<?php

namespace App\Imports;

use App\Models\Customer;
use Maatwebsite\Excel\Concerns\ToModel;

class ImportCustomer implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
//        dd($row);
        return new Customer([
            "first_name" => $row[0],
            "last_name" => $row[1],
            "address_one" => $row[2],
            "address_two" => $row[3],
            "city" => $row[4],
            "state" => $row[5],
            "zip" => $row[6],
            "zip_plus_four" => $row[7],
            "delivery_point" => $row[8],
            "state_code" => $row[9],
            "county" => $row[10],
            "latitude" => $row[11],
            "longitude" => $row[12],
            "carrier" => $row[13],
            "segment" => $row[14],
            "org_phone" => $row[15],
            "resort" => $row[16],
            "phone_one" => $row[17],
            "phone_two" => $row[18],
            "email" => $row[19],
        ]);
    }
}
