<?php

namespace App\Http\Controllers;

use App\Models\Artist;
use App\Models\Genre;
use App\Models\Music;
use Illuminate\Http\Request;

class DashBoardController extends Controller
{

    public function index(Request $request){
        $data=[];
        $data['no_of_musics']=Music::all()->count();
        $data['no_of_artist']=Artist::all()->count();
        $data['no_of_genre']=Genre::all()->count();
        if ($request->ajax()) {
            # code...
             $data['total_genre_musics']=Genre::withCount('musics')->get();
            $data['top_five_artist']=Artist::withCount('musics')->take(5)->get();
            return $data;
        }
        return view('admin.dashboard.index',compact("data"));
    }
}
