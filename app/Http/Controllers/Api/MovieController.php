<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ActorResource;
use App\Http\Resources\ImageResource;
use App\Http\Resources\MovieResource;
use App\Models\Movie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MovieController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $movies = Movie::WhenType(request()->type)
            ->WhenSearch(request()->search)
            ->paginate(10);

        //to make paginate for api
        $data['movies'] = MovieResource::collection($movies)->response()->getData(true);
        
        return response()->api($data);
    }

    public function favoriteMovie(){
        Auth::user()->favoriteByMovies()->toggle([\request()->movieId]);
        return response()->api(null,0,'added movies to favorite');
    }

    public function getImages(Movie $movie){
        return response()->api(ImageResource::collection($movie->images));
    }

    public function getActors(Movie $movie){
        return response()->api(ActorResource::collection($movie->actors));
    }

    public function relatedMovie(Movie $movie){
        $movies = Movie::whereHas('genres',function ($q) use ($movie){
           return $q->whereIn('name',$movie->genres()->pluck('name'));
        })
            ->where('id','!=',$movie->id)
            ->get();
        return response()->api(MovieResource::collection($movies));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
