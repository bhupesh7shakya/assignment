<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Shared\SharedController;
use App\Models\Genre;
use Illuminate\Http\Request;

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
