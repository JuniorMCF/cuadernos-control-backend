<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Coin;
use App\Models\Country;
use App\Models\Enterprise;
use App\Models\Product;
use App\Models\Record;
use App\Models\Sale;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

class EnterpriseController extends Controller
{
    //
    public function show($user_id)
    {
        $enterprise = Enterprise::where("user_id", $user_id)->with("coins")->with("countries")->get()->first();
        $countrys = Country::all();
        $coins = Coin::all();
        if ($enterprise) {
            return response([
                "data" => [
                    'enterprise' => $enterprise,
                    'countrys' => $countrys,
                    'coins' => $coins,
                ],
                "success" => true,
                "message" => ''
            ]);
        }
        return response([
            "data" => null,
            "success" => false,
            "message" => 'Company not found associate to user_id'
        ]);
    }

    public function products($user_id)
    {
        $enterprise = Enterprise::where("user_id", $user_id)->get()->first();

        if ($enterprise) {
            $products = Product::where("enterprise_id", $enterprise->id)->get();

            $coin = Coin::where('id',$enterprise->coin_id)->get()->first();
            return response([
                "data" => [
                    'enterprise' => $enterprise,
                    'products' => $products,
                    'coin'=>$coin
                ],
                "success" => true,
                "message" => ''
            ]);
        }
        return response([
            "data" => null,
            "success" => false,
            "message" => 'Company not found associate to user_id'
        ]);
    }
    public function services($user_id)
    {
        $enterprise = Enterprise::where("user_id", $user_id)->get()->first();

        if ($enterprise) {
            $services = Service::where("enterprise_id", $enterprise->id)->get();

            $coin = Coin::where('id',$enterprise->coin_id)->get()->first();
            return response([
                "data" => [
                    'enterprise' => $enterprise,
                    'services' => $services,
                    'coin'=>$coin
                ],
                "success" => true,
                "message" => ''
            ]);
        }
        return response([
            "data" => null,
            "success" => false,
            "message" => 'Company not found associate to user_id'
        ]);
    }

    public function clients($user_id)
    {
        $enterprise = Enterprise::where("user_id", $user_id)->get()->first();

        if ($enterprise) {
            $clients = Client::where("enterprise_id", $enterprise->id)->get();

            return response([
                "data" => [
                    'enterprise' => $enterprise,
                    'clients' => $clients,

                ],
                "success" => true,
                "message" => ''
            ]);
        }
        return response([
            "data" => null,
            "success" => false,
            "message" => 'Company not found associate to user_id'
        ]);
    }
    public function sales($user_id) /**for products */
    {
        $enterprise = Enterprise::where("user_id", $user_id)->get()->first();

        if ($enterprise) {
            $clients = Client::where("enterprise_id", $enterprise->id)->get();
            $products = Product::where("enterprise_id", $enterprise->id)->get();
            $sales = Sale::join("products","sales.product_id","=","products.id")
                            ->join("clients","sales.client_id","=","clients.id")
                            ->where("sales.enterprise_id", $enterprise->id)
                            ->select("sales.*","products.name as product_name","clients.first_name as client_name","clients.dni as client_dni")
                            ->get();
            $coin = Coin::where('id',$enterprise->coin_id)->get()->first();
            return response([
                "data" => [
                    'enterprise' => $enterprise,
                    'clients' => $clients,
                    'products'=>$products,
                    'sales'=>$sales,
                    'coin'=>$coin

                ],
                "success" => true,
                "message" => ''
            ]);
        }
        return response([
            "data" => null,
            "success" => false,
            "message" => 'Company not found associate to user_id'
        ]);
    }
    public function records($user_id) /**for services */
    {
        $enterprise = Enterprise::where("user_id", $user_id)->get()->first();

        if ($enterprise) {
            $clients = Client::where("enterprise_id", $enterprise->id)->get();
            $services = Service::where("enterprise_id", $enterprise->id)->get();
            $records = Record::join("services","records.service_id","=","services.id")
                            ->join("clients","records.client_id","=","clients.id")
                            ->where("records.enterprise_id", $enterprise->id)
                            ->select("records.*","services.name as product_name","clients.first_name as client_name","clients.dni as client_dni")
                            ->get();
            $coin = Coin::where('id',$enterprise->coin_id)->get()->first();
            return response([
                "data" => [
                    'enterprise' => $enterprise,
                    'clients' => $clients,
                    'services'=>$services,
                    'records'=>$records,
                    'coin'=>$coin

                ],
                "success" => true,
                "message" => ''
            ]);
    }
    return response([
        "data" => null,
        "success" => false,
        "message" => 'Company not found associate to user_id'
    ]);
}


    public function updateLogo(Request $request)
    {
        $enterprise = Enterprise::find($request->id);
        $url_foto = "";
        if ($request->hasFile('file')) {
            $this->validate($request, [
                'file_upload' => 'image|mimes:jpg,jpeg,png,gif,svg|max:2048',
            ]);

            ini_set('memory_limit', '256M');

            File::delete($enterprise->logo);

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

        Enterprise::find($request->id)->update([
            "logo" => $url_foto

        ]);

        return response()->json(true, 200);
    }
    public function updateEnterprise(Request $request)
    {
        $validator  =  Validator::make($request->all(), [
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

        $enterprise = Enterprise::find($request->id)->update($request->all());

        return response()->json(
            [
                "data" => $enterprise,
                "success" => true,
                "message" => "Registro actualizado"
            ]
        );
    }
}
