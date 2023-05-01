<?php
namespace App\Http\Controllers;

    use App\Classes\Order;
    use App\Models\Product;
    use App\Models\Category;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Log;

    class LandingController extends Controller
    {
        public function index()
        {
            try {

                $categories = Category::all();
                // Leemos el archivo JSON
                $json_string = file_get_contents('js/datos.json');

                // Decodificamos la cadena JSON en un array PHP
                $data = json_decode($json_string, true);
                $data = $data['variableCategories'];
                $activeTags = array();
                foreach($data as $key => $category) {
                    $activeTags[$key]['name'] = self::getCategories($category['category']);
                    $activeTags[$key]['products'] = self::getProducts($category['category'],$category['amount']);
                    // array_push($activeTags, $tag['tag']);
                }

                //  dd($activeTags[0]['products'][0]['tag']);
                // dd($activeTags[0]);
                // Mostramos el contenido del array

                Log::channel('marketify')->info('The home view has been loaded successfully.');

                return view('landing.index', [
                    'categories' => $categories,
                    'options_order' => Order::$order_array,
                    'activeTags' => $activeTags
                ]);
            } catch (\Exception $e) {
                Log::channel('marketify')->error('An error occurred while loading the home view: '.$e->getMessage());
                return redirect()->back()->with('error', 'An error occurred while loading the home view.');
            }
        }
        public function getProducts($category,$limit)
        {
            try{
                Log::channel('marketify')->info('getProducts has been loaded successfully with this category: '.$category);

                return Product::query()
                ->join('category_product', 'category_product.product_id', '=', 'products.id')
                ->select('products.*')
                ->where('category_product.category_id', '=', $category)
                ->limit($limit)
                ->get();
            } catch (\Exception $e) {
                Log::channel('marketify')->error('An error occurred while loading getProducts() '.$e->getMessage());
                return redirect()->back()->with('error', 'An error occurred while loading getProducts() ');
            }
        }
        public function getCategories($id)
        {
            try {
                Log::channel('marketify')->info('getCategories has been loaded successfully with this category: '.$id);

                return Category::query()
                ->join('category_product', 'category_product.category_id', '=', 'categories.id')
                ->select('categories.*')
                ->where('category_product.category_id', '=', $id)
                ->first()
                ;
            } catch (\Exception $e) {
                Log::channel('marketify')->error('An error occurred while loading getCategories() '.$e->getMessage());
                return redirect()->back()->with('error', 'An error occurred while loading getCategories() ');
            }
        }

    }
