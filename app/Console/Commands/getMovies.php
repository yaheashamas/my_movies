<?php

namespace App\Console\Commands;

use App\Models\Actor;
use App\Models\Genre;
use App\Models\Image;
use App\Models\Movie;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use PhpParser\Node\Stmt\DeclareDeclare;

class getMovies extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get:movies';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'get all movies from TMDB';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->getPopularMovies();
        $this->getNowPlayingMovies();
        $this->getupcomingMovies();
    }

    private function getPopularMovies(){

        for ($i = 1 ; $i <= config('services.tmdb.max_pages') ; $i++){
            $response = Http::get(config('services.tmdb.base_url').'movie/popular?region=us&api_key='.config('services.tmdb.api_key').'&page='.$i);
            foreach ($response->json()['results'] as $result){

                $movie = Movie::updateOrCreate(
                    [
                        'e_id' => $result['id'],
                        'title' => $result['title'],
                    ],
                    [
                        'type' => null,
                        'description' => $result['overview'],
                        'poster' => $result['poster_path'],
                        'banner' => $result['backdrop_path'],
                        'release_date' => $result['release_date'],
                        'vote' => $result['vote_average'],
                        'vote_count' => $result['vote_count']
                    ]
                );
                $this->attachGenre($result,$movie);
                $this->attachActores($movie);
                $this->getImages($movie);

            }//end of foreach movies

        }//end of loop

    }//end of getPopularMovies

    private function getNowPlayingMovies(){

        for ($i = 1 ; $i <= config('services.tmdb.max_pages') ; $i++){
            $response = Http::get(config('services.tmdb.base_url').'movie/now_playing?region=us&api_key='.config('services.tmdb.api_key').'&page='.$i);
            foreach ($response->json()['results'] as $result){

                $movie =  $movie = Movie::updateOrCreate(
                    [
                        'e_id' => $result['id'],
                        'title' => $result['title'],
                    ],
                    [
                        'type' => 'now_playing',
                        'description' => $result['overview'],
                        'poster' => $result['poster_path'],
                        'banner' => $result['backdrop_path'],
                        'release_date' => $result['release_date'],
                        'vote' => $result['vote_average'],
                        'vote_count' => $result['vote_count']
                    ]
                );

                $this->attachGenre($result,$movie);
                $this->attachActores($movie);
                $this->getImages($movie);

            }//end of foreach movies

        }//end of loop

    }//end of nowPlayingMovies

    private function getupcomingMovies(){

        for ($i = 1 ; $i <= config('services.tmdb.max_pages') ; $i++){
            $response = Http::get(config('services.tmdb.base_url').'movie/upcoming?region=us&api_key='.config('services.tmdb.api_key').'&page='.$i);
            foreach ($response->json()['results'] as $result){

                $movie = $movie = Movie::updateOrCreate(
                    [
                        'e_id' => $result['id'],
                        'title' => $result['title'],
                    ],
                    [
                        'type' => 'upcoming',
                        'description' => $result['overview'],
                        'poster' => $result['poster_path'],
                        'banner' => $result['backdrop_path'],
                        'release_date' => $result['release_date'],
                        'vote' => $result['vote_average'],
                        'vote_count' => $result['vote_count']
                    ]
                );
                $this->attachGenre($result,$movie);
                $this->attachActores($movie);
                $this->getImages($movie);

            }//end of foreach movies

        }//end of loop

    }//end of upcomingMovies

    private function attachGenre($result,$movie){

        foreach ($result['genre_ids'] as $genre_id){
            $genre = Genre::where('e_id',$genre_id)->first();
            $movie->genres()->syncWithoutDetaching($genre->id);

        }//end of foreach genres

    }//end of attachGenres

    private function attachActores($movie){

        $response = Http::get(config('services.tmdb.base_url').'movie/'.$movie->e_id.'/credits?api_key='.config('services.tmdb.api_key'));

        foreach ($response->json()['cast'] as $index => $cast){

            //move to next loop
            if ( $cast['known_for_department'] != 'Acting') continue ;

            //break loop
            if ($index == 12) break;

            $actor = Actor::updateOrCreate(
                [
                    'e_id' => $cast['id'],
                    'name' => $cast['name'],
                ],
                [
                    'image' => $cast['profile_path'],
                ]);
            $movie->actors()->syncWithoutDetaching($actor->id);

        }//end of foreach

    }//end of getActors

    private function getImages($movie){

        $response = Http::get(config('services.tmdb.base_url').'movie/'.$movie->e_id.'/images?api_key='.config('services.tmdb.api_key'));

        //delete all data
        $movie->images()->delete();

        foreach ($response->json()['backdrops'] as $index => $img){

            //break loop
            if ($index == 12) break;

            $image = new Image;
            $image->path = $img['file_path'];

            $movie->images()->save($image);

        }//end of foreach
    }//end of getImages

}//end of get:movies
