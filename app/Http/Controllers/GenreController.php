<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Shared\SharedController;
use App\Models\Genre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

class GenreController extends SharedController
{
    public $title ="Genre";
    public $class_instance=Genre::class;
    public $route_name="genres";
    public $rules=[
        "name"=>["required","unique:genres,name","max:20",]
    ];
    public $table_headers=["name"];
    public $columns=["name"];

    public function update(Request $request, $id)
    {
        if (Gate::denies('update', $this->class_instance)) {
            abort(403, "You do not have permssion for this action");
        }
        $rules = $this->rules;
        $rules['name'][1]=$rules['name'][1].",$id";
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
        } else {
            session()->flash("error", " something went wrong");
            return redirect()->route("{$this->route_name}.index");
        }
    }


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
