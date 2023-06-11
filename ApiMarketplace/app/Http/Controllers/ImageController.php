<?php

namespace App\Http\Controllers;

use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ImageController extends Controller
{
    /**
     * Get all images.
     */
    public function index(Request $request)
    {
        try {
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

                return response(['message' => 'No images found'], 404);
            }
        } catch (\Exception $e) {
            Log::channel('Marketify_API')->error('An error occurred: ' . $e->getMessage());

            return response(['error' => 'An error occurred'], 500);
        }
    }

    /**
     * Retrieve images by product ID.
     */
    public function show(Request $request, $id)
    {
        try {
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
                
                return response(['message' => 'No images found for product ' . $id], 404);
            }
        } catch (\Exception $e) {
            Log::channel('Marketify_API')->error('An error occurred: ' . $e->getMessage());
            
            return response(['error' => 'An error occurred'], 500);
        }
    }

    /**
     * Insert images using seeder data.
     */
    public function insertSeeder(Request $request)
    {
        try {
            $imagesData = $request->input('insert_images');
        
            $images = [];
            foreach ($imagesData as $data) {
                $image = Image::create([
                    'name' => $data['name'],
                    'path' => $data['path'] ?? 'images/products/default-product.png',
                    'product_id' => $data['product_id'],
                    'main' => $data['main']
                ]);
                $images[] = $image;
            }
        
            Log::channel('Marketify_API')->info('Images inserted successfully (BY SEEDER)');
        
            return response(['message' => 'Images inserted successfully', 'images' => $images], 200);
        } catch (\Exception $e) {
            Log::channel('Marketify_API')->error('An error occurred: ' . $e->getMessage());
            
            return response(['error' => 'An error occurred'], 500);
        }
    }

    /**
     * Insert a new image.
     */
    public function insert(Request $request)
    {
        try {
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

            return response(['message' => 'Image inserted successfully'], 200);
        } catch (\Exception $e) {
            Log::channel('Marketify_API')->error('An error occurred: ' . $e->getMessage());
            
            return response(['error' => 'An error occurred'], 500);
        }
    }

    /**
     * Delete an image by ID.
     */
    public function delete($id)
    {
        try {
            $image = Image::find($id);

            if ($image) {
                $image->delete();

                Log::channel('Marketify_API')->info('The image ' . $id . ' has been deleted', [
                    'image_id' => $id,
                ]);

                return response(['message' => 'The image ' . $id . ' has been deleted'], 200);
            } else {
                return response(['message' => 'Image not found'], 404);
            }
        } catch (\Exception $e) {
            Log::channel('Marketify_API')->error('An error occurred: ' . $e->getMessage());
            
            return response(['error' => 'An error occurred'], 500);
        }
    }

    /**
     * Delete all images of a product.
     */
    public function deleteAll($id)
    {
        try {
            $images = Image::where('product_id', $id);

            if ($images->count() > 0) {
                $images->delete();

                Log::channel('Marketify_API')->info('All images of the product ' . $id . ' have been deleted', [
                    'image_id' => $id,
                ]);

                return response(['message' => 'All images of the product ' . $id . ' have been deleted'], 200);
            } else {
                return response(['message' => 'No images found for product ' . $id], 404);
            }
        } catch (\Exception $e) {
            Log::channel('Marketify_API')->error('An error occurred: ' . $e->getMessage());
            
            return response(['error' => 'An error occurred'], 500);
        }
    }
}