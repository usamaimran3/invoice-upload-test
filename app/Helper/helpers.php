<?php
/**
 * Created by PhpStorm.
 * User: usama
 * Date: 9/28/2019
 * Time: 10:11 PM
 */

function is_valid_record($record)
{

    if (filled($record[0]) && filled($record[1]) && filled($record[2]) && is_numeric($record[0]) && is_valid_date($record[2]) && (is_numeric($record[1]) || is_float($record[1])) ) {
        return true;
    }

    /*if (filled($record[0]) && filled($record[1]) && filled($record[2])) {
        $status = true;
    } else {
        return "Record is empty";
    }

    if (is_numeric($record[0])) {
        $status = true;
    } else {
        return "Invoice ID is not numeric";
    }

    if (is_numeric($record[1]) || is_float($record[1])) {
        $status = true;
    } else {
        return "Amount is invalid";
    }

    if (is_valid_date($record[2])) {
        $status = true;
    } else {
        return "Date is invalid";
    }*/

    return false;
}

function is_valid_date($date)
{
    $d = DateTime::createFromFormat('Y-m-d', $date);
    return $d && $d->format('Y-m-d') === $date;
}