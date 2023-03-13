<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CardController extends Controller
{
    private $order = 'name_asc';

    private $order_array = [
        'name_asc' => 'Name Ascendent',
        'name_desc' => 'Name Descendent',
        'price_asc' => 'Price Ascendent',
        'price_desc' => 'Price Descendent'
    ];

    public function index() {
        $order_data = [
            'order_array' => $this->order_array,
            'order' => $this->order,
        ];
        return view('card.index', $order_data);
    }

    public function add(Request $request) {
    }
}
