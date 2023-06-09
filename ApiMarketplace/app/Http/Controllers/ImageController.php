<?php

namespace App\Http\Controllers;

use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ImageController extends Controller
{
    public function index()
    {
        $images = Image::where('main', true)->get();

        if ($images->count() > 0) {
            return response($images, 200);
        } else {
            return abort(404);
        }
    }

    public function show($id)
    {
        $images = Image::where('product_id', $id)->get();

        if ($images->count() > 0) {
            return response($images, 200);
        } else {
            return abort(404);
        }
    }

    public function insertSeeder(Request $request)
    {
        $image = Image::create([
            'name' => $request->input('name'),
            'path' => $request->input('path', 'images/products/default-product.png'),
            'product_id' => $request->input('product_id'),
            'main' => $request->input('main')
        ]);

        return response(['message' => 'Image inserted successfully', 'image' => $image], 200);
    }

    public function insert(Request $request)
    {
        $validatedData = $request->validate([
            'product_image' => 'required|array',
            'product_image.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    
        $path = 'images/products/';
    
        $imagePaths = [];
    
        foreach ($validatedData['product_image'] as $image) {
            
            $name = uniqid('product_') . '.' . $image->getClientOriginalExtension();
            $image->move(public_path($path), $name);
            $imagePaths[] = $path . $name;
        }
        
        return response()->json([
            'message' => 'Imágenes cargadas exitosamente.',
            'image_paths' => $imagePaths,
        ], 200);
    }

    public function delete($id)
    {
        $image = Image::find($id);

        if ($image) {
            $image->delete();
            return response(['message' => 'The image ' . $id . ' has been deleted'], 200);
        } else {
            return abort(404);
        }
    }

    public function deleteAll($id)
    {
        $images = Image::where('product_id', $id);

        if ($images->count() > 0) {
            $images->delete();
            return response(['message' => 'All images of the product ' . $id . ' have been deleted'], 200);
        } else {
            return abort(404);
        }
    }
}
