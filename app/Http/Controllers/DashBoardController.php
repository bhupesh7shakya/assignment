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
        $data['no_of_musics']=Music::count_music();
        $data['no_of_artist']=Artist::count_artist();
        $data['no_of_genre']=Genre::count_genre();
        if ($request->ajax()) {
            $data['total_genre_musics']=Genre::getGenresWithMusicCount();
            # code...
            $data['top_five_artist']=Artist::getArtistWithMusicCount();
            return $data;
        }
        return view('admin.dashboard.index',compact("data"));
    }
}
