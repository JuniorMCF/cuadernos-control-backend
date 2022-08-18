<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Enterprise;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    //
    public function add(Request $request){


        $validator = Validator::make($request->all(),[
            'name' => 'sometimes|required|string',

            'price' => 'sometimes|required|numeric|between:0,9999.99',
            'user_id'=>'sometimes|required|string',
        ]);



        if ($validator->fails()) {
            return response()->json(
                [
                    "data" => null,
                    "success" => false,
                    "message" => $validator->errors()
                ]
            );
        }

        $enterprise = Enterprise::where("user_id", $request->user_id)->get()->first();

        $product = Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' =>  $request->price,
            'enterprise_id'=>$enterprise->id
        ]);

        return response()->json(
            [
                "data" => $product,
                "success" => true,
                "message" => "Product created succesfully"
            ]
        );

    }
    public function edit(Request $request){
        $validator = Validator::make($request->all(),[
            'id'=>'sometimes|required|string',
            'name' => 'required|string',

            'price' => 'sometimes|required|numeric|between:0,9999.99',
        ]);

        if ($validator->fails()) {
            return response()->json(
                [
                    "data" => null,
                    "success" => false,
                    "message" => $validator->errors()
                ]
            );
        }


        $product = Product::find($request->id)->update([
            'name' => $request->name,
            'description' => $request->description,
            'price' =>  $request->price
        ]);

        return response()->json(
            [
                "data" => $product,
                "success" => true,
                "message" => "Product updated succesfully"
            ]
        );
    }

    public function delete(Request $request){
        $validator = Validator::make($request->all(),[
            'id' => 'sometimes|required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(
                [
                    "data" => null,
                    "success" => false,
                    "message" => $validator->errors()
                ]
            );
        }


        $product = Product::find($request->id)->delete();
        return response()->json(
            [
                "data" => $product,
                "success" => true,
                "message" => "Product deleted succesfully"
            ]
        );
    }
    public function show(Request $request){
        $validator = Validator::make($request->all(),[
            'id' => 'sometimes|required|string',
            'user_id'=>'sometimes|required|string',
        ]);



        if ($validator->fails()) {
            return response()->json(
                [
                    "data" => null,
                    "success" => false,
                    "message" => $validator->errors()
                ]
            );
        }

        $enterprise = Enterprise::where("user_id", $request->user_id)->get()->first();
        $product = Product::find($request->id);

        if($enterprise->id != $product->enterprise_id){
            return response()->json(
                [
                    "data" => null,
                    "success" => false,
                    "message" => "User unauthorized"
                ]
            );
        }


        return response()->json(
            [
                "data" =>$product,
                "success" => true,
                "message" => "Product exist"
            ]
        );
    }
}
