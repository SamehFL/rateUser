<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'rated_user_id', 'rating_user_id','rating','rating_comment',
    ];

    public function ratingUser(){
        return $this->belongsTo('App\Models\User','rating_user_id');
    }

}
