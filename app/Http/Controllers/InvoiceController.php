<?php

namespace App\Http\Controllers;

use App\Http\Requests\UploadCsvRequest;
use App\Invoice;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class InvoiceController extends Controller
{

    public function index()
    {
        $invoices = User::find(1)->invoices; //Used 1 as id of dummy user created by seeder

        if($invoices){
            $invoices->transform(function ($invoice){
                $invoice->amount = number_format($invoice->amount,2);
                $invoice->selling_price = number_format($invoice->selling_price,2);
                return $invoice->only(['invoice_id','amount','due_on','selling_price']);
            });
        }

        return response()->json(['status' => 200, 'message' => 'List of all invoices', 'data' => $invoices]);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $invoices = Invoice::paginate(100); //Assuming the pagination should be 100
        return view('invoice.create',['invoices'=>$invoices]);
    }

    /**
     * This method is with Form Request Validation
     *
     * @param  UploadCsvRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(UploadCsvRequest $request)
    {
        return $this->processFile($request,'redirect');
    }

    private function calculateSellingPrice($amount, $dueDate)
    {
        $dueDate = Carbon::parse($dueDate);
        $today = Carbon::now();
        $difference = $today->diffInDays($dueDate,false);

        if ($difference>30) {
            $sellingPrice = $amount * 0.5;
        } else {
            $sellingPrice = $amount * 0.3;
        }

        return $sellingPrice;
    }

    /**
     * This method is with validation in method
     *
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function save(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'file' => 'required|file|mimes:csv,txt',
        ]);

        if ($validate->fails()) {
            return response()->json(['status' => 400, 'message' => 'Validation failed', 'errors' => $validate->errors()->getMessageBag()]);
        }

        return $this->processFile($request,'response');

    }

    private function processFile($request,$type)
    {
        $path = $request->file('file')->getRealPath();
        $records = array_map('str_getcsv', file($path));

        $data = [];
        $errors = [];

        foreach ($records as $key => $record) {

            if (is_valid_record($record)) {
                $data[$key]['user_id'] = 1; //Used 1 as id of dummy user created by seeder
                $data[$key]['invoice_id'] = $record[0];
                $data[$key]['amount'] = $record[1];
                $data[$key]['due_on'] = $record[2];
                $data[$key]['selling_price'] = $this->calculateSellingPrice($record[1], $record[2]);
            } else {

                if ($type == 'response') {
                    $errors[$key] = ++$key;
                } else {
                    return redirect()->back()->withErrors(["Row ".++$key." has invalid data"]);
                }

            }

        }

        if (!empty($errors)) {
            $errors = "Rows ".implode(",",$errors)." are skipped due to invalid data.";
        }

        $insert = Invoice::insert($data);

        if ($insert) {

            if ($type == 'response') {
                return response()->json(['status' => 200, 'message' => 'File has been uploaded successfully!', 'errors' => $errors]);
            }
            return redirect('invoices/get')->with('status', 'File has been uploaded successfully!');
        }
    }

}
