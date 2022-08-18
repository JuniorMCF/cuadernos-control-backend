<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Enterprise;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ClientController extends Controller
{
    //
    public function add(Request $request){


        $validator = Validator::make($request->all(),[
            'first_name' => 'required|string|nullable',

            'dni'=>'required|string|nullable',
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

        $client = Client::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'description'=>$request->description,
            'contact_email' =>  $request->contact_email,
            'phone_number_one' =>  $request->phone_number_one,
            'phone_number_two' =>  $request->phone_number_two,
            'dni' =>  $request->dni,
            'enterprise_id'=>$enterprise->id
        ]);

        return response()->json(
            [
                "data" => $client,
                "success" => true,
                "message" => "Service created succesfully"
            ]
        );

    }
    public function edit(Request $request){
        $validator = Validator::make($request->all(),[
            'id'=>'required|string|nullable',
            'first_name' => 'required|string|nullable',
            'last_name'=>'sometimes|string',
            'contact_email' => 'sometimes',
            'phone_number_one' => 'sometimes',
            'phone_number_two'=>'sometimes',
            'dni'=>'required|string|nullable',

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


        $client = Client::find($request->id)->update($request->all());

        return response()->json(
            [
                "data" => $client,
                "success" => true,
                "message" => "Service updated succesfully"
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


        $client = Client::find($request->id)->delete();
        return response()->json(
            [
                "data" => $client,
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
        $client = Client::find($request->id);

        if($enterprise->id != $client->enterprise_id){
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
                "data" =>$client,
                "success" => true,
                "message" => "Service exist"
            ]
        );
    }
}
