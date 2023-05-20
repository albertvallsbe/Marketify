<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Order;
use GuzzleHttp\Client;
use App\Classes\HeaderVariables;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LandingController extends Controller
{
    /**
     * Controlador de la pagina principal
     */
        public function index() {
        try {
            // Hacemos la petición a la api
            $client = new Client();
            $response = $client->get('https://'.env('API_IP').':443/api/images', [
                'verify' => false
            ]);
            $data = json_decode($response->getBody(), true);
            $paths = [];
            foreach ($data as $ruta) {
                array_push($paths, $ruta);
            }
            $categories = Category::all();

            try {
                $json_string = file_get_contents('../landingValues.json');

                // Decodificamos la cadena JSON en un array PHP
                $data = json_decode($json_string, true);
                $data = $data['variableCategories'];
                $activeTags = array();
                foreach ($data as $key => $category) {
                    $activeTags[$key]['name'] = self::getCategories($category['category']);

                    $activeTags[$key]['products'] = self::getProducts($category['category'], $category['amount']);
                }

                Log::channel('marketify')->info('The home view has been loaded successfully.');

                return view('landing.index', [
                    'categories' => $categories,
                    'options_order' => HeaderVariables::$order_array,
                    'paths' => $paths,
                    'activeTags' => $activeTags
                ]);
            } catch (\Exception $e) {
                // Si hay un error al cargar el JSON, se carga el archivo de valores predeterminado
                $json_string = file_get_contents('../defaultValues.json');
                $data = json_decode($json_string, true);
                $data = $data['variableCategories'];
                $activeTags = array();
                foreach ($data as $key => $category) {
                    $activeTags[$key]['name'] = self::getCategories($category['category']);

                    $activeTags[$key]['products'] = self::getProducts($category['category'], $category['amount']);
                }

                Log::channel('marketify')->error('An error occurred while loading the home view: '.$e->getMessage());
                return view('landing.index', [
                    'categories' => $categories,
                    'options_order' => HeaderVariables::$order_array,
                    'paths' => $paths,
                    'activeTags' => $activeTags
                ])->with('error', 'An error occurred while loading the home view.');
            }
        } catch (\Exception $e) {
            Log::channel('marketify')->error('An error occurred while loading the home view: '.$e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while loading the home view.');
        }
    }


    /**
     * Obtener productos para la landing page.
     */
    public function getProducts($category, $limit)
    {
        try {
            Log::channel('marketify')->info('getProducts has been loaded successfully with this category: ', ["category" => $category]);

            $products = Product::getProducts($category, $limit);
            return $products;
        } catch (\Exception $e) {
            Log::channel('marketify')->error('An error occurred while loading getProducts() ' ,["e"=> $e->getMessage()]);
            return redirect()->back()->with('error', 'An error occurred while loading getProducts() ');
        }
    }

    /**
     * Obtener las categorias para la pagina principal
     */
    public function getCategories($id)
    {
        try {
            Log::channel('marketify')->info('getCategories has been loaded successfully with this category: ', ["id" => $id]);

            $categories = Category::getCategories($id);
            return $categories;
        } catch (\Exception $e) {
            Log::channel('marketify')->error('An error occurred while loading getCategories() ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while loading getCategories() ');
        }
    }
}
