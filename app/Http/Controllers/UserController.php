<?php


namespace App\Http\Controllers;


use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

    /**
     * Register api
     *
     * @return Response
     */
    public function register(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'email' => 'required|email|unique:users',
                'password' => 'required',
                'password_confirmation' => 'required|same:password',
                'is_active'=> 'sometimes|boolean',
            ]);
            if ($validator->fails()) {
                return response()->json(['message' => $validator->errors()], 400);
            }

            $input = $request->all();
            $input['password'] = Hash::make($input['password']);

            try {
                $user = User::create($input);
            } catch (Exception $exception) {
                return response()->json(['message' => $exception->getCode() . ' - ' . $exception->getMessage()], 400);
            }

            $accessToken = $user->createToken('rateUser')->accessToken;
            return response()->json(['user' => $user, 'access_token' => $accessToken], 201);
        }catch (Exception $ex){
            return response()->json(['message' => $ex->getMessage()], 400);
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
      $users = User::all();
        if (count($users) != 0) {
            return response()->json(['users' => $users], 200);
        }
        return response()->json(['message' => 'No users found'], 404);
    }

    /**
     * details api
     *
     * @return Response
     */
    public function getUserDetails()
    {
        try {
            $user = Auth::user();
            return response()->json(['user' => $user], 200);
        }catch (Exception $ex){
            return response()->json(['error' => $ex->getMessage()], 400);
        }
    }
}
