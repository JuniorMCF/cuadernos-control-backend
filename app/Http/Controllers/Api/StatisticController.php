<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Enterprise;
use App\Models\Record;
use App\Models\Sale;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class StatisticController extends Controller
{
    //
    public function show(Request $request){
        $validator = Validator::make($request->all(),[
            'user_id'=>'sometimes|required',
            'year'=>'sometimes'
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

        $enterprise = Enterprise::where("user_id",$request->user_id)->first();

        if($enterprise){


            $year = $request->year;
            $month = date('m'); //mes actual

            $date_month_start = Carbon::createFromDate($year, $month)->startOfMonth();
            $date_month_end = Carbon::createFromDate($year, $month)->endOfMonth();

            $sales_current_month_total = Sale::where("enterprise_id",$enterprise->id)->whereBetween('updated_at', [$date_month_start,$date_month_end])->select(DB::raw('sum(price_actual * quantity) as total'))->first();
            $sales_current_year_total = Sale::where("enterprise_id",$enterprise->id)->whereYear('updated_at', $year)->select(DB::raw('sum(price_actual * quantity) as total'))->first();

            $records_current_month_total =  Record::where("enterprise_id",$enterprise->id)->whereBetween('updated_at', [$date_month_start,$date_month_end])->select(DB::raw('sum(price_actual * quantity) as total'))->first();
            $records_current_year_total =  Record::where("enterprise_id",$enterprise->id)->whereYear('updated_at', $year)->select(DB::raw('sum(price_actual * quantity) as total'))->first();


            $diff_sale = Sale::where("enterprise_id",$enterprise->id)->where(function($q) {
                $q->where('status', 'Pendiente')
                  ->orWhere('status', 'Con adelanto');
            })->whereBetween('updated_at',[$date_month_start,$date_month_end])->select(DB::raw('sum(price_actual * quantity - registered_amount) as total'))->first();

            $diff_record = Record::where("enterprise_id",$enterprise->id)->where(function($q) {
                $q->where('status', 'Pendiente')
                  ->orWhere('status', 'Con adelanto');
            })->whereBetween('updated_at', [$date_month_start,$date_month_end])->select(DB::raw('sum(price_actual * quantity - registered_amount) as total'))->first();


            $diff_sale_year = Sale::where("enterprise_id",$enterprise->id)->where(function($q) {
                $q->where('status', 'Pendiente')
                  ->orWhere('status', 'Con adelanto');
            })->whereYear('updated_at',$year)->select(DB::raw('sum(price_actual * quantity - registered_amount) as total'))->first();

            $diff_record_year = Record::where("enterprise_id",$enterprise->id)->where(function($q) {
                $q->where('status', 'Pendiente')
                  ->orWhere('status', 'Con adelanto');
            })->whereYear('updated_at', $year)->select(DB::raw('sum(price_actual * quantity - registered_amount) as total'))->first();

            $diff_current_month = $diff_sale->total + $diff_record->total;

            $diff_current_year = $diff_sale_year->total + $diff_record_year->total;


            $sales_weekly = Sale::where("enterprise_id",$enterprise->id)->whereBetween("updated_at", [$date_month_start,$date_month_end])->get();
            $records_weekly = Record::where("enterprise_id",$enterprise->id)->whereBetween("updated_at", [$date_month_start,$date_month_end])->get();


            $sales_current_months = Sale::where("enterprise_id",$enterprise->id)->whereYear('updated_at',  $year)->get();



            $records_current_months = Record::where("enterprise_id",$enterprise->id)->whereYear('updated_at',  $year)->get();


            $top_ten_sales_quantity = Sale::join("products","products.id","=","sales.product_id")
                                            ->whereYear('sales.updated_at',  $year)
                                            ->select('sales.product_id',"sales.product_name", DB::raw('count(sales.product_id) as total'))
                                            ->groupBy('sales.product_id',"sales.product_name")
                                            ->take(10)->get();

            $top_ten_sales_amount = Sale::join("products","products.id","=","sales.product_id")
                                            ->whereYear('sales.updated_at',  $year)
                                            ->select('sales.product_id',"sales.product_name", DB::raw('sum(sales.price_actual * sales.quantity) as total'))
                                            ->groupBy('sales.product_id',"sales.product_name")
                                            ->take(10)->get();


            $top_ten_records_quantity = Record::join("services","services.id","=","records.service_id")
                                            ->whereYear('records.updated_at',  $year)
                                            ->select('records.service_id',"records.service_name", DB::raw('count(records.service_id) as total'))
                                            ->groupBy('records.service_id',"records.service_name")
                                            ->take(10)->get();

            $top_ten_records_amount = Record::join("services","services.id","=","records.service_id")
                                            ->whereYear('records.updated_at',   $year)
                                            ->select('records.service_id',"records.service_name", DB::raw('sum(records.price_actual * records.quantity) as total'))
                                            ->groupBy('records.service_id',"records.service_name")
                                            ->take(10)->get();


            $sales_invoices = Sale::groupBy('invoice')
                            ->whereYear('updated_at',  $year)
                            ->select('invoice', DB::raw('count(invoice) as total'))->get();

            $records_invoices = Record::groupBy('invoice')
                            ->whereYear('updated_at',  $year)
                            ->select('invoice', DB::raw('count(invoice) as total'))->get();


            $diff_sale_top_client = Sale::join("clients","clients.id","=","sales.client_id")
            ->where("sales.enterprise_id",$enterprise->id)->where(function($q) {
                $q->where('sales.status', 'Pendiente')
                  ->orWhere('sales.status', 'Con adelanto');
            })->whereYear('sales.updated_at', $year)
            ->select('sales.client_id',"sales.client_name",DB::raw('sum(sales.price_actual * sales.quantity - sales.registered_amount) as total'))
            ->groupBy('sales.client_id',"sales.client_name")
            ->take(10)
            ->get();

            $diff_record_top_client = Record::join("clients","clients.id","=","records.client_id")
            ->where("records.enterprise_id",$enterprise->id)->where(function($q) {
                $q->where('records.status', 'Pendiente')
                  ->orWhere('records.status', 'Con adelanto');
            })->whereYear('records.updated_at', $year)
            ->select('records.client_id','records.client_name',DB::raw('sum(records.price_actual * records.quantity - records.registered_amount) as total'))
            ->groupBy('records.client_id','records.client_name')
            ->take(10)
            ->get();




            return response()->json(
                [
                    "data" => [
                        "sales_current_month_total"=>$sales_current_month_total->total == null ? 0.0 : $sales_current_month_total->total,
                        "sales_current_year_total"=>$sales_current_year_total->total == null ? 0.0 : $sales_current_year_total->total,
                        "records_current_month_total"=>$records_current_month_total->total == null ? 0.0 : $records_current_month_total->total,
                        "records_current_year_total"=>$records_current_year_total->total == null ? 0.0 : $records_current_year_total->total,
                        "difference_month"=>$diff_current_month == null ? 0.0 :$diff_current_month,
                        "difference_year"=>$diff_current_year  == null ? 0.0 :$diff_current_year,
                        "sales_weekly"=>$sales_weekly == null ? [] :$sales_weekly,
                        "records_weekly"=>$records_weekly== null ? [] :$records_weekly,
                        "sales_months"=>$sales_current_months== null ? [] :$sales_current_months,
                        "records_months"=>$records_current_months== null ? [] :$records_current_months,
                        "top_ten_sales_quantity"=>$top_ten_sales_quantity== null ? [] :$top_ten_sales_quantity,
                        "top_ten_record_quantity"=>$top_ten_records_quantity== null ? [] :$top_ten_records_quantity,
                        "top_ten_sales_amount"=>$top_ten_sales_amount== null ? [] :$top_ten_sales_amount,
                        "top_ten_records_amount"=>$top_ten_records_amount== null ? [] :$top_ten_records_amount,
                        "sales_invoices"=>$sales_invoices== null ? [] :$sales_invoices,
                        "records_invoices"=>$records_invoices== null ? [] :$records_invoices,
                        "top_ten_clients_sales"=>$diff_sale_top_client== null ? [] :$diff_sale_top_client,
                        "top_ten_clients_records"=>$diff_record_top_client== null ? [] :$diff_record_top_client,
                    ],
                    "success" => true,
                    "message" => "Service deleted succesfully"
                ]
            );


        }

        return response()->json(
            [
                "data" => null,
                "success" => false,
                "message" => "Not enterprise"
            ]
        );

    }
}
