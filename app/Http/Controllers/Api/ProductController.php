<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\CarPhoto;
use Illuminate\Http\Request;
use Validator;

class ProductController extends Controller
{

    /**
     * @OA\Post(
     ** path="/api/CreateCar",
     *   tags={"Car"},
     *   summary="CreateCar",
     *   operationId="CreateCar",
     *
     *  @OA\Parameter(
     *      name="model",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="date",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string",
     *   format ="date",
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="price",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="photo[ ]",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="file"
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
     *
     *   @OA\Response(
     *      response=424,
     *       description="You have a car"
     *   ),
     *)
     **/


    public function CreateCar(Request $request){
        $rules=array(
            'model' => 'required|max:254',
            'date' => 'required|max:254|date',
            'price' => 'required|max:254',
            'photo' => 'required',
        );
        $validator=Validator::make($request->all(),$rules);
        if($validator->fails())
        {
            return $validator->errors();
        }
        if(auth()->user()->UserCar->count() > 1){
            return response()->json([
                'status' => false,
                'message' => 'You have a car',
            ],424);
        }
       $crete =  Car::create([
           'user_id'=> auth()->user()->id,
           'model' => $request->model,
           'date' => $request->date,
           'price' => $request->price
        ]);

       $photo = $request->photo;

       $time = time();
       foreach ($photo as $photos){
           $filename= $time++.$photos->getClientOriginalName();
           $photos-> StoreAs(('CarPhoto'), $filename);

           CarPhoto::create([
              'car_id'  => $crete->id,
               'photo' => $filename
           ]);
       }
       $data =  Car::with('UserCar', 'CarPhoto')->where('id' , $crete->id)->get();
       return response()->json([
          'status' => true,
          'message' => 'Car Created',
          'data' => $data,
       ]);
    }

    /**
     * @OA\Get(
     ** path="/api/DeleteCar",
     *   tags={"Car"},
     *      *   security={{"bearerAuth":{}}},
     *   summary="DeleteCar",
     *   operationId="DeleteCar",
     *
     *  @OA\Parameter(
     *      name="car_id",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Response(
     *      response=200,
     *       description="Car Deleted",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *)
     **/

    public function DeleteCar($id){
        $get = Car::where('id', $id)->get();
        if($get->isEMpty()){
                 return      response()->json([
                  'status' => false,
                  'message' => 'Wrong Car Id'
                 ], 422);
        }else{
            Car::where('id', $id)->delete();
            return      response()->json([
                'status' => true,
                'message' => 'Car Deleted'
            ], 200);
        }
    }
}
