<?php

namespace App\Http\Controllers;


use Dompdf\Dompdf;
use App\Models\Shop;
use App\Models\Order;
use App\Models\Product;
use App\Models\Category;
use App\Models\OrderItems;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Classes\HeaderVariables;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;

class HistoricController extends Controller
{
    /**
     * Retorna historic de compres de l'usuari
     */
    public function index()
    {
        try{
            $categories = Category::all();

            $id = auth()->id();

            $orders = Order::searchOrderByUser($id);

            Log::channel('marketify')->info('Historic of orders generated.');

            return view('order.historic', [
                'categories' => $categories,
                'orders' => $orders,
                'options_order' => HeaderVariables::$order_array,
            ]);

        }catch (\Exception $e) {

            Log::channel('marketify')->info('The email has not been sent.', ["e" => $e->getMessage()]);
            return redirect(route('order.historic'));
        }
    }
    /**
     * Crear vista de detalles
     */
    public function details($id)
    {
        try{
            $categories = Category::all();

            /**
             * COJO EL ID DEL PRODUCTO EN LA TABLA ORDER_ITEMS
             */
            $order = Order::findOrFail($id);
            $userID = Shop::findUserShopID($order->shop_id);
            if ($order->user_id == auth()->id() || $userID == auth()->id) {
                $orders = OrderItems::catchIdProduct($id);
                /**
                 * FILTRO CON ESE ID EN LA TABLA DE PRODUCTS Y CONSIGO DICHOS PRODUCTOS
                 */
                $arrayOrder = [];
                $products = [];

                if ($orders->count() > 1) {
                    /**
                     * SI HAY MÁS DE 1 REGISTRO DEVUELVE UN ARRAY DE PRODUCTOS
                    */
                    foreach ($orders as $product) {
                        array_push($arrayOrder, $product->product_id);
                    }
                    foreach ($arrayOrder as $idProduct) {
                        $product = Product::getIdProducts($idProduct);
                        array_push($products, $product);
                    }
                } else {
                    /**
                     * SI HAY 1 REGISTRO DEVUELVE EL PRODUCTO
                     */
                    $products = Product::findOrFail($orders);
                }

                /**
                 * FILTRO POR EL SHOP_ID EN LA TABLA DE SHOPS Y DEVUELVO LA TIENDA
                 */
                $idShop = Order::catchIdShop($id);
                $shop = Shop::findOrFail($idShop);

                Log::channel('marketify')->info('Generated order history details.');
                return view('order.historicalDetails', [
                    'categories' => $categories,
                    'order' => $order,
                    'products' => $products,
                    'shop' => $shop,
                    'options_order' => HeaderVariables::$order_array,
                ]);
            } else {
                Log::channel('marketify')->info('Redirect to historical index');
                return redirect(route('historical.index'));
            }
        }catch (\Exception $e) {

            Log::channel('marketify')->info('Problems with historics details.', ["e" => $e->getMessage()]);
            return redirect(route('historical.index'));

        }
    }

    public function downloadFile(Request $request,$id)
    {
        try{
            $categories = Category::all();
            $order = Order::findOrFail($id);
            /**
             *  COJO EL ID DEL PRODUCTO EN LA TABLA ORDER_ITEMS
             */

            $orders = OrderItems::catchIdProduct($id);

            /**
             * FILTRO CON ESE ID EN LA TABLA DE PRODUCTS Y CONSIGO DICHOS PRODUCTOS
             */
            $arrayOrder = [];
            $products = [];

            if ($orders->count() > 1) {
                /**
                 * SI HAY MÁS DE 1 REGISTRO DEVUELVE UN ARRAY DE PRODUCTOS
                 */
                foreach ($orders as $product) {
                    array_push($arrayOrder, $product->product_id);
                }
                foreach ($arrayOrder as $idProduct) {
                    $product = Product::getIdProducts($idProduct);
                    array_push($products, $product);
                }
            } else {
                /**
                 * SI HAY 1 REGISTRO DEVUELVE EL PRODUCTO
                 */
                $products = Product::findOrFail($orders);
            }

            /** *
             *  FILTRO POR EL SHOP_ID EN LA TABLA DE SHOPS Y DEVUELVO LA TIENDA
             */
            $idShop = Order::catchIdShop($id);
            $shop = Shop::findOrFail($idShop);
            if ($request->has('btn-download')) {

                /**
                 * CREO UNA INSTANCIA DEL DOMPDF
                 */
                $pdf = new Dompdf();
                /**
                 *  CARGO LA VISTA
                 */
                $contenido = View::make('order.pdf ',[
                    'order' => $order,
                    'products' => $products,
                    'shop' => $shop,
                ])->render();
                /**
                 *  GENERO EL CONTENIDO DEL PDF
                 */
                $pdf->loadHtml($contenido);

                /**
                 * RENDERIZO EL CONTENIDO DEL PDF
                 */
                $pdf->render();

                /**
                 * DESCARGO EL ARCHIVO PDF EN EL NAVEGADOR DEL USUARIO
                 */

                Log::channel('marketify')->info('Generado pdf del order.');

                return $pdf->stream('order '.$order->id.' pdf');
            }

        }catch (\Exception $e) {

            Log::channel('marketify')->info('Problems with historic order.', ["e" => $e->getMessage()]);
            return redirect(route('order.index'));

        }
    }
}
