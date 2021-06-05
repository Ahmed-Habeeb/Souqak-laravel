<?php

namespace App\Http\Controllers;

use App\Models\service;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ServiceController extends Controller
{
    //
    public function addService(Request $data): JsonResponse
    {
        $service=service::create([
            'name' => $data['name'],
            'category'=>$data['category'],
            'phoneNumber'=>$data['phoneNumber']

        ]);

        return response()->json($service);

    }
    public function getServices(): JsonResponse
    {
        $services=service::all();

        return response()->json($services);
    }

    public function getServicessbyCategory(Request $data){
        $category=[$data['category']];

        $result=  DB::select('select * from services where category=?',$category);

        return response()->json($result);
    }

}
