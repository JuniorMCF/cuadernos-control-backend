<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Enterprise;
use App\Models\Product;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SaleController extends Controller
{
    //
    public function add(Request $request){


        $validator = Validator::make($request->all(),[
            "user_id" => 'required|string|nullable',
            "client_id" => 'sometimes',
            "first_name"  => 'sometimes',
            "dni"  => 'required|string|nullable',
            "product_id"  => 'required|string|nullable',
            "quantity"  => 'required',
            "status" => 'required|string|nullable',
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

        if($request->client_id == null || $request->client_id == ''){

            $client = new Client();
            $client->first_name = $request->first_name;
            $client->dni = $request->dni;
            $client->enterprise_id = $enterprise->id;
            $client->save();


            $product = Product::find($request->product_id);

            $price_actual = $product->is_offer == 1 ? $product->price_offer : $product->price;

            $discount = $product->is_offer == 1 ? $product->price - $price_actual : 0 ;


            $new_sid = Sale::where('enterprise_id',$enterprise->id)->max('sid') + 1;

            $sale = Sale::create([
                "client_id"=>$client->id,
                "client_name"=>$client->first_name,
                "product_id"=>$product->id,
                "product_name"=>$product->name,
                'enterprise_id'=>$enterprise->id,
                'price_actual'=>$price_actual,
                'quantity'=>$request->quantity,
                'discount'=>$discount,
                'registered_amount'=>$request->amount,
                "sid"=>$new_sid,
                'status'=>$request->status,
                'enterprise_id'=>$enterprise->id
            ]);

            return response()->json(
                [
                    "data" => $sale,
                    "success" => true,
                    "message" => "Sale created succesfully"
                ]
            );
        }



        $product = Product::find($request->product_id);

        $client = Client::find($request->client_id);

        $price_actual = $product->is_offer == 1 ? $product->price_offer : $product->price;

        $discount = $product->is_offer == 1 ? $product->price - $price_actual : 0 ;

        $new_sid = Sale::where('enterprise_id',$enterprise->id)->max('sid') + 1;

        $sale = Sale::create([
            "client_id"=>$client->id,
            "client_name"=>$client->first_name,
            "product_id"=>$product->id,
            "product_name"=>$product->name,
            'enterprise_id'=>$enterprise->id,
            'price_actual'=>$price_actual,
            'quantity'=>$request->quantity,
            'discount'=>$discount,
            'registered_amount'=>$request->amount,
            'sid'=>$new_sid,
            'status'=>$request->status,
            'enterprise_id'=>$enterprise->id
        ]);

        return response()->json(
            [
                "data" => $sale,
                "success" => true,
                "message" => "Sale created succesfully"
            ]
        );

    }
    public function edit(Request $request){
        $validator = Validator::make($request->all(),[
            'id'=>'required|string|nullable',
            'registered_amount'=>'sometimes',
            'status'=>'sometimes',

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

        if($request->registered_amount != ''){
            $sale = Sale::find($request->id)->update([
                'registered_amount'=>$request->registered_amount
            ]);
            return response()->json(
                [
                    "data" => $sale,
                    "success" => true,
                    "message" => "Sale updated succesfully"
                ]
            );
        }
        if($request->status != ''){
            $sale = Sale::find($request->id)->update([
                'status'=>$request->status
            ]);
            return response()->json(
                [
                    "data" => $sale,
                    "success" => true,
                    "message" => "Sale updated succesfully"
                ]
            );
        }

        return response()->json(
            [
                "data" => null,
                "success" => false,
                "message" => "No puede enviar nulos"
            ]
        );

    }

    public function delete(Request $request){
        $validator = Validator::make($request->all(),[
            'id' => 'required|string|nullable',
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


        $sale = Sale::find($request->id)->delete();
        return response()->json(
            [
                "data" => $sale,
                "success" => true,
                "message" => "Service deleted succesfully"
            ]
        );
    }
    public function show(Request $request){
        $validator = Validator::make($request->all(),[
            'id' => 'required|string|nullable',
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
        $sale = Client::find($request->id);

        if($enterprise->id != $sale->enterprise_id){
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
                "data" =>$sale,
                "success" => true,
                "message" => "Service exist"
            ]
        );
    }
}
