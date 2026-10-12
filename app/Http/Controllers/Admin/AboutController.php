<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class AboutController extends Controller
{
    public function edit()
    {
        return view('admin.about', ['about' => Setting::get('about'), 'mission' => Setting::get('mission')]);
    }

    public function update(Request $request)
    {
        $data = $request->validate(['about' => 'required|string|max:2000', 'mission' => 'required|string|max:1000']);
        Setting::put('about', $data['about']);
        Setting::put('mission', $data['mission']);

        return back()->with('ok', 'تم حفظ النبذة والهدف');
    }
}
