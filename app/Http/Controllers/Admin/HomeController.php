<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Actor;
use App\Models\Genre;
use App\Models\Movie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        $popularMovies = Movie::where('type',null)
            ->limit(5)
            ->orderBy('vote','desc')
            ->get();

        $upComingMovies = Movie::where('type','upcoming')
            ->limit(5)
            ->orderBy('vote','desc')
            ->get();

        $nowPlayingMovies = Movie::where('type','now_playing')
            ->limit(5)
            ->orderBy('vote','desc')
            ->get();

        return view('admin.home',compact('popularMovies','nowPlayingMovies','upComingMovies'));
    }// end of index

    public function topStatistic(){
        $countGenres = Genre::count();
        $countActors = Actor::count();
        $countMovies = Movie::count();
        return response()->json([
            'countGenres' => $countGenres,
            'countMovies' => $countMovies,
            'countActors' => $countActors
        ]);
    }

    public function moviesChart(){

        $movies = Movie::whereYear('release_date',request()->year)
            ->select('*',
                DB::raw('YEAR(release_date) as year'),
                DB::raw('MONTH(release_date) as month'),
                DB::raw('COUNT(id) as total_movies')
            )
            ->groupBy('month')
            ->get();
        return view('admin._movies_chart',compact('movies'));
    }
}//end of controller
