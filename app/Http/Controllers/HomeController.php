<?php

namespace App\Http\Controllers;

use App\Parkinglot;
use Validator;
use Illuminate\Http\Request;
use App\Http\Requests\ParkinglotRequest;

use App\Role;
use App\Permission;
use App\User;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /*
     *  Create role
     */
    public function createRole() 
    {
        $modifyBakend = new Permission();
        $modifyBakend->name         = 'modify-backend';
        $modifyBakend->display_name = 'Modify Homepage'; // optional
        // Allow a user to...
        $modifyBakend->description  = 'Modify Homepage'; // optional
        $modifyBakend->save();

        $superadmin = new Role();
        $superadmin->name         = 'superadmin';
        $superadmin->display_name = 'Super Administrator'; // optional
        $superadmin->description  = 'User is allowed to manage and edit other users'; // optional
        $superadmin->save();

        $superadmin->attachPermission($modifyBakend);

        $admin = new Role();
        $admin->name         = 'admin';
        $admin->display_name = 'Homepage Administrator'; // optional
        $admin->description  = 'User is allowed to manage and edit homepage'; // optional
        $admin->save();

        $admin->attachPermission($modifyBakend);

        return "success";
    }

    public function attachRole(){
        
    }


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
/*$admin = new Role();
$admin->name         = 'admin';
$admin->display_name = 'User Administrator'; // optional
$admin->description  = 'User is allowed to manage and edit other users'; // optional
$admin->save();

$user = User::where('name', '=', 'admin')->first();

// role attach alias
$user->attachRole($admin); // parameter can be an Role object, array, or id

*/

        return view('home');
    }

     public function parkingReload(Request $request){
       $locations = $request->input('locations');
       return view('parking', ['locations' => json_encode($locations)]);
     }

    public function create() {
        $parkinglot = Parkinglot::all();
         return View('home', ['locations' => $parkinglot]);
    }

    public function store(ParkinglotRequest $request) {
     /*    $validator = Validator::make($request->all(), [
            'title' => 'required',
            'body' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect('home/create')
                        ->withErrors($validator)
                        ->withInput();
        }*/
      /*  $this->validate($request, [
            'title' => 'required|min:3',
            'type' => 'required|min:10'
        ]);*/

        // if ($request->ajax()) return;

          $parkinglot = new Parkinglot;
          $parkinglot->title = $request->get('title');
          $parkinglot->type = $request->get('type');
          $parkinglot->address = $request->get('address');
          $parkinglot->city = $request->get('city');
          $parkinglot->state = $request->get('state');
          $parkinglot->zipcode = $request->get('zipcode');
          $parkinglot->country = $request->get('country');
          $parkinglot->star = 0;
          $parkinglot->latitude = $request->get('latitude');
          $parkinglot->longitude = $request->get('longitude');
          $parkinglot->save();
        
        return $parkinglot;//redirect('home');
    } 
}
