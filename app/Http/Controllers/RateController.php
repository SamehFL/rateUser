<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use App\Models\User;
use App\Transformers\RatingTransformer;
use App\Transformers\UserTransformer;
use Exception;
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

    /**
     * @var UserTransformer
     */
    private $userTransformer;

    function __construct(Manager $fractal, RatingTransformer $ratingTransformer, UserTransformer $userTransformer)
    {
        $this->fractal = $fractal;
        $this->ratingTransformer = $ratingTransformer;
        $this->userTransformer = $userTransformer;
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

            $ratings = new Fractal\Resource\Collection($user->Ratings, $this->ratingTransformer);
            $ratings = $this->fractal->createData($ratings)->toArray();

            return response()->json(['rating_average' => $user->Ratings->avg('rating'),'rating_history'=>$ratings['data']], 201);
        }catch (Exception $ex){
            if($ex->getCode()==="23000"){
                return response()->json(['message' => 'user rating already submitted before'], 400);
            }
            return response()->json(['message' => $ex->getMessage()], 400);
        }
    }

    public function index(){
        try {
            $users = User::all();

            if (is_null($users)) {
                return response()->json(['message' => 'no users found'], 404);
            }

            $usersList = new Fractal\Resource\Collection($users, $this->userTransformer);
            $usersList = $this->fractal->createData($usersList)->toArray();

            return response()->json(['users' => $usersList['data']], 200);
        }catch (Exception $ex){
            return response()->json(['message' => $ex->getMessage()], 400);
        }
    }

    public function show($rated_user_id){
        try {
            $user = User::where('id', $rated_user_id)->first();

            if (is_null($user)) {
                return response()->json(['message' => 'user not found'], 404);
            }

            if (count($user->Ratings) === 0) {
                return response()->json(['message' => 'user has no ratings'], 404);
            }

            $ratings = new Fractal\Resource\Collection($user->Ratings, $this->ratingTransformer);
            $ratings = $this->fractal->createData($ratings)->toArray();

            return response()->json(['rating_average' => $user->Ratings->avg('rating'), 'rating_history' => $ratings['data']], 200);
        }catch (Exception $ex){
            return response()->json(['message' => $ex->getMessage()], 400);
        }
    }
}

