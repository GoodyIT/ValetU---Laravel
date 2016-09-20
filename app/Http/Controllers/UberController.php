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

        /*$sql = "select t1.id, t1.address, t1.latitude, t1.longitude, t2.request, t2.photourl, t2.comment, t2.star, t3.name, t3.email, 3956 * 2 * ASIN(SQRT( POWER(SIN(($lat - abs(t1.latitude)) * pi()/180 / 2),2) + COS($lat * pi()/180 ) 
 * COS(abs(t1.latitude) * pi()/180) * POWER(SIN(($lng - t1.longitude) * pi()/180 / 2),2) )) as distance from parkinglots as t1 join trips as t2 on t1.id = t2.parkinglot_id join uberusers as t3 on t2.user_id = t3.id where 3956 * 2 * ASIN(SQRT( POWER(SIN(($lat - abs(t1.latitude)) * pi()/180 / 2),2) + COS($lat * pi()/180 ) 
 * COS(abs(t1.latitude) * pi()/180) * POWER(SIN(($lng - t1.longitude) * pi()/180 / 2),2) )) < 16 ";*/
        $sql = "select t1.id, t1.address, t1.latitude, t1.longitude, t1.star, 3956 * 2 * ASIN(SQRT( POWER(SIN(($lat - abs(t1.latitude)) * pi()/180 / 2),2) + COS($lat * pi()/180 ) 
 * COS(abs(t1.latitude) * pi()/180) * POWER(SIN(($lng - t1.longitude) * pi()/180 / 2),2) )) as distance from parkinglots as t1 where 3956 * 2 * ASIN(SQRT( POWER(SIN(($lat - abs(t1.latitude)) * pi()/180 / 2),2) + COS($lat * pi()/180 ) 
 * COS(abs(t1.latitude) * pi()/180) * POWER(SIN(($lng - t1.longitude) * pi()/180 / 2),2) )) < 16 ";

        $parkinglots =  DB::select($sql);

        $result["status"] = "Ok";
        $result["data"] = [];

        foreach ($parkinglots as $lotkey => $parkinglot) {
            $data = [];

            $http = new \GuzzleHttp\Client;
            $response = $http->get('https://api.uber.com/v1/estimates/price', [
                'form_params' => [
                    'start_latitude' => $lat,
                    'start_longitude' => $lng,
                    'end_latitude' => $parkinglot->latitude,
                    'end_longitude' => $parkinglot->longitude,
                    'server_token' => 'VcR8_A-Xex3YhVGTUvjDWBQhDa3ygeBFHBXU73L7',
                ],
            ]);

              foreach ($parkinglot as $_key => $_value) {
                $data[$_key] = $_value;
            }

           /* $comments = DB::table('trips')
                ->join('uberusers', 'trips.user_id', '=', 'uberusers.id')
                ->select('trips.*', 'contacts.phone', 'orders.price')
                ->where('trips.parkinglot_id', '=', $parkinglot->id)
                ->get()->asArray();*/

            $sql = "select t1.id, t1.photourl, t1.comment, t1.updated_at, t2.id, t2.name, t2.email from trips as t1 join uberusers as t2 where t1.parkinglot_id = $parkinglot->id";

            $comments =  DB::select($sql);

            $data['comments'] = [];
            $numberOfPhotos = 0;
            foreach ($comments as $commentkey => $commentvalue) {
                foreach ($commentvalue as $subkey => $subvalue) {
                    if ($subkey == "photourl" && $subvalue != null) {
                        $numberOfPhotos++;
                    }
                }
                array_push($data['comments'], $commentvalue);
            }

            $data['numberOfPhotos'] = $numberOfPhotos;

            $prices = json_decode($response->getBody())->prices;
            foreach ($prices as $price) {
                if ($price->localized_display_name == "uberX") {
                    $data['estimate'] = $price->estimate;
                    $data['duration'] = $price->duration;
                    $data['distance'] = $price->distance;
                    continue;
                }
            }

            array_push($result["data"], $data);
        }
/*
           foreach ($parkinglots as $lotkey => $parkinglot) {
            $result["data"][$lotkey] = $parkinglot;
        }
*/

        return json_encode($result);
    }

    public function savereview(Request $request)
    {
        $uuid = $request->input("uuid");
        $parkinglot_id = $request->input("parkinglot_id");
        $review = $request->input("review");
        $star = $request->input("star");

        $users = DB::table('uberusers')
                    ->where('uber_credential', $uuid)
                    ->first();
        $result = [];
        if (!isset($users->id) || $request->file('image') == null) {
            $result['status'] = "Fail";
        } else {
            $imageName = $parkinglot_id . "_" . time() .  $request->file('image')->extension();
            $path = $request->file('image')->storeAs('images', $imageName);

            DB::table('trips')
                ->insert([
                    'parkinglot_id'=>$parkinglot_id,
                     'user_id'=>$users->id,
                   //  'photourl'=>$path,
                     'star' => $star,
                     'review' => $review
                    ]);

            $result['status'] = "Ok";
        }
      
        return $result;
    }
}
