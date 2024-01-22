<?php

namespace App\Http\Controllers\Admin\Customer;

use App\DataTables\CustomerDataTable;
use App\DataTables\TimeDataTable;
use App\Http\Controllers\Controller;
use App\Imports\ImportCustomer;
use App\Jobs\ProcessProspects;
use App\Models\Customer;
use App\Models\JobBatch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use mysql_xdevapi\Exception;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
//    public function index(CustomerDataTable $dataTable)
//    {
//        set_page_meta('Prospects');
//        return $dataTable->render('admin.customers.index');
//    }
    public function index(TimeDataTable $dataTable)
    {
        set_page_meta('Prospects');
        $total_prospect = Customer::count();
        return $dataTable->render('admin.customers.index',compact('total_prospect'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function findCustomerInRadiusIndex()
    {
        set_page_meta('Find Prospects');
        $auth = Auth::user();
        return view('admin.customers.find_customers',compact('auth'));
    }

    public function findCustomerInRadiusPost(Request $request)
    {
        set_page_meta('Find Customers');
        $data = $request->all();
        $present_location = Customer::where('zip',$data['zip_code'])->first();
        if ($present_location){
            $latitude = $present_location->latitude;
            $longitude = $present_location->longitude;
            /*
             * using query builder approach, useful when you want to execute direct query
             * replace 6371000 with 6371 for kilometer and 3956 for miles
             */
            $customers = DB::table('customers')
                ->selectRaw("id, first_name, last_name,address_one,address_two,city,state,zip,zip_plus_four,delivery_point,state_code,county, latitude,longitude,carrier,segment,org_phone,resort,phone_one,phone_two,email,
                     ( 3956 * acos( cos( radians(?) ) *
                       cos( radians( latitude ) )
                       * cos( radians( longitude ) - radians(?)
                       ) + sin( radians(?) ) *
                       sin( radians( latitude ) ) )
                     ) AS distance", [$latitude, $longitude, $latitude])
//            ->where('active', '=', 1)
                ->having("distance", "<", $data['radius'])
                ->orderBy("distance",'asc')
//            ->offset(0)
//            ->limit(20)
                ->get();

            if ($customers){
                $json = ["customers"=>$customers,"center"=>$present_location,"radius"=>$data['radius']];
            }else{
                $json = ["customers"=>[],"center"=>[],"radius"=>[]];
            }

            return response()->json($json);
        }else{
            return response()->json([]);
        }

//        dd($customers) ;
    }

//    public function importCustomerToExcel(Request $request)
//    {
//
////        return response()->json(['status_code'=>200,'status'=>'success','message'=>'Data imported successfully!','data'=>[]]);
//        if ($request->file('file')){
//            try {
//                Excel::import(new ImportCustomer, $request->file('file'));
//                return response()->json(['status_code'=>200,'status'=>'success','message'=>'Data imported successfully!','data'=>[]]);
//            }catch (\Exception $exception){
//                return response()->json(['status_code'=>500,'status'=>'error','message'=>'Please follow standard format!','data'=>[]]);
//            }
//        }else{
//            return response()->json(['status_code'=>500,'status'=>'error','message'=>'Please select a excel file!','data'=>[]]);
//        }
//
//        dd('imported successfully');
//        return back();
//    }


    public function importCustomerToExcel(Request $request)
    {

//        dd($request->all());


//        try {
//            if ($request->file('file')){
//
//                $file = $request->file('file');
//
//                $records = array_map('str_getcsv',file($file));
//
////                dd($records);
//                // Chunking file
//                $chunks = array_chunk($records, 1000);
////            dd($chunks);
//                $header = null;
//                $batch  = Bus::batch([])->dispatch();
//                foreach ($chunks as $key => $chunk) {
////                    $data = array_map('str_getcsv', $chunk);
//
//                    if ($key === 0) {
//                        $header = $records[0];
//                        unset($records[0]);
//                        unset($chunk[0]);
//                    }
//
//                    $batch->add(new ProcessProspects($chunk, $header));
//                }
//
//                return response()->json(['status_code'=>200,'status'=>'success','message'=>'File Submitted,Uploading.Please wait...','data'=>$batch]);
//
//            }else{
//                return response()->json(['status_code'=>500,'status'=>'error','message'=>'Please select a excel file!','data'=>[]]);
//            }
//        }catch (\Exception $exception){
//         Log::error($exception);
//        }

//        dd(file(request()->file));
//        $file = $request->file('file');
//        dd($file);

        try {
            if (request()->has('file')){

                // Get The Upload File From Request
                $data = file(request()->file);

//            dd($data);
                // Create an empty Batch and then dispatch it
                $batch = Bus::batch([])->dispatch();						// Add This line

                // Chunk File to multi files
                $chunks = array_chunk($data, 1000);
//            dd($chunks);
                $header = [];
                foreach($chunks as $key => $chunk) {
                    // Get File Content and save it as an array
                    $data = array_map('str_getcsv', $chunk);

                    if ($key === 0) {
                        $header = $data[0];
                        unset($data[0]);
                    }
                    // add The Job to the batch
//                dd($data);
                    $batch->add(new ProcessProspects($data, $header));		// Add This line
                }

                return response()->json(['status_code'=>200,'status'=>'success','message'=>'File Submitted,Uploading.Please wait...','data'=>$batch]);

            }else{
                return response()->json(['status_code'=>500,'status'=>'error','message'=>'Please select a excel file!','data'=>[]]);
            }
        }catch (\Exception $exception){
            Log::error($exception);
        }
    }
    public function downloadSampleFile()
    {
        return response()->download(storage_path('app/public/sample/sample.csv'));
    }

    public function getUploadProgress($id)
    {
        try {

            if (JobBatch::where('id',$id)->count()){
                $response = JobBatch::where('id',$id)->first();
                return response()->json(['status_code'=>200,'status'=>'success','message'=>'Upload progressing.Please wait...','data'=>$response]);
            }

        }catch (\Exception $exception){
            Log::error($exception);
            dd($exception);
        }

    }

}
