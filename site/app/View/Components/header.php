<?php

namespace App\View\Components;

use Closure;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class header extends Component
{
    public function __construct(Request $request)
    {
        $order_request = $request->order ?? "name_asc";
        session(['request_order' => $order_request]);
        session(['request_search' => $request->search]);
        session(['request_categories' => $request->filter]);
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.header');
    }

    public function checkUserShop(): boolean {
        $id = Auth::user()->id;
        if ($id) {
            if (Shop::checkUser($id)) {
                return true;
            }else {
                return false;
            } 
        } else{
            return false;
        }
    }
}
