@extends('layout.app')

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <h2 id="sizing">Upload CSV</h2>
            @if($errors->any())
                @foreach($errors->all() as $message)
                    <div class="alert alert-danger alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                        <strong>Error!</strong> {{$message}}
                    </div>
                @endforeach
            @endif
            @if (session('status'))
                <div class="alert alert-success alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    {{ session('status') }}
                </div>
            @endif
            <form action="{{ route('upload') }}" method="post" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="form-group">
                    <input type="file" name="file">
                    <input type="submit" class="btn btn-info" name="upload_csv" value="Upload">
                </div>
            </form>
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th scope="col">Invoice ID</th>
                    <th scope="col">Amount</th>
                    <th scope="col">Due On</th>
                    <th scope="col">Selling Price</th>
                </tr>
                </thead>
                <tbody>
                    @forelse ($invoices as $invoice)
                        <tr>
                            <th scope="row">Invoice # {{ $invoice->invoice_id }}</th>
                            <td>{{ number_format($invoice->amount,2) }}</td>
                            <td>{{ $invoice->due_on }}</td>
                            <td>{{ number_format($invoice->selling_price,2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <th scope="row" colspan="4">No records found!</th>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="row float-right">
                {{ $invoices->links() }}
            </div>
        </div>
    </div>
@endsection

