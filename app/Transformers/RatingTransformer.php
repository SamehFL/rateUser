<?php


namespace App\Transformers;


use League\Fractal\TransformerAbstract;
use App\Models\Rating;

class RatingTransformer extends TransformerAbstract
{
    public function transform (Rating $rating){
        return[
            'rated_user_id'=>  $rating->rated_user_id,
            'rating'=>$rating->rating,
            'rating_comment'=>$rating->rating_comment,
            'rating_date'=>$rating->created_at->format('jS F Y'),
            'rating_user' => [
                'id'=> $rating->rating_user_id,
                'name' =>$rating->ratingUser->name,
                'email' =>$rating->ratingUser->email,
                'is_active' =>(boolean) $rating->ratingUser->is_active,
            ],
        ];
    }
}
