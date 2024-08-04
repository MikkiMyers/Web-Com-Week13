<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shop;
use Illuminate\View\View;

class ShopController extends Controller
{

    public function list()
    {
        $shops = Shop::all();

        return view('shops.list', [
            'title' => 'Shop : List',
            'shops' => $shops,
        ]);
    }
    public function show($shop){
        $shop = Shop::where('code',$shop)->firstOrFail();
        return view('shops.view',[
            'title'=> ' Shop :View',
            'shop' => $shop,
        ]);
    }
}