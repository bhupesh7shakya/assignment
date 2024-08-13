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

    function register(Request $request,$to="")  {
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
                "phone"=> ['required','regex:/^(98|97)\d{8}$/']
            ]
        );


        if ($validator->fails()) {
            // dd($validator->errors());

            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        $validated_data=$validator->validated();

        $validated_data['password']=Hash::make($validated_data['password']);

        $user=User::create($validated_data);
        session()->flash("success", "Registration Success! Now You can Login ");
        if ($to != "") {
            return redirect($to);
        }
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

    function store(Request $request) {
        return $this->register($request,"users");
    }

    public $title ="User Management";
    public $class_instance=User::class;
    public $route_name="users";
    public $rules=[
        [
            "first_name"=>["required",],
            "last_name"=>["required",],
            "email"=> ['required','email','unique:users,email'],
            "password"=> ['required','min:6','max:255','unique:users,password'],
            "gender"=> ['required'],
            "address"=> ['required','min:3','max:255'],
            "dob"=> ['required','date'],
            "phone"=> ['required','unique:users,phone'],
            "role"=>["required"]
        ]
    ];
    public $table_headers=["full name","role","email","address","dob","phone"];
    public $columns=["name","role","email","address","dob","phone"];

    public function createForm($data = null,$method='post',$action='store')
    {
        $this->form = [
            'route' => route($this->route_name . '.'.$action,(isset($data->id)?$data->id:null)),
            'method' => $method,
            'fields' =>
            [
                [
                    ['type'=>'text','name'=>'first_name','label'=>'First Name','value'=>(isset($data->first_name))?$data->first_name:null,'placeholder'=>'First Name',],
                    ['type'=>'text','name'=>'last_name','label'=>'Last Name','value'=>(isset($data->last_name))?$data->last_name:null,'placeholder'=>'Last name',],
                ],
                [
                    ['type'=>'text','name'=>'email','label'=>'Email','value'=>(isset($data->email))?$data->email:null,'placeholder'=>'Email',],
                    ['type'=>'password','name'=>'password','label'=>'Password','value'=>(isset($data->password))?$data->password:null,'placeholder'=>'Password',],
                ],
                [
                    ['type'=>'text','name'=>'address','label'=>'Address','value'=>(isset($data->address))?$data->address:null,'placeholder'=>'Address',],
                    ['type'=>'date','name'=>'dob','label'=>'DOB','value'=>(isset($data->dob))?$data->dob:null,'placeholder'=>'DOB',],

                ],

                [
                    ['type'=>'number','name'=>'phone','label'=>'Phone','value'=>(isset($data->phone))?$data->phone:null,'placeholder'=>'phone',],
                    ['options'=>[
                        'm'=>'male',
                        'f'=>'female',
                        'o'=>'other',
                    ], 'name' => 'gender', 'label' => 'gender', 'value' => (isset($data->gender)) ? $data->gender : null,],
                ],
                [
                    ['options'=>[
                        'super_admin'=>'Super Admin',
                        'artist'=>'Artist',
                        'artist_manager'=>'Artist Manager',
                    ], 'name' => 'role', 'label' => 'Role', 'value' => (isset($data->role)) ? $data->role : null,],
                    []
                ]
            ]
        ];

    }
}
