<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

class ProfileController extends Controller
{
    //
    public function updateProfile(Request $request){
        $validator = Validator::make($request->all(),[
            'name' => 'sometimes|required|string',
            'id'=>'sometimes|required|string',
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


        $user = User::find($request->id)->update([
            "name"=>$request->name
        ]);

        return response()->json(
            [
                "data" => $user,
                "success" => true,
                "message" => "Perfil actualizado"
            ]
        );
    }

    public function changePassword(Request $request){

        $validator = Validator::make($request->all(),[
            'id'=>'sometimes|required',
            'password'=>'sometimes|required',
            'new_password' => 'string|min:6|required',
            'new_password_confirm' => 'required_with:new_password|same:new_password|min:6',
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
        $user = User::find($request->id);
        if (password_verify($request->password, $user->password)) {
            // Success
            $user->password = Hash::make($request->new_password);
            $user->save();

            return response()->json(
                [
                    "data" => $user,
                    "success" => true,
                    "message" => "Password actualizado"
                ]
            );
        }else{
            return response()->json(
                [
                    "data" => null,
                    "success" => false,
                    "message" => [
                        "errors"=>["La contraseña actual no coincida"]
                    ]
                ]
            );
        }
    }

    public function updatePhoto(Request $request){
        $user = User::find($request->id);
        $url_foto = "";
        if ($request->hasFile('file')) {
            $this->validate($request, [
                'file_upload' => 'image|mimes:jpg,jpeg,png,gif,svg|max:2048',
            ]);

            ini_set('memory_limit', '256M');

            File::delete($user->photo);

            $image_resize = Image::make($request->file->getRealPath());
            $image_resize->resize(800, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
            $image_resize->orientate();
            $nombre_archivo = time() . "." . $request->file->extension();
            /**
             * codigo en produccion php 7.3
             *
             */

            if (!file_exists(public_path('app'))) {
                mkdir(public_path('app'), 666, true);
            }
            $image_resize->save(public_path('app/' . $nombre_archivo));

            $url_foto =  '/app/' . $nombre_archivo;
        }

        User::find($request->id)->update([
            "photo" => $url_foto
        ]);

        return response()->json(true, 200);
    }

    public function profile($user_id){
        return response()->json(User::find($user_id));
    }
}
