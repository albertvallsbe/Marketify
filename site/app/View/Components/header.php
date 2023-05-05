<?php

namespace App\View\Components;

use Closure;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

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
}
