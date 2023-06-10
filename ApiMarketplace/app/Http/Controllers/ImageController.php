<?php

namespace App\Http\Controllers;

use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ImageController extends Controller
{
    public function index(Request $request)
    {
        Log::channel('Marketify_API')->info('Received request: GET /images/view/all', [
            'user_agent' => $request->userAgent(),
            'ip_address' => $request->ip(),
            'parameters' => $request->all(),
        ]);

        $images = Image::where('main', true)->get();

        if ($images->count() > 0) {
            $response = response($images, 200);
            $response->header('Cache-Control', 'public, max-age=5184000'); // 60 days in seconds

            Log::channel('Marketify_API')->info('Sent response: 200 OK', [
                'content' => $response->getContent(),
            ]);

            return $response;
        } else {
            Log::channel('Marketify_API')->info('Sent response: 404 Not Found');

            return abort(404);
        }
    }

    public function show(Request $request, $id)
    {
        Log::channel('Marketify_API')->info('Received request: GET /images/view/' . $id, [
            'user_agent' => $request->userAgent(),
            'ip_address' => $request->ip(),
            'parameters' => $request->all(),
        ]);

        $images = Image::where('product_id', $id)->get();

        if ($images->count() > 0) {
            $response = response($images, 200);
            $response->header('Cache-Control', 'public, max-age=5184000'); // 60 days in seconds

            Log::channel('Marketify_API')->info('Sent response: 200 OK', [
                'content' => $response->getContent(),
            ]);

            return $response;
        } else {
            Log::channel('Marketify_API')->info('Sent response: 404 Not Found');
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

        Log::channel('Marketify_API')->info('Image inserted successfully (BY SEEDER)');

        return response(['message' => 'Image inserted successfully', 'image' => $image], 200);
    }

    public function insert(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string',
            'path' => 'required|string',
            'product_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'product_id' => 'required|integer',
            'main' => 'required|boolean',
        ]);

        $name = $validatedData['name'];
        $path = $validatedData['path'];
        $image = $validatedData['product_image'];
        $productID = $validatedData['product_id'];
        $main = $validatedData['main'];

        $realpath = 'images/products/';
        $image->move($realpath, $name);

        Image::create([
            'name' => $name,
            'path' => $path,
            'product_id' => $productID,
            'main' => $main,
        ]);

        Log::channel('Marketify_API')->info('Image inserted successfully', [
            'name' => $name,
            'path' => $path,
            'product_id' => $productID,
            'main' => $main,
        ]);

        return response(['message' => 'Image inserted successfully', 'image' => $image], 200);
    }

    public function delete($id)
    {
        $image = Image::find($id);

        if ($image) {
            $image->delete();
            Storage::disk('public2')->delete($image->path);

            Log::channel('Marketify_API')->info('The image ' . $id . ' has been deleted', [
                'image_id' => $id,
            ]);

            return response(['message' => 'The image ' . $id . ' has been deleted'], 200);
        } else {
            return abort(404);
        }
    }

    public function deleteAll($id)
    {
        $images = Image::where('product_id', $id);

        if ($images->count() > 0) {
            foreach ($images as $image) {
                $image->delete();
                Storage::disk('public2')->delete($image->path);
            }

            Log::channel('Marketify_API')->info('All images of the product ' . $id . ' have been deleted', [
                'image_id' => $id,
            ]);

            return response(['message' => 'All images of the product ' . $id . ' have been deleted'], 200);
        } else {
            return abort(404);
        }
    }
}