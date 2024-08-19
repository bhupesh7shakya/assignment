<?php

namespace App\Http\Controllers\Shared;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

abstract class SharedController extends Controller
{

    public $title;
    public $class_instance;
    public $route_name;
    public $view_path = "shared_view";
    public $rules;
    public $table_headers;
    public $columns;
    public $form;
    public $relation;
    public function index(Request $request)
    {
        // return     $data = $this->class_instance::all();

        // return $this->currentFiscalYear();
        if (Gate::denies('viewAny', $this->class_instance)) {
            abort(403, "You do not have permssion for this action");
        }
        // return  $data = $this->class_instance::getAllRecord();

        if ($request->ajax()) {

            if (isset($this->relation)) {
                $data = $this->class_instance::with($this->relation)->get();
            } else {

                $data = $this->class_instance::getAllRecord();
            }
            // dd($data);

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {

                    $delete_button = '
                <button  type="button" class="btn btn-danger tabler-card" disabled ><i class="ti ti-trash"></i></i></button>';
                    if (!Gate::denies('delete', $this->class_instance)) {
                        $delete_button = '
                                   <button onclick="del(' . $row['id'] . ')" type="button" class="btn btn-danger" ><i class="ti ti-trash"></i></i></button>

                        ';
                    }
                    $edit_button = '
                     <a href="#" disabled>
                                     <button  type="button" class="btn btn-primary" disabled  ><i class="ti ti-edit"></i></button>
                                </a>

                ';
                    if (!Gate::denies('update', $this->class_instance)) {

                        $edit_button = '
                             <a href="' . route("{$this->route_name}.edit", "{$row['id']}") . '" >
                                     <button  type="button" class="btn btn-primary"  ><i class="ti ti-edit"></i></button>
                                </a>';
                    }
                    return '<div class="text-center">
                                ' . $edit_button . '
                                <!-- <a href="' . route("{$this->route_name}.show", "{$row['id']}") . '">
                                    <button  type="button" class="btn btn-primary" ><i class="fa fa-eye"></i></button>
                                 </a>-->
            ' . $delete_button . '
                            </div>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        $data['title'] = $this->title;
        $data['route_name'] = $this->route_name;
        $data['table_headers'] = $this->table_headers;
        $data['columns'] = $this->columns;
        $data['model'] = $this->class_instance;
        return view($this->view_path . '.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (Gate::denies('create', $this->class_instance)) {
            abort(403, "You do not have permssion for this action");
        }
        $data['route_name'] = $this->route_name;
        $data['title'] = $this->title;
        $data['model'] = $this->class_instance;

        /*
            createForm() :- createes a form araay and set to $form variable of class
        */
        $this->createForm();
        /*
            $data['form'] :- this variable gets the array of form with various fields
            which will be use in the shared view of create as well as edit form
            in which array will be pass to the form compnenet params in the view
        */
        $data['form'] = $this->form;
        return view($this->view_path . '.create', compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (Gate::denies('create', $this->class_instance)) {
            abort(403, "You do not have permssion for this action");
        }
        $validator = Validator::make($request->all(), $this->rules);
        // dd($validator->validated());
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        if ($this->class_instance::insertRaw($request->all())) {
            session()->flash("success", "Data inserted successfully");
            return redirect()->route("{$this->route_name}.index");
        } else {
            session()->flash("error", "Server Error Code 500");
            return redirect()->route("{$this->route_name}.index");
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (Gate::denies('view_any', $this->class_instance)) {
            abort(403, "You do not have permssion for this action");
        }
        $data['view_only'] = true;
        $data['route_name'] = '#';
        $data['title'] = $this->title;
        $data['data'] = $this->class_instance::getById($id);
        $data['model'] = $this->class_instance;

        /*
                createForm() :- createes a form araay and set to $form variable of class
            */

        // $this->createForm();
        $this->createForm($data['data'], 'put', 'update');
        $data['form'] = $this->form;
        /*
                $data['form'] :- this variable gets the array of form with various fields
                which will be use in the shared view of create as well as edit form
                in which array will be pass to the form compnenet params in the view
            */
        return view($this->view_path . '.show', compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (Gate::denies('update', $this->class_instance)) {
            abort(403, "You do not have permssion for this action");
        }
        $data['route_name'] = $this->route_name;
        $data['title'] = $this->title;
        $data['data'] = $this->class_instance::getById($id);
        $data['model'] = $this->class_instance;

        /*
            createForm() :- createes a form araay and set to $form variable of class
        */

        // $this->createForm();
        $this->createForm($data['data'], 'put', 'update');
        $data['form'] = $this->form;
        /*
            $data['form'] :- this variable gets the array of form with various fields
            which will be use in the shared view of create as well as edit form
            in which array will be pass to the form compnenet params in the view
        */
        return view($this->view_path . '.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (Gate::denies('update', $this->class_instance)) {
            abort(403, "You do not have permssion for this action");
        }
        $rules = $this->rules;
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
        // } // foreach ($rules as $key => $rule) {
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
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            // dd($validator->errors());
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        // return $validator->validated();
        $data = $this->class_instance::updateRaw($id, $validator->validated());
        if ($data) {
            session()->flash("success", "Data updated successfully");
            return redirect()->route("{$this->route_name}.index");
        }
        else{
            session()->flash("error", " something went wrong");
            return redirect()->route("{$this->route_name}.index");
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (Gate::denies('delete', $this->class_instance)) {
            abort(403, "You do not have permssion for this action");
        }
        // dd($id);
        $instance = $this->class_instance::deleteById($id);

        return ($instance) ?
            response()->json(
                [
                    'status' => 200,
                    'message' => 'Data has been Deleted Successfully.',
                    'data' => $instance
                ]
            ) : response()->json(
                [
                    'status' => 500,
                    'message' => 'Server Error',
                ]
            );
    }
    public function upload($file)
    {
        $file_name = time() . time() . uniqid() . $file->getClientOriginalName();
        Storage::disk('local')->putFileAs('public/images/', $file, $file_name);
        return $file_name;
    }
    abstract  public function createForm();
}
