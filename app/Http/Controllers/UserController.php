<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Shared\SharedController;
use App\Models\Artist;
use App\Models\User;
use Illuminate\Auth\Events\Validated;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends SharedController
{
    function login_index(Request $request)
    {
        return view('admin.authentication.login');
    }

    function register_index(Request $request)
    {
        return view('admin.authentication.create');
    }

    function register(Request $request, $to = "")
    {
        $validator = Validator::make(
            $request->all(),
            [
                "name" => ['required_if:role,artist',],
                "first_name" => ["required",],
                "last_name" => ["required",],
                "email" => ['required', 'email', 'unique:users,email'],
                "password" => ['required', 'min:6', 'max:255'],
                "gender" => ['required'],
                "address" => ['required', 'min:3', 'max:255'],
                "dob" => ['required', 'date'],
                "phone" => ['required', 'regex:/^(98|97)\d{8}$/', 'unique:users,phone'],
                "first_release_year" => ['required_if:role,artist',]
            ]
        );


        if ($validator->fails()) {
            // dd($validator->errors());

            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        $validated_data = $validator->validated();
        if ($request->role != null) {
            // dd($request->role);
            $validated_data['role'] = $request->role;
        } else {
            $validated_data['role'] = "artist_manager";
        }

        $validated_data['password'] = Hash::make($validated_data['password']);
        $user = User::create($validated_data);
        $validated_data['user_id']=$user->id;
        if ($user->role == "artist") {
            Artist::insertRaw($validated_data);
        }
        session()->flash("success", "Registration Success! Now You can Login ");
        if ($to != "") {
            return redirect($to);
        }
        return redirect('dashboard');
        // return view('');
    }

    public function login(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'email' => ['required', 'email'],
                'password' => ['required', 'min:6']
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
            return redirect()->intended('dashboard');
        }

        // If authentication fails, redirect back with an error message
        return redirect()->back()
            ->withErrors(['email' => 'These credentials do not match our records.'])
            ->withInput();
    }

    function store(Request $request)
    {
        // dd($request->all());
        return $this->register($request, "users");
    }

    public $title = "User Management";
    public $class_instance = User::class;
    public $route_name = "users";
    public $rules =
        [
            "name" => ['required_if:role,artist',],
            "first_name" => ["required",],
            "last_name" => ["required",],
            "email" => ['required', 'email', 'unique:users,email'],
            "password" => ['required', 'min:6', 'max:255'],
            "gender" => ['required'],
            "address" => ['required', 'min:3', 'max:255'],
            "dob" => ['required', 'date'],
            "phone" => ['required', 'unique:users,phone'],
            "role" => ["required"],
            "first_release_year" => ['required_if:role,artist',]


    ];
    public $table_headers = ["full name", "role", "email", "address", "dob", "phone"];
    public $columns = ["name", "role", "email", "address", "dob", "phone"];


    public function update(Request $request, $id)
    {
        if (Gate::denies('update', $this->class_instance)) {
            abort(403, "You do not have permssion for this action");
        }
        // return $this->rules
        $rules = $this->rules;
        // foreach ($rules as $key => $rule) {
        //     // dd($rule);
        //     foreach ($rule as $index => $r) {
        //         // foreach($r as $b){
        //         //     dd($b)
        //         // }
        //         try {
        //             $find_unique_key = explode(":", $r)[0];
        //             if ($find_unique_key == "unique") {
        //                 $rules[$key][$index] = $rules[$key][$index] . "," . $id;
        //             }
        //         } catch (\Throwable $th) {
        //             throw $th;
        //         }
        //     }
        // }

        // foreach ($rules as $key => $rule) {
        //     // dd($rule);
        //     foreach ($rule as $index => $r) {
        //         try {
        //             $find_unique_key = explode(":", $r)[0];
        //             if ($find_unique_key == "unique") {
        //                 $rules[$key][$index] = $rules[$key][$index] . "," . $id;
        //             }
        //         } catch (\Throwable $th) {
        //             throw $th;
        //         }
        //     }
        // }


        // dd($rules[0]['email']);
        $rules =
            [
                "name" => ['required_if:role,artist',],
                "first_name" => ["required",],
                "last_name" => ["required",],
                "email" => ['required', 'email', 'unique:users,email,'.$id],
                "password" => ['required', 'min:6', 'max:255'],
                "gender" => ['required'],
                "address" => ['required', 'min:3', 'max:255'],
                "dob" => ['required', 'date'],
                "phone" => ['required', 'unique:users,phone,'.$id],
                "role" => ["required"],
                "first_release_year" => ['required_if:role,artist',]

            ];
        // dd($rules);
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            // dd($validator->errors());
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        $validated = $validator->validated();
        if ($validated['role']=='artist' && User::find($id)->role!="artist") {
            session()->flash("success", "You can't change to role artist! Recommened to REcreate instead");
            return redirect()->route("{$this->route_name}.index");
        }
        $validated['password'] = bcrypt($validated['password']);
        // dd($validated);
        // return $validator->validated();
        $data = $this->class_instance::updateRaw($id, $validated);
        if ($data) {
            session()->flash("success", "Data updated successfully");
            return redirect()->route("{$this->route_name}.index");
        } else {
            session()->flash("error", " something went wrong");
            return redirect()->route("{$this->route_name}.index");
        }
    }

    public function createForm($data = null, $method = 'post', $action = 'store')
    {
        // dd($data);
        $this->form = [
            'route' => route($this->route_name . '.' . $action, (isset($data->id) ? $data->id : null)),
            'method' => $method,
            'fields' =>
            [
                [
                    ['type' => 'text', 'name' => 'name', 'label' => 'Artist Name', 'value' => (isset($data->name)) ? $data->name : null, 'placeholder' => 'Aritst Name',],
                    ['type' => 'text', 'name' => 'first_name', 'label' => 'First Name', 'value' => (isset($data->first_name)) ? $data->first_name : null, 'placeholder' => 'First Name',],
                    ['type' => 'text', 'name' => 'last_name', 'label' => 'Last Name', 'value' => (isset($data->last_name)) ? $data->last_name : null, 'placeholder' => 'Last name',],
                ],
                [
                    ['type' => 'text', 'name' => 'email', 'label' => 'Email', 'value' => (isset($data->email)) ? $data->email : null, 'placeholder' => 'Email',],
                    ['type' => 'password', 'name' => 'password', 'label' => 'Password', 'value' => (isset($data->password)) ? null : null, 'placeholder' => 'Password',],
                ],
                [
                    ['type' => 'text', 'name' => 'address', 'label' => 'Address', 'value' => (isset($data->address)) ? $data->address : null, 'placeholder' => 'Address',],
                    ['type' => 'date', 'name' => 'dob', 'label' => 'DOB', 'value' => (isset($data->dob)) ? $data->dob : null, 'placeholder' => 'DOB',],

                ],

                [
                    ['type' => 'number', 'name' => 'phone', 'label' => 'Phone', 'value' => (isset($data->phone)) ? $data->phone : null, 'placeholder' => 'phone',],
                    ['options' => [
                        'm' => 'male',
                        'f' => 'female',
                        'o' => 'other',
                    ], 'name' => 'gender', 'label' => 'gender', 'value' => (isset($data->gender)) ? $data->gender : null,],
                ],
                [
                    ['options' => [
                        'super_admin' => 'Super Admin',
                        'artist' => 'Artist',
                        'artist_manager' => 'Artist Manager',
                    ], 'name' => 'role', 'label' => 'Role', 'value' => (isset($data->role)) ? $data->role : null,],
                    ['type' => 'date', 'name' => 'first_release_year', 'label' => 'First Release Year', 'value' => (isset($data->first_release_year)) ? $data->first_release_year : null, 'placeholder' => 'First Release Year',],

                ]
            ]
        ];
    }
}
