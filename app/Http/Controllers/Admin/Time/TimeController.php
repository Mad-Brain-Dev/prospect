<?php

namespace App\Http\Controllers\Admin\Time;

use App\Exports\ExportProspects;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Suppression;
use App\Models\Time;
use http\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Excel;

class TimeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
//        $customers = Customer::orderBy('id','desc')->get();
        $times = Time::all();

//        $data = ['customers'=>$customers,'times'=>$times];
        $data = ['times'=>$times];

        return response()->json($data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        set_page_meta('Create Timeshare');

//        $users = Customer::orderBy('id','desc')->get();

        $times = Time::all();


        $auth = Auth::user();
        //return $users;
//        return view('admin.timeshares.create',compact('users','times','auth'));
        return view('admin.timeshares.create',compact('times','auth'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

//        dd($request->all());
        $csv_name = $request->form_data['zip'].'timeshare'.time().'.csv';
        $timeshare = Time::create([
            "name" => $request->form_data['name'],
            "city" => $request->form_data['city'],
            "state" => $request->form_data['state'],
            "zip" => $request->form_data['zip'],
            "phone" => $request->form_data['phone'],
            "radius" => $request->form_data['radius'],
            "csv_file_name"=>$csv_name,
        ]);

        if ($timeshare){

            foreach ($request->prospects as $key=>$prospect){
                $suppression = Suppression::create([
                    "time_id"=>$timeshare->id,
                    "first_name"=>$prospect['first_name'] != null ? $prospect['first_name']:null,
                    "last_name"=>$prospect['last_name'] != null ? $prospect['last_name']:null,
                    "address_one"=>$prospect['address_one'] != null ? $prospect['address_one']:null,
                    "address_two"=>$prospect['address_two'] != null ? $prospect['address_two']:null,
                    "city"=>$prospect['city'] != null ? $prospect['city']:null,
                    "state"=>$prospect['state'] != null ? $prospect['state']:null,
                    "zip"=>$prospect['zip'] != null ? $prospect['zip']:null,
                    "zip_plus_four"=>$prospect['zip_plus_four'] != null ? $prospect['zip_plus_four']:null,
                    "delivery_point"=>$prospect['delivery_point'] != null ? $prospect['delivery_point']:null,
                    "state_code"=>$prospect['state_code'] != null ? $prospect['state_code']:null,
                    "county"=>$prospect['county'] != null ? $prospect['county']:null,
                    "latitude"=>$prospect['latitude'] != null ? $prospect['latitude']:null,
                    "longitude"=>$prospect['longitude'] != null ? $prospect['longitude']:null,
                    "carrier"=>$prospect['carrier'] != null ? $prospect['carrier']:null,
                    "segment"=>$prospect['segment'] != null ? $prospect['segment']:null,
                    "org_phone"=>$prospect['org_phone'] != null ? $prospect['org_phone']:null,
                    "resort"=>$prospect['resort'] != null ? $prospect['resort']:null,
                    "phone_one"=>$prospect['phone_one'] != null ? $prospect['phone_one']:null,
                    "phone_two"=>$prospect['phone_two'] != null ? $prospect['phone_two']:null,
                ]);
            }

        }
        $response = [
            'status'=>200,
            'message'=>'Data saved successfully',
            'timeshare'=>$timeshare
        ];
        return response()->json($response);
//        dd($request->all());
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
        $suppressions = Suppression::where('time_id',$id)->get();

        foreach ($suppressions as $suppression){
            $suppression->delete();
        }
        $time = Time::find($id);
        $time->delete();
        return back();
    }

    public function filter(Request $request)
    {

    $name = $request->name != null ? $request->name : '';
    $city = $request->city != null ? $request->city : '';
    $zip = $request->zip != null ? $request->zip : '';
    $state = $request->state != null ? $request->state : '';
    $phone = $request->phone != null ? $request->phone : '';
    $radius = $request->radius != null ? $request->radius : '';

        if ($zip && $radius ){

            $present_location = Customer::where('zip',$zip)->first();
            if ($present_location){
                $latitude = $present_location->latitude;
                $longitude = $present_location->longitude;
                /*
                 * using query builder approach, useful when you want to execute direct query
                 * replace 6371000 with 6371 for kilometer and 3956 for miles
                 */
                $query = DB::table('customers')
                    ->selectRaw("id, first_name, last_name,address_one,address_two,zip_plus_four,delivery_point,state_code,carrier,county,segment,org_phone,resort, latitude,longitude,city,state,zip,phone_one,phone_two,
                     ( 3956 * acos( cos( radians(?) ) *
                       cos( radians( latitude ) )
                       * cos( radians( longitude ) - radians(?)
                       ) + sin( radians(?) ) *
                       sin( radians( latitude ) ) )
                     ) AS distance", [$latitude, $longitude, $latitude])
//                    ->orWhere(function($query)use ($city,$zip,$state,$phone) {
//                        $query->orWhere('zip',$zip)
//                            ->orWhere('state',$state)
//                            ->orWhere('city',$city)
//                            ->orWhere('phone_one',$phone)
//                            ->orWhere('phone_two',$phone);
//                    })
//                    ->orWhere('city', 'like', "%$city%")
//                    ->orWhere('state', '=', $state)
//                    ->orWhere('zip', '=', $zip)
//                    ->orWhere(function ($query) use ($phone) {
//                        $query->where('phone_one', '=', $phone)
//                            ->orWhere('phone_two', '=', $phone);
//                    })
                    ->having("distance", "<", $radius)
                    ->orderBy("distance",'asc');
//            ->offset(0)
//            ->limit(20)
//                    ->get();


                if ($query && $city){
                    $query->where('city', 'like', "%$city%");
                }

                if ($query && $state){
                    $query->where('state', 'like', "%$state%");
                }

                if ($query && $phone){
                    $query->where(function ($query) use ($phone) {
                        $query->where('phone_one', '=', $phone)
                            ->orWhere('phone_two', '=', $phone);
                    });
                }

                $customers = $query->get();
                $count = $query->count();

//                dd($customers);
                if ($customers){
                    $json = ["customers"=>$customers,"center"=>$present_location,"radius"=>$radius,'count'=>$count];
                }else{
                    $json = ["customers"=>[],"center"=>[],"radius"=>[],'count'=>0];
                }

                return response()->json($json);
            }else{
                return response()->json([]);
            }

        }else{
//            $customers = Customer::where('city','=',$city)
//                ->orWhere(function($query)use ($zip,$state,$phone) {
//                    $query->orWhere('zip',$zip)
//                        ->orWhere('state',$state)
//                        ->orWhere('phone_one',$phone)
//                        ->orWhere('phone_two',$phone);
//                })
////                ->where('zip','=',$zip)
////                ->where('state','=',$state)
////                ->where(function ($query) use ($phone) {
////                    $query->where('phone_one', '=', $phone)
////                        ->orWhere('phone_two', '=', $phone);
////                })
//                ->get();

//            dd('hello');
            $query = Customer::orderBy("id", 'asc');

            if ($zip) {
                $query->where('zip', 'like', "%$zip%");
            }

            if ($city) {
                $query->where('city', 'like', "%$city%");
            }

            if ($state) {
                $query->where('state', 'like', "%$state%");
            }

            if ($phone) {
                $query->where(function ($query) use ($phone) {
                    $query->where('phone_one', '=', $phone)
                        ->orWhere('phone_two', '=', $phone);
                });
            }

            $customers = $query->get();
            $count = $query->count();

//            dd($customers);
            if ($customers){
                $json = ["customers"=>$customers,"radius"=>$radius,'count'=>$count];
            }else{
                $json = ["customers"=>[],"center"=>[],"radius"=>[],"count"=>0];
            }

            return response()->json($json);
        }

    }

    public function suppressed(Request $request)
    {
//        dd($request->all());

        $ids = $request->all();

        foreach ($ids as $id){
            $suppressions = Suppression::where('time_id',$id)->get();
            foreach ($suppressions as $suppression){
                $lastTimeId = Time::orderBy('id', 'desc')->first();
                $lastSups = Suppression::where('time_id', $lastTimeId->id)->get();
                foreach ($lastSups as $lastSup){
                    if ($lastSup->first_name == $suppression->first_name){
                        $lastSup->delete();
                    }
                }

            }
        }

        $sups = Suppression::where('time_id', $lastTimeId->id)->get();
        $count = $sups->count();
        $data = [
            'status'=>200,
            'message'=>'Successfully Suppressed',
            'sups'=>$sups,
            'count'=>$count
        ];
        return response()->json($data);
    }

    public function download($id)
    {
        $fileName = Time::find($id);
        return \Excel::download(new ExportProspects($id), $fileName->csv_file_name);
    }

    public function countAllCustomer()
    {
        $count = Customer::count();

        $data = ['count'=>$count];

        return response()->json($data);
    }
}
