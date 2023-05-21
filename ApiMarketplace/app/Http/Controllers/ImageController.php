<?php

namespace App\Http\Controllers;

use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ImageController extends Controller
{

    /** 
     * EXPLICAR EL PORQUE HE CAMBIADO LA FUNCIÓN EN LA DOCUMENTACIÓN 
    */
     
    public function index() {
        $images = Image::where('main', true)->get();
    
        if ($images && $images->count() > 0) {
            return response($images, 200);
        } else {
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
        ]);
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
