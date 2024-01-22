<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\LazyCollection;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        Customer::truncate();

        for ($x = 1; $x <= 7; $x++) {
            $csvFile = fopen(base_path("database/data/customers"."-".$x.".csv"), "r");

            $firstline = true;
            while (($row = fgetcsv($csvFile, 2000, ",")) !== FALSE) {
                if (!$firstline) {
                    Customer::create([
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
                $firstline = false;
            }

            fclose($csvFile);
        }


//start
//        $csvFile = fopen(base_path("database/data/customers.csv"), "r");
////        $fp = file(base_path("database/data/customers.csv"), FILE_SKIP_EMPTY_LINES);
////        $count = count($fp);
////        $length = $count-1;
//
//        $firstline = true;
//        while (($row = fgetcsv($csvFile, 2000, ",")) !== FALSE) {
//            if (!$firstline) {
//                Customer::create([
//                    "first_name" => $row[0],
//                    "last_name" => $row[1],
//                    "address_one" => $row[2],
//                    "address_two" => $row[3],
//                    "city" => $row[4],
//                    "state" => $row[5],
//                    "zip" => $row[6],
//                    "zip_plus_four" => $row[7],
//                    "delivery_point" => $row[8],
//                    "state_code" => $row[9],
//                    "county" => $row[10],
//                    "latitude" => $row[11],
//                    "longitude" => $row[12],
//                    "carrier" => $row[13],
//                    "segment" => $row[14],
//                    "org_phone" => $row[15],
//                    "resort" => $row[16],
//                    "phone_one" => $row[17],
//                    "phone_two" => $row[18],
//                    "email" => $row[19],
//                ]);
//            }
//            $firstline = false;
//        }
//
//        fclose($csvFile);

        //ai porjonto



//        LazyCollection::make(function () {
//            $handle = fopen(base_path("database/data/customers.csv"), 'r');
////            $fp = file(base_path("database/data/customers.csv"), FILE_SKIP_EMPTY_LINES);
////            $count = count($fp);
////            $length = $count-1;
//
//            while (($line = fgetcsv($handle, 2000)) !== false) {
//                $dataString = implode(", ", $line);
//                $row = explode(';', $dataString);
//                yield $row;
//            }
//
//            fclose($handle);
//        })
//            ->skip(1)
//            ->chunk(500)
//            ->each(function (LazyCollection $chunk) {
//                $records = $chunk->map(function ($row) {
//                    return [
//                        "first_name" => $row[0],
//                        "last_name" => $row[1],
//                        "address_one" => $row[2],
//                        "address_two" => $row[3],
//                        "city" => $row[4],
//                        "state" => $row[5],
//                        "zip" => $row[6],
//                        "zip_plus_four" => $row[7],
//                        "delivery_point" => $row[8],
//                        "state_code" => $row[9],
//                        "county" => $row[10],
//                        "lat" => $row[11],
//                        "long" => $row[12],
//                        "carrier" => $row[13],
//                        "segment" => $row[14],
//                        "org_phone" => $row[15],
//                        "resort" => $row[16],
//                        "phone_one" => $row[17],
//                        "phone_two" => $row[18],
//                        "email" => $row[19],
//                    ];
//                })->toArray();
//
//                dd($records);
////                DB::table('customers')->insert($records);
//            });
    }
}
