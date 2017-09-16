<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    public function category(){
       return $this->belongsTo('App\Category');
    }
    public function tag()
    {
        return $this->belongsToMany('App\Tag');
    }

    public function comments()
    {
        return $this->hasMany('App\Comment');
    }
    public static function timeDifference($created){
        $now =\Carbon\Carbon::now();

        if($now->diffInDays($created)>4){
            return  date('M j, Y g:ia',strtotime($created));
        }else{
            return $created->diffForHumans();
        }

    }

}
