<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Billet;
use App\Models\Unit;
use Illuminate\Http\Request;

class BilletController extends Controller
{
    public function getAll()
    {
        $array = ['error' => ''];

        $user = \auth()->user();
        $units = Unit::where('owner', $user->id)->get();
        $billets = Billet::whereIn('unit_id', $units->pluck('id'))->get();

        foreach ($billets as $key => $value) {
            $billets[$key]['url'] = asset('storage/' . $value['url']);
        }

        $array['list'] = $billets;

        return $array;
    }
}
