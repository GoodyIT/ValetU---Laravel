<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\DB;
use App\Uberuser;
use App\Trip;
use App\Parkinglot;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

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

    // create function for our upload page
    public function index(){
      return view('uploadfile');
    }

     public function savetoken(Request $request){
        $token =  $request->input("token");
        $name  =  $request->input("name");
        $email = $request->input("email");
        $photoUrl = $request->input("photoUrl");

        $result["status"] = "error";
        if (empty($email) || empty($token) || empty($name)) {
            $result["message"] = "Input is not proper";
            $result["name"] = $name;
            $result["email"] = $email;
            $result["token"] = $token;
            return json_encode($result);
        } else {
            $uberusers = DB::table('uberusers')
                    ->where('email', $email);

            $lastInfo= [];
            if (empty($uberusers)) {
                $uberuser = new Uberuser;
                $uberuser->name = $name;
                $uberuser->email = $email;
                $uberuser->uber_credential = $token;
                $userId = $uberuser->save();

                $result["test"] = "inserted";
                $result["userId"] = $userId;
            } else {
               $userId = Uberuser::where('email', $email)
                        ->update(['name' => $name,
                                 'uber_credential' => $token,
                                 ]);
               
                $result["test"] = "updated";

                // Get the last review
                $sql = "SELECT t1.photourl as photoUrl, t1.review, t1.updated_at, t2.title as parkingPlace  from trips as t1 join parkinglots as t2 on t1.parkinglot_id = t2.id where t1.user_id=$userId ORDER BY t1.updated_at DESC";

                $lastInfo =  DB::select($sql);
                $result['lastParking'] = $lastInfo[0];
                $result['lastReview'] = [];
                $result['userId'] = $userId;
                $isLastReviewGet = false;
                $numberOfReviews = 0;
                for ($i=0; $i < count($lastInfo); $i++) { 
                    if (!$isLastReviewGet && !empty($lastInfo[$i]->review)) {
                        array_push($result['lastReview'], $lastInfo[$i]);
                        $isLastReviewGet = true;
                    }
                    $numberOfReviews++;
                }

                // Number of Parking & number of Reviews
                $result["numberOfReviews"] = $numberOfReviews;
                $result["numberOfParking"] = count($lastInfo);
            }

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

        $sql = "SELECT t1.id, t1.title, t1.address, t1.latitude, t1.longitude, t1.star, 3956 * 2 * ASIN(SQRT( POWER(SIN(($lat - abs(t1.latitude)) * pi()/180 / 2),2) + COS($lat * pi()/180 ) 
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

            $sql = "SELECT t1.photourl, t1.review, t1.updated_at, t2.id, t2.name, t2.email, t2.uber_credential as profileId FROM trips as t1 join uberusers as t2 on t1.user_id = t2.id where t1.parkinglot_id = $parkinglot->id";

            $reviews =  DB::select($sql);

            $data['reviews'] = [];
            $numberOfPhotos = 0;
            foreach ($reviews as $reviewkey => $reviewvalue) {
                array_push($data['reviews'], $reviewvalue);
            }

            $prices = json_decode($response->getBody())->prices;
            foreach ($prices as $price) {
                if ($price->localized_display_name == "uberX") {
                    $data['estimate'] = $price->estimate;
                    $data['duration'] = $price->duration;
                    $data['distance'] = $price->distance;
                    break;
                }
            }

            array_push($result["data"], $data);
        }

        return json_encode($result);
    }

    public function savereview(Request $request)
    {
        $token = $request->input("token");
        $parkinglot_id = $request->input("parkinglot_id");
        $review = $request->input("review");
        $star = $request->input("star");


        $users = DB::table('uberusers')
                    ->where('uber_credential', $token)
                    ->first();
        $result = [];
        $file = $request ->file('image');
        if (!isset($users->id) || $file->getClientOriginalName() == "") {
            $result['status'] = "Fail";
            $result['token'] = $token; 
        } else {
            try {
              $destinationPath = 'uploads';
              $filename = $parkinglot_id . '_' . $users->id . '_' . $file->getClientOriginalName();
              $file->move($destinationPath,$filename);

              $trip = new Trip;
              $trip->parkinglot_id = $parkinglot_id;
              $trip->user_id = $users->id;
              $trip->star = $star;
              $trip->review = $review;
              $trip->photourl = $filename;
              $trip->save();
             
              $result['status'] = "Ok";
            } catch(\Exception $e) {
                Log::info($e);
                $result['status'] = "error";
                $result['message'] = $e;
            }
        }
      
        return $result;
    }

    public function savecomment(Request $request)
    {
        $parkinglot_id = $request->input("parkinglot_id");
        $user_id = $request->input("user_id");
        $review_id = $request->input("review_id");
    }
}
