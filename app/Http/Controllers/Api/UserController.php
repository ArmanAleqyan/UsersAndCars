<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class UserController extends Controller
{

    /**
     * @OA\Post(
     ** path="/api/register",
     *   tags={"Auth"},
     *   summary="register",
     *   operationId="register",
     *
     *  @OA\Parameter(
     *      name="name",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *  @OA\Parameter(
     *      name="email",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="password",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *      @OA\Parameter(
     *      name="password_confirmation",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Response(
     *      response=200,
     *       description="User Created",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=422,
     *       description="Validation Error"
     *   ),
     *)
     **/
    public function register(RegisterRequest $request){
            $createUser = User::create([
               'email' => $request->email,
               'name' => $request->name,
               'password' =>  Hash::make($request->password),
            ]);

            return response()->json([
               'status' => true,
               'message' => 'User Created',
                'routing' => 'You can now request a login link'
            ], 200);
    }


    /**
     * @OA\Post(
     ** path="/api/login",
     *   tags={"Auth"},
     *   summary="login",
     *   operationId="login",
     *
     *  @OA\Parameter(
     *      name="email",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="password",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Response(
     *      response=200,
     *       description="User Login end",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=422,
     *       description="Validation Error"
     *   ),
     *   @OA\Response(
     *      response=423,
     *       description="Wrong Email or password"
     *   ),
     *)
     **/

    public function login(Request $request){
        $rules=array(
            'email' => 'required',
            'password' => 'required|max:254',
        );
        $validator=Validator::make($request->all(),$rules);
        if($validator->fails())
        {
            return $validator->errors();
        }

        $data = $request->only('email','password');

        $login = Auth::attempt($data);

        if($login == false){
            return response()->json([
               'status' => false,
               'message' => 'Wrong Email Or Password',
            ],423);
        }else{
            $user =auth()->user();
            $token = $user->createToken('Passport Token')->accessToken;
            return response()->json([
                'status' => true,
                'user' => $user,
                'message' => 'User Login end',
                'token' => $token
            ],200);
        }


    }

    /**
     * @OA\Post(
     ** path="/api/logout",
     *   tags={"Auth"},
     *   summary="logout",
     *   operationId="logout",
     **   security={
     *   },
     *   @OA\Response(
     *      response=200,
     *       description="User Logout end",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),

     *)
     **/

    public function logout(){

   \auth()->user()->token()->revoke();

        return response()->json([
            'status' => true,
            'message' => 'User Logout end',

        ],200);


    }
}
