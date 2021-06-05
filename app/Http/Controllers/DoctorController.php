<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DoctorController extends Controller
{
    //
    public function addDoctor(Request $data): JsonResponse
    {
        $doctor=Doctor::create([
            'name' => $data['name'],
            'category'=>$data['category'],
            'phoneNumber'=>$data['phoneNumber']

        ]);

        return response()->json($doctor);

    }
    public function getDoctors(): JsonResponse
    {
        $doctors=Doctor::all();

        return response()->json($doctors);
    }

    public function getDoctorsbyCategory(Request $data){
        $category=[$data['category']];

      $result=  DB::select('select * from doctors where category=?',$category);

      return response()->json($result);
    }

}
