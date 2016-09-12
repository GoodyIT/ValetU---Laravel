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

    /*public function savetoken(Request $request){
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
            DB::table('uberusers')->insert(
                ['email' => $email, 'name' => $name, 'uber_credential'=>$token]
            );
        } else {
            DB::table('uberusers')
                ->where('email', $email)
                ->update(['uber_credential' => $token]);
        }

        $result["status"] = "Ok";

        return json_encode($result);
    }*/

     public function test(Request $request){
        $token =  $request->get("token");
        $name  =  $request->get("name");
        $email = $request->get("email");

        $result["status"] = "error";
        if (empty($email) || empty($token) || empty($name)) {
            return json_encode($result);
        } else {
            DB::table('uberusers')->insertGetId(
                    ['email' => $email, 'name' => $name, 'uber_credential' => $token]
                );
            return json_encode(['email' => $email, 'name' => $name, 'token' => $token]);
          //  $filecontents = $token . $name . $email;
            // file_put_contents("test.txt", json_encode($filecontents)); 
            $users = DB::select("select * from uberusers where email='$email'");

            //    $filecontents .=  json_encode($users);
              //   file_put_contents("test.txt", json_encode($filecontents)); 

            if (isset($users) && count($users) == 0 ) {
                  // $filecontents .= "empty";
                // file_put_contents("test.txt", json_encode($filecontents)); 
                DB::table('uberusers')->insertGetId(
                    ['email' => "sdf@gmail.com", 'name' => "name", 'uber_credential' => "token"]
                );

               /* DB::statement( 'insert into uberusers (name, email, uber_credential) values (:name, :email, :token)', array('name' => $name, 'email' => $email, 'token' => $token));*/
              
                $result["test"] = "inserted";
            } else {
                 // $filecontents .= "update";
                    // file_put_contents("test.txt", json_encode($filecontents)); 
                DB::table('uberusers')
                    ->where('email', $email)
                    ->update(['uber_credential' => $token]);
                     $result["test"] = "updated";
            }


            $result["status"] = "Ok";
         //   $result["test"] = json_encode($users);
           

          //  $filecontents .= json_encode($result);
            // file_put_contents("test.txt", json_encode($filecontents)); 

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

        $sql = "select t1.address, t1.latitude, t1.longitude, t2.request, t2.photourl, t2.comment, t2.star, 3956 * 2 * ASIN(SQRT( POWER(SIN(($lat - abs(t1.latitude)) * pi()/180 / 2),2) + COS($lat * pi()/180 ) 
 * COS(abs(t1.latitude) * pi()/180) * POWER(SIN(($lng - t1.longitude) * pi()/180 / 2),2) )) as distance from parkinglots as t1 left join trips as t2 on t1.id = t2.parkinglot_id where 3956 * 2 * ASIN(SQRT( POWER(SIN(($lat - abs(t1.latitude)) * pi()/180 / 2),2) + COS($lat * pi()/180 ) 
 * COS(abs(t1.latitude) * pi()/180) * POWER(SIN(($lng - t1.longitude) * pi()/180 / 2),2) )) < 16 ";

        /*$sql = "select *, 3956 * 2 * ASIN(SQRT( POWER(SIN(($lat - abs(latitude)) * pi()/180 / 2),2) + COS($lat * pi()/180 ) 
 * COS(abs(latitude) * pi()/180) * POWER(SIN(($lng - longitude) * pi()/180 / 2),2) )) as distance from parkinglots having distance < 1600";*/

        $parkinglot = DB::select($sql);

        $result["status"] = "Ok";
        $result["places"] = $parkinglot;

        return json_encode($result);
    }
}
