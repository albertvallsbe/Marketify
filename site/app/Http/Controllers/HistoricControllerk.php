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
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;

class HistoricControllerk extends Controller
{
    public function index()
    {
        $categories = Category::all();
        // $orders = Order::all();

        $id = auth()->user()->id;

        $orders = Order::searchOrderByUser($id);

        return view('order.historic', [
            'categories' => $categories,
            'orders' => $orders,
            'options_order' => HeaderVariables::$order_array,
        ]);
    }
    // CREAR VISTA DE DETALLES
    public function details($id)
    {
        $categories = Category::all();

        $order = Order::findOrFail($id);
        // COJO EL ID DEL PRODUCTO EN LA TABLA ORDER_ITEMS

        $orders = OrderItems::catchIdProduct($id);

        // FILTRO CON ESE ID EN LA TABLA DE PRODUCTS Y CONSIGO DICHOS PRODUCTOS
        $arrayOrder = [];
        $products = [];

        if ($orders->count() > 1) {
            // SI HAY MÁS DE 1 REGISTRO DEVUELVE UN ARRAY DE PRODUCTOS   
            foreach ($orders as $product) {
                array_push($arrayOrder, $product->product_id);
            }
            foreach ($arrayOrder as $idProduct) {
                $product = Product::getIdProducts($idProduct);
                array_push($products, $product);
            }
        } else {
            // SI HAY 1 REGISTRO DEVUELVE EL PRODUCTO
            $products = Product::findOrFail($orders);
        }

        // FILTRO POR EL SHOP_ID EN LA TABLA DE SHOPS Y DEVUELVO LA TIENDA
        $idShop = Order::catchIdShop($id);
        $shop = Shop::findOrFail($idShop);
        return view('order.historicalDetails', [
            'categories' => $categories,
            'order' => $order,
            'products' => $products,
            'shop' => $shop,
            'options_order' => HeaderVariables::$order_array,
        ]);
    }

    public function downloadFile(Request $request,$id)
    {   
        $categories = Category::all();
        $order = Order::findOrFail($id);
        // COJO EL ID DEL PRODUCTO EN LA TABLA ORDER_ITEMS

        $orders = OrderItems::catchIdProduct($id);

        // FILTRO CON ESE ID EN LA TABLA DE PRODUCTS Y CONSIGO DICHOS PRODUCTOS
        $arrayOrder = [];
        $products = [];

        if ($orders->count() > 1) {
            // SI HAY MÁS DE 1 REGISTRO DEVUELVE UN ARRAY DE PRODUCTOS   
            foreach ($orders as $product) {
                array_push($arrayOrder, $product->product_id);
            }
            foreach ($arrayOrder as $idProduct) {
                $product = Product::getIdProducts($idProduct);
                array_push($products, $product);
            }
        } else {
            // SI HAY 1 REGISTRO DEVUELVE EL PRODUCTO
            $products = Product::findOrFail($orders);
        }

        // FILTRO POR EL SHOP_ID EN LA TABLA DE SHOPS Y DEVUELVO LA TIENDA
        $idShop = Order::catchIdShop($id);
        $shop = Shop::findOrFail($idShop);
        if ($request->has('btn-download')) {

            // Crear una instancia de Dompdf
            $pdf = new Dompdf();
            // Cargar la vista
            $contenido = View::make('order.pdf ',[
                'order' => $order,
                'products' => $products,
                'shop' => $shop,
            ])->render();
            // Generar el contenido del PDF
            $pdf->loadHtml($contenido);

            // Renderizar el contenido del PDF
            $pdf->render();

            // Descargar el archivo PDF en el navegador del usuario
            return $pdf->stream('order '.$order->id.' pdf');
        }
    }
}
