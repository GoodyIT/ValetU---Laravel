<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\DB;
use App\Uberuser;
use App\Parkinglot;

class UberController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
       
    }

    public function savetoken(Request $request){
    	$token = $request->post("access_token");
        $name = $request->post("name");
        $email = $request->post("email");


        if (empty($email)) {
            return json_decode(["status"=>"error"]);
        }

        $user = DB::table('uberusers')->where('email', $email)->first();

        if (empty($users)) {
            $newUser = new Uberuser;
            $newUser->name = $name;
            $newUser->email = $email;
            $newUser->uber_credential = $token;
            $newUser->save();

            return $newUser;
        } else {
            $user->uber_credential = $token;
        }

    	return $token;
    }

    public function findnearby(Request $request){
        $lat = $request->post("lat");
        $lng = $request->post("lng");
        $direction = $request->post("direction");

        $result["status"] = "Error";
        if (empty($lat) || empty($lng)) {
            return json_encode($result);
        }

        $sql = sprintf('3956 * 2 * ASIN(SQRT( POWER(SIN((%s - abs(latitude)) * pi()/180 / 2),2) + COS(%s * pi()/180 ) 
 * COS(abs(latitude) * pi()/180) * POWER(SIN((%s - longitude) * pi()/180 / 2),2) ))', $lat, $lat, $lng);

        $parkinglot = DB::table('parkinglots')
                            ->select(DB::raw('*,' . $sql . ' as distance'))
                            ->get();

        $result["status"] = "Ok";
        $result["places"] = $parkinglot;

        return json_encode($result);
    }
}
