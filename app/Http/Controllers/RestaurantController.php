<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Setting;
use App\Support\Loyalty;
use App\Support\Menu;

class RestaurantController extends Controller
{
    public function index()
    {
        return view('home', Menu::info() + [
            'menu'        => Menu::forSite(),
            'catalog'     => Menu::catalog(),
            'about'       => Setting::get('about'),
            'mission'     => Setting::get('mission'),
            'ratingAvg'   => round((float) Review::where('approved', true)->avg('rating'), 1),
            'ratingCount' => Review::where('approved', true)->count(),
            'loyalty'     => ['enabled' => Loyalty::enabled(), 'rate' => Loyalty::rate(), 'block' => Loyalty::block(), 'value' => Loyalty::value()],
        ]);
    }
}
