<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Coin;
use App\Models\Enterprise;
use App\Models\Record;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    //
    public function generate(Request $request){
        $validator = Validator::make($request->all(),[
            'list_ids'=>'sometimes|required',
            'type'=>'sometimes',
            'user_id'=>'sometimes|required',
            'concept'=>'sometimes'//product or service
        ]);

        if ($validator->fails()) {

            abort(422, "Faltan parametros");
        }



        try {
            DB::beginTransaction();

            $list_ids = json_decode($request->list_ids);

            if( count($list_ids) == 0){

                abort(400, 'Debe de elegir al menos una fila');

            }

            $enterprise = Enterprise::where("user_id", $request->user_id)->get()->first();

            if($enterprise->logo == null){

                abort(404, 'Configure el logotipo de su empresa');


            }

            $coin = Coin::where('id',$enterprise->coin_id)->get()->first();

            if($request->concept == 'product'){
                $invoice_id = Sale::where('enterprise_id',$enterprise->id)->max('invoice_id') + 1;
            }else if($request->concept == 'service'){
                $invoice_id = Record::where('enterprise_id',$enterprise->id)->max('invoice_id') + 1;
            }






            if($request->type == "ticket"){
                /**verificacion */
                foreach($list_ids as $id){
                    if($request->concept == 'product'){
                        $sale = Sale::where("id",$id)->whereNotNull("invoice_id")->first();
                    }else if($request->concept == 'service'){
                        $sale = Record::where("id",$id)->whereNotNull("invoice_id")->first();
                    }

                    if($sale){

                        abort(401, 'Uno de los elementos pertenece a otro documento');
                    }
                }



                /**actualizacion */
                foreach($list_ids as $id){
                    if($request->concept == 'product'){
                        $sale = Sale::find($id);
                        $client = Client::find($sale->client_id);

                        Sale::find($id)->update([
                            'invoice_id'=>$invoice_id,
                            'invoice'=>"Boleta",
                            'serie'=>"BPR".str_pad($invoice_id,8,"0",STR_PAD_LEFT)
                        ]);
                    }else if($request->concept == 'service'){
                        $sale = Record::find($id);
                        $client = Client::find($sale->client_id);

                        Record::find($id)->update([
                            'invoice_id'=>$invoice_id,
                            'invoice'=>"Boleta",
                            'serie'=>"BSE".str_pad($invoice_id,8,"0",STR_PAD_LEFT)
                        ]);
                    }



                }




                if($request->concept == 'product'){
                    $products = Sale::join("products","products.id","=","sales.product_id")
                                ->where("sales.invoice_id",$invoice_id)
                                ->select("sales.*","products.name as product_name","products.description as product_description")
                                ->get();
                    $total = Sale::where('invoice_id',$invoice_id)->select(DB::raw('sum(price_actual * quantity) as total'))->get();
                }else if($request->concept == 'service'){
                    $products = Record::join("services","services.id","=","records.service_id")
                                ->where("records.invoice_id",$invoice_id)
                                ->select("records.*","services.name as product_name","services.description as product_description")
                                ->get();
                    $total = Record::where('invoice_id',$invoice_id)->select(DB::raw('sum(price_actual * quantity) as total'))->get();
                }


                $pdf = Pdf::loadView('pdf', ['products' => $products,'enterprise'=>$enterprise,'coin'=>$coin,'client'=>$client,'type'=>"BOLETA ELECTRÓNICA",'total'=>$total[0]["total"]]);

                // database queries here
                DB::commit();

                return $pdf->download(str_pad($invoice_id,8,"0",STR_PAD_LEFT).'.pdf');


            }else if($request->type == "invoice"){

                foreach($list_ids as $id){
                    if($request->concept == 'product'){
                        $sale = Sale::where("id",$id)->whereNotNull("invoice_id")->first();
                    }else if($request->concept == 'service'){
                        $sale = Record::where("id",$id)->whereNotNull("invoice_id")->first();
                    }
                    if($sale){
                            abort(401, 'Uno de los elementos pertenece a otro documento');
                    }
                }



                foreach($list_ids as $id){
                    if($request->concept == 'product'){
                        $sale = Sale::find($id);
                        $client = Client::find($sale->client_id);

                        Sale::find($id)->update([
                            'invoice_id'=>$invoice_id,
                            'invoice'=>"Factura",
                            'serie'=>"FPR".str_pad($invoice_id,8,"0",STR_PAD_LEFT)
                        ]);
                    }else if($request->concept == 'service'){
                        $sale = Record::find($id);
                        $client = Client::find($sale->client_id);

                        Record::find($id)->update([
                            'invoice_id'=>$invoice_id,
                            'invoice'=>"Factura",
                            'serie'=>"FSE".str_pad($invoice_id,8,"0",STR_PAD_LEFT)
                        ]);
                    }



                }




                if($request->concept == 'product'){
                    $products = Sale::join("products","products.id","=","sales.product_id")
                            ->where("sales.invoice_id",$invoice_id)
                            ->select("sales.*","products.name as product_name","products.description as product_description")
                            ->get();
                    $total = Sale::where('invoice_id',$invoice_id)->select(DB::raw('sum(price_actual * quantity) as total'))->get();
                }else if($request->concept == 'service'){
                    $products = Record::join("services","services.id","=","records.service_id")
                            ->where("records.invoice_id",$invoice_id)
                            ->select("records.*","services.name as product_name","services.description as product_description")
                            ->get();
                    $total = Record::where('invoice_id',$invoice_id)->select(DB::raw('sum(price_actual * quantity) as total'))->get();
                }

                $pdf = Pdf::loadView('pdf', ['products' => $products,'enterprise'=>$enterprise,'coin'=>$coin,'client'=>$client,'type'=>"FACTURA ELECTRÓNICA",'total'=>$total[0]["total"]]);

                // database queries here
                DB::commit();

                return $pdf->download(str_pad($invoice_id,8,"0",STR_PAD_LEFT).'.pdf');


            }else if($request->type == "comprobante"){
                foreach($list_ids as $id){
                    if($request->concept == 'product'){
                        $sale = Sale::where("id",$id)->whereNotNull("invoice_id")->first();
                    }else if($request->concept == 'service'){
                        $sale = Record::where("id",$id)->whereNotNull("invoice_id")->first();
                    }
                    if($sale){
                        abort(401, 'Uno de los elementos pertenece a otro documento');
                    }
                }



                foreach($list_ids as $id){
                    if($request->concept == 'product'){
                        $sale = Sale::find($id);
                        $client = Client::find($sale->client_id);

                        Sale::find($id)->update([
                            'invoice_id'=>$invoice_id,
                            'invoice'=>"Comprobante",
                            'serie'=>"CPR".str_pad($invoice_id,8,"0",STR_PAD_LEFT)
                        ]);
                    }else if($request->concept == 'service'){
                        $sale = Record::find($id);
                        $client = Client::find($sale->client_id);

                        Record::find($id)->update([
                            'invoice_id'=>$invoice_id,
                            'invoice'=>"Comprobante",
                            'serie'=>"CSE".str_pad($invoice_id,8,"0",STR_PAD_LEFT)
                        ]);
                    }



                }




                if($request->concept == 'product'){
                    $products = Sale::join("products","products.id","=","sales.product_id")
                            ->where("sales.invoice_id",$invoice_id)
                            ->select("sales.*","products.name as product_name","products.description as product_description")
                            ->get();
                    $total = Sale::where('invoice_id',$invoice_id)->select(DB::raw('sum(price_actual * quantity) as total'))->get();
                }else if($request->concept == 'service'){
                    $products = Record::join("services","services.id","=","records.service_id")
                            ->where("records.invoice_id",$invoice_id)
                            ->select("records.*","services.name as product_name","services.description as product_description")
                            ->get();
                    $total = Record::where('invoice_id',$invoice_id)->select(DB::raw('sum(price_actual * quantity) as total'))->get();
                }

                $pdf = Pdf::loadView('pdf', ['products' => $products,'enterprise'=>$enterprise,'coin'=>$coin,'client'=>$client,'type'=>"COMPROBANTE ELECTRÓNICO",'total'=>$total[0]["total"]]);

                // database queries here
                DB::commit();

                return $pdf->download(str_pad($invoice_id,8,"0",STR_PAD_LEFT).'.pdf');
            }

        } catch (\PDOException $e) {
            // Woopsy
            DB::rollBack();
        }
    }

    public function rebuildGenerate(Request $request){
        $validator = Validator::make($request->all(),[
            'id'=>'sometimes|required',
            'user_id'=>'sometimes|required',
            'concept'=>'sometimes'//product or service
        ]);

        if ($validator->fails()) {

            abort(422, "Faltan parametros");
        }

        $enterprise = Enterprise::where("user_id", $request->user_id)->get()->first();
        if($enterprise->logo == null){
            abort(404, 'Configure el logotipo de su empresa');
        }

        $coin = Coin::where('id',$enterprise->coin_id)->get()->first();

        try {
            DB::beginTransaction();
            if($request->concept == 'product'){
                $sale = Sale::find($request->id);
                $serie = $sale->serie;
                $client = Client::find($sale->client_id);

                $products = Sale::join("products","products.id","=","sales.product_id")
                        ->where("sales.serie",$serie)
                        ->where("sales.enterprise_id",$enterprise->id)
                        ->select("sales.*","products.name as product_name","products.description as product_description")
                        ->get();
                $total = Sale::where('serie',$serie)->select(DB::raw('sum(price_actual * quantity) as total'))->get();

                if($sale->invoice == "Boleta"){
                    $type = "BOLETA ELECTRÓNICA";
                }else if($sale->invoice == "Factura"){
                    $type = "FACTURA ELECTRÓNICA";
                }else if($sale->invoice == "Comprobante"){
                    $type = "COMPROBANTE ELECTRÓNICO";
                }


                $pdf = Pdf::loadView('pdf', ['products' => $products,'enterprise'=>$enterprise,'coin'=>$coin,'client'=>$client,'type'=>$type,'total'=>$total[0]["total"]]);

                // database queries here
                DB::commit();

                return $pdf->download(str_pad($sale->invoice_id,8,"0",STR_PAD_LEFT).'.pdf');

            }else if($request->concept == 'service'){

                $record = Record::find($request->id);


                $serie = $record->serie;
                $client = Client::find($record->client_id);

                $products = Record::join("services","services.id","=","records.service_id")
                                ->where("records.serie",$serie)
                                ->select("records.*","services.name as product_name","services.description as product_description")
                                ->get();
                $total = Record::where('serie',$serie)->select(DB::raw('sum(price_actual * quantity) as total'))->get();

                if($record->invoice == "Boleta"){
                    $type = "BOLETA ELECTRÓNICA";
                }else if($record->invoice == "Factura"){
                    $type = "FACTURA ELECTRÓNICA";
                }else if($record->invoice == "Comprobante"){
                    $type = "COMPROBANTE ELECTRÓNICO";
                }


                $pdf = Pdf::loadView('pdf', ['products' => $products,'enterprise'=>$enterprise,'coin'=>$coin,'client'=>$client,'type'=>$type,'total'=>$total[0]["total"]]);

                // database queries here
                DB::commit();

                return $pdf->download(str_pad($record->invoice_id,8,"0",STR_PAD_LEFT).'.pdf');
            }
        }catch (\PDOException $e) {
            // Woopsy
            DB::rollBack();
        }
    }
}
