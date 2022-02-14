<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;
    protected $fillable = ['path','imageable_id','imageable_type'];
    protected $appends = ['image_path'];

    //attribute
    public function getImagePathAttribute(){
        if ($this->path){
            return "https://image.tmdb.org/t/p/w500".$this->path;
        }
    }
    //relation
    public function imageable(){
        return $this->morphTo();
    }

    //scope

    //function
}
