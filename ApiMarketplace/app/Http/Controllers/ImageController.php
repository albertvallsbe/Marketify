<?php

namespace App\Http\Controllers;

use App\Models\Image;
use Illuminate\Http\Request;

class ImageController extends Controller
{
    public function index(){
        $images = Image::all();

        if ($images && $images->count() > 0) {
            return response($images,200);

        }else {
        abort(404);
        }

    }

    public function catchImage($id){
        $images = Image::where('product_id',$id)->get();

        if ($images && $images->count() > 0) {
            return response($images,200);

        }else {
        abort(404);
        }
    }

    public function insertImage(Request $request){

        Image::create([
            'name'=> $request['name'],
            'path'=> $request['path'],
            'product_id'=>$request['product_id'],
            'main'=>$request['main']
<<<<<<< HEAD
        ]); 

=======
        ]);
>>>>>>> 65d9d44f55bd43d0d2059ff282f3d641b4f1c953
    }

    public function deleteImage($id){
        $image = Image::find($id);

        if ($image && $image->count() > 0) {
            $image->delete();
            return "La imagen ". $id ." ha sido borrada";

        }else {
        abort(404);
        }

    }
    public function deleteAll($id){
        $images = Image::where('product_id',$id);

        if ($images && $images->count() > 0) {
            $images->delete();
            return "Las imagenes del producto ". $id ." han sido borradas.";

        }else {
        abort(404);
        }

    }
}
