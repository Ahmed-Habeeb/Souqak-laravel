<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ItemController extends Controller
{
    //


    public function addItem(Request $request0): JsonResponse
    {
        $image = $this->saveImage($request0);
        $user=auth('api')->user();
        $request=$request0["data"];
        
        $result = Item::create([

            'user_id' => $user["id"],
            'name' => $request['name'],
            'details' => $request['details'],
            'category' => $request['category'],
            'subcategory' => $request['subcategory'],
            'image' => $image,
            'price' => $request['price'],


        ]);
        return response()->json($result);
    }

    public function updateItem(Request $request0): JsonResponse
    {
        $image = $this->saveImage($request0);
        $user=auth('api')->user();
        $request=$this->$request0["data"];
  
        $result = Item::where('id', $request['id'])->update([
            'user_id' => $request['user_id'],
            'name' => $request['name'],
            'details' => $request['details'],
            'category' => $request['category'],
            'subcategory' => $request['subcategory'],
            'image' => $image,
            'price' => $request['price'],
            'phone'=>$request['phone']


        ]);
        $res = Item::find($request['id']);
        return response()->json($res);
    }

    public function deleteItem(Request $request): JsonResponse
    {
        $res = Item::find($request['id']);
        if ($res != null) {
            $result = $res->delete();
            return ($result) ? response()->json(true) : response()->json(false);
        }
        return response()->json(false);

    }


    public  function fetchLatest(){
      /*  $type1 = Item::whereCategory("Clothes & Accessories")->take(10);
        $type2 = Item::whereCategory("Family")->take(10);
        $type3 = Item::whereCategory("Vehicles")->take(10);
        $type4 = Item::whereCategory("Home & Garden")->take(10);
        $type5 = Item::whereCategory("Electronics")->take(10);
        $type6 = Item::whereCategory("Entertainment")->take(10);
        $types = $type1->union($type2)->union($type3)->union($type4)->union($type5)->union($type6)->get();
       */

      $items=Item::paginate(10);
      
      return response()->json($items);
    }

    public function fetchAll(): JsonResponse
    {

        $items=Item::all();
        return response()->json($items);


    }
    public function fetchCategory(Request $request): JsonResponse
    {
        $data=$request["category"];
        if ($data=="All"){
            return $this->fetchAll();


        }

        $items=DB::select("select * from items where category ='$data'");
        return response()->json($items);


    }
    public function fetchSubcategory(Request $request): JsonResponse
    {
        $category=$request["category"];
        $subcategory=$request["subcategory"];
        if ($category=="All"&&$subcategory=="All"){
            return $this->fetchAll();


        }
        elseif  ($category=="All"&&$subcategory !="All"){
            $items=DB::select("select * from items where subcategory='$subcategory'");
            return response()->json($items);


        }

        $items=DB::select("select * from items where category ='$category' and subcategory='$subcategory'");
        return response()->json($items);
        


    }
    public function fetchUserId(Request $request): JsonResponse
    {
        $data=[$request["user_id"]];

        $items=DB::select("select * from items where user_id =?",$data);
        return response()->json($items);


    }









    public function saveImage(Request $data): string
    {
        $file_extension = $data["image"]->getClientOriginalExtension();
        $filename = time() . "." . $file_extension;
        $path = "storage/images";
        $data["image"]->move($path, $filename);


        return $filename;

    }

}
