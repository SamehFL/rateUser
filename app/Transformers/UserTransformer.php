<?php


namespace App\Transformers;


use App\Models\User;
use App\Models\Rating;
use App\Transformers\RatingTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use League\Fractal;
use League\Fractal\Manager;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
    /**
     * @var Manager
     */
    private $fractal;

    /**
     * @var RatingTransformer
     */
    private $ratingTransformer;

    function __construct(Manager $fractal, RatingTransformer $ratingTransformer)
    {
        $this->fractal = $fractal;
        $this->ratingTransformer = $ratingTransformer;
    }
    public function transform (User $user){
        $ratings = new Fractal\Resource\Collection($user->Ratings, $this->ratingTransformer);
        $ratings = $this->fractal->createData($ratings)->toArray();
        return[
            'id'=> $user->id,
            'rating_average'=> $user->Ratings->avg('rating'),
            'name'=>$user->name,
            'is_active'=>$user->is_active,
            'ratings_history'=> $ratings['data'],
        ];
    }
}
