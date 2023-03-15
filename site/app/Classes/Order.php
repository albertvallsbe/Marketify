<?php

namespace App\Classes;

class Order
{    
    public static $order = 'name_asc';

    public static $order_array = [
        'name_asc' => 'Name Ascendent',
        'name_desc' => 'Name Descendent',
        'price_asc' => 'Price Ascendent',
        'price_desc' => 'Price Descendent'
    ];
}
