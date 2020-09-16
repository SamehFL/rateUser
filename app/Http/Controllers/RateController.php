<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use App\Models\User;
use App\Transformers\RatingTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use League\Fractal;
use League\Fractal\Manager;

class RateController extends Controller
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

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function store(Request $request)
    {
        try {
            $this->validate($request, [
                'rated_user_id' => 'required|integer',
                'rating_user_id' => 'required|integer',
                'rating' => 'required|integer|max:10|min:1',
                'rating_comment' => 'nullable|string|max:255',
            ]);

            $user=User::where('id',$request->rated_user_id)->first();

            if (is_null($user)){
                return response()->json(['message' => 'user not found'], 404);
            }

            $rating = Rating::create($request->all());

            $avgRate = DB::table('ratings')
                ->where('rated_user_id', $request->rated_user_id)
                ->avg('rating');

            $ratings = new Fractal\Resource\Collection($user->Ratings, $this->ratingTransformer);
            $ratings = $this->fractal->createData($ratings)->toArray();

            return response()->json(['rating_average' => $avgRate,'rating_history'=>$ratings['data']], 201);
        }catch (\Exception $ex){
            if($ex->getCode()==="23000"){
                return response()->json(['message' => 'user rating already submitted before'], 400);
            }
            return response()->json(['message' => $ex->getMessage()], 400);
        }
    }
}

