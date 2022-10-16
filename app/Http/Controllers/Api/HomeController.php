<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use http\Env\Response;
use Illuminate\Http\Request;

class HomeController extends Controller
{

    /**
     * @OA\Get(
     ** path="/api/AllUsers",
     *   tags={"Users"},
     *        *   security={{"bearerAuth":{}}},
     *   summary="AllUsers",
     *   operationId="AllUsers",
     *
     *   @OA\Response(
     *      response=200,
     *       description="All USers data",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *)
     **/
    public function AllUsers(){
        $user = User::OrderBy('id', 'Desc')->paginate(10);
        return response()->json([
        'status' => true,
        'All_users' => $user
        ],200);
    }


    /**
     * @OA\Get(
     ** path="/api/SinglePageUser",
     *   tags={"Users"},
     *   summary="SinglePageUser",
     *   operationId="SinglePageUser",
     *
     *  @OA\Parameter(
     *      name="user_id",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Response(
     *      response=200,
     *       description="User Data And relation Car",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *)
     **/

    public function SinglePageUser($id){

        $user = User::with('UserCar', 'UserCar.CarPhoto')->where('id', $id)->get();

        if($user->isEMpty()){
            return \response()->json([
               'status' => false,
               'message' => 'wrong user_id'
            ],422);
        }

        return response()->json([
            'status' => true,
            'user' => $user
        ],200);

    }
}
