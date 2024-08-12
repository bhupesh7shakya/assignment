<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Shared\SharedController;
use App\Models\User;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends SharedController
{
    function login_index(Request $request)  {
        return view('admin.authentication.login');
    }

    function register_index(Request $request){
        return view('admin.authentication.create');
    }

    function register(Request $request)  {
        $validator=Validator::make(
            $request->all(),
            [
                "first_name"=>["required",],
                "last_name"=>["required",],
                "email"=> ['required','email','unique:users,email'],
                "password"=> ['required','min:6','max:255','unique:users,password'],
                "gender"=> ['required'],
                "address"=> ['required','min:3','max:255'],
                "dob"=> ['required','date'],
                "phone"=> ['required']
            ]
        );


        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        $validated_data=$validator->validated();

        $validated_data['password']=Hash::make($validated_data['password']);

        $user=User::create($validated_data);
        session()->flash("success", "Registration Success! Now You can Login ");
        return redirect('login');
        // return view('');
    }

    public function login(Request $request) {
        $validator=Validator::make($request->all(),
        [
            'email'=>['required','email'],
            'password'=>['required','min:6']
            ]
        );
        if ($validator->fails()) {

            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

    // Attempt to log the user in with the provided credentials
    if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
        // If successful, redirect to the intended page or a default page
        return redirect()->intended('artists');
    }

    // If authentication fails, redirect back with an error message
    return redirect()->back()
        ->withErrors(['email' => 'These credentials do not match our records.'])
        ->withInput();
    }

    public $title ="User Management";
    public $class_instance=User::class;
    public $route_name="users";
    public $rules=[
        // todo user stuff....
        "name"=>["required","unique:genres,name","max:20",]
    ];
    public $table_headers=["name"];
    public $columns=["name"];

    public function createForm($data = null,$method='post',$action='store')
    {
        $this->form = [
            'route' => route($this->route_name . '.'.$action,(isset($data->id)?$data->id:null)),
            'method' => $method,
            'fields' =>
            [
                [
                    ['type'=>'text','name'=>'name','label'=>'Name','value'=>(isset($data->name))?$data->name:null,'placeholder'=>'Name',],
                ],
            ]
        ];

    }
}
