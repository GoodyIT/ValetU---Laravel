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
        $token =  $request->get("token");
        $name  =  $request->get("name");
        $email = $request->get("email");

        $result["status"] = "error";
        if (empty($email) || empty($token) || empty($name)) {
            return json_encode($result);
        } else {
            $sql = sprintf("select * from uberusers where email='%s'", $email);
            $users = DB::statement($sql);

            if (isset($users) && count($users) == 0 ) {
                $sql = sprintf("INSERT INTO uberusers (name, email, uber_credential) VALUES ('%s', '%s', '%s')", $name, $email, $token);

                $result["test"] = "inserted";
            } else {
                 $sql = sprintf("UPDATE uberusers SET name='%s', uber_credential='%s' where email='%s'", $name, $token, $email);
                  $result["test"] = "updated";
            }
            DB::statement($sql);

            $result["status"] = "Ok";
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

        $parkinglots = DB::select($sql);

        $result["status"] = "Ok";
        $result["data"] = [];
        foreach ($parkinglots as $lotkey => $parkinglot) {
            $http = new \GuzzleHttp\Client;
            $result["data"][$lotkey] = [];

            $response = $http->get('https://api.uber.com/v1/estimates/price', [
                'form_params' => [
                    'start_latitude' => $lat,
                    'start_longitude' => $lng,
                    'end_latitude' => $parkinglot->latitude,
                    'end_longitude' => $parkinglot->longitude,
                    'server_token' => 'VcR8_A-Xex3YhVGTUvjDWBQhDa3ygeBFHBXU73L7',
                ],
            ]);

            $prices = json_decode($response->getBody())->prices;
            foreach ($prices as $price) {
                if ($price->localized_display_name == "uberX") {
                    $result["data"][$lotkey]['price'] = [];
                    $result["data"][$lotkey]['place'] = [];
                    array_push($result["data"][$lotkey]['place'], $parkinglot);
                    array_push($result["data"][$lotkey]['price'], $price);
                    continue;
                }
            }
        }


        return json_encode($result);
    }
}
