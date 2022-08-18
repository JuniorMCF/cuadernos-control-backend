<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Enterprise;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ServiceController extends Controller
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

        $service = Service::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' =>  $request->price,
            'enterprise_id'=>$enterprise->id
        ]);

        return response()->json(
            [
                "data" => $service,
                "success" => true,
                "message" => "Service created succesfully"
            ]
        );

    }
    public function edit(Request $request){
        $validator = Validator::make($request->all(),[
            'id'=>'sometimes|required|string',
            'name' => 'sometimes|required|string',

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


        $service = Service::find($request->id)->update($request->all());

        return response()->json(
            [
                "data" => $service,
                "success" => true,
                "message" => "Service updated succesfully"
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


        $service = Service::find($request->id)->delete();
        return response()->json(
            [
                "data" => $service,
                "success" => true,
                "message" => "Service deleted succesfully"
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
        $service = Service::find($request->id);

        if($enterprise->id != $service->enterprise_id){
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
                "data" =>$service,
                "success" => true,
                "message" => "Service exist"
            ]
        );
    }
}
