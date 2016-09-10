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
    	$token = $request->get("access_token");
        $name = $request->get("name");
        $email = $request->get("email");

        $result["status"] = "error";
        if (!empty($email) && !empty($token) && !empty($name)) {
            $user = DB::table('uberusers')->where('email', $email)->first();

            if (empty($user->name)) {
                $newUser = new Uberuser;
                $newUser->name = $name;
                $newUser->email = $email;
                $newUser->uber_credential = $token;
                $newUser->save();
            } else {
                DB::table('uberusers')
                    ->where('email', $email)
                    ->update(['uber_credential' => $token]);
            }

            $result["status"] = "Ok";
        }

    	return json_encode($result);
    }

    public function logintoken(Request $request){
        $token =  $request->get("access_token");
        $name  =  $request->get("name");
        $email = $request->get("email");

        $result["status"] = "error";
        if (empty($email) || empty($token) || empty($name)) {
            return json_encode($result);
        }
       
        $user = DB::table('uberusers')->where('email', $email)->first();

        if (empty($user->name)) {
            $newUser = new Uberuser;
            $newUser->name = $name;
            $newUser->email = $email;
            $newUser->uber_credential = $token;
            $newUser->save();
        } else {
            DB::table('uberusers')
                ->where('email', $email)
                ->update(['uber_credential' => $token]);
        }

        $result["status"] = "Ok";

        return json_encode($result);
    }

     public function test(Request $request){
        $token =  $request->get("access_token");
        $name  =  $request->get("name");
        $email = $request->get("email");

        $result = [];
        $result["status"] = "error";
        if (empty($email) || empty($token) || empty($name)) {
            return json_encode($result);
        } else {
            $users = DB::table('uberusers')
                ->where('email', $email)
                ->get()->first();

            if (empty($users->name)) {
                $newUser = new Uberuser;
                $newUser->name = $name;
                $newUser->email = $email;
                $newUser->uber_credential = $token;
                $newUser->save();

            } else {
                DB::table('uberusers')
                    ->where('email', $email)
                    ->update(['uber_credential' => $token]);
            }


            $result["status"] = "Ok";
            $result["test"] = "test";

            file_put_contents("test.txt", json_encode($result)); 

            return json_encode($result);
        }
     }

    public function findnearby(Request $request){
        $lat = $request->get("lat");
        $lng = $request->get("lng");
        $direction = $request->get("direction");

        $result["status"] = "Error";
        if (empty($lat) || empty($lng)) {
            return json_encode($result);
        }

        $sql = "*, 3956 * 2 * ASIN(SQRT( POWER(SIN(($lat - abs(latitude)) * pi()/180 / 2),2) + COS($lat * pi()/180 ) 
 * COS(abs(latitude) * pi()/180) * POWER(SIN(($lng - longitude) * pi()/180 / 2),2) )) as distance from parkinglots having distance < 500000";

        $parkinglot = DB::select(DB::raw("select *, 3956 * 2 * ASIN(SQRT( POWER(SIN((37 - abs(latitude)) * pi()/180 / 2),2) + COS(37 * pi()/180 ) 
 * COS(abs(latitude) * pi()/180) * POWER(SIN((37 - longitude) * pi()/180 / 2),2) )) as distance from parkinglots "));
                            
        $result["status"] = "Ok";
        $result["places"] = $parkinglot;

        return json_encode($result);
    }
}
