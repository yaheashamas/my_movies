<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    use HasFactory;
    protected $fillable = ['e_id','title','description','poster','banner','release_date','vote','vote_count','type'];

    protected $appends = ['poster_path','banner_path'];

    //Attribute
    public function getPosterPathAttribute(){
        return "https://image.tmdb.org/t/p/w500". $this->poster;
    }

    public function getBannerPathAttribute(){
        return "https://image.tmdb.org/t/p/w500". $this->banner;
    }

    //scope
    public function scopeWhenGenreId($query,$genreId){
        return $query->when($genreId,function ($q) use ($genreId){
            return $q->whereHas('genres',function ($qu) use ($genreId){
                return $qu->where('genres.id',$genreId);
            });
        });
    }

    public function scopeWhenActorId($query,$actorId){
        return $query->when($actorId,function ($q) use ($actorId){
            return $q->whereHas('actors',function ($qu) use ($actorId){
                return $qu->where('actors.id',$actorId);
            });
        });
    }

    public function scopeWhenMovieId($query,$movieId){
        return $query->when($movieId,function ($q) use ($movieId){
            return $q->where('e_id',$movieId);
        });
    }

    public function scopeWhenType($query,$type){
        return $query->when($type,function ($q) use ($type){
            return $q->where('type',$type);
        });
    }

    //relation
    public function genres(){
        return $this->belongsToMany(Genre::class,'genre_movie');
    }

    public function actors(){
        return $this->belongsToMany(Actor::class,'actor_movie');
    }

    public function images(){
        return $this->morphMany(Image::class,'imageable');
    }

}
