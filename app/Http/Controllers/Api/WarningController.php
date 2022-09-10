<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Unit;
use App\Models\Warning;
use Illuminate\Http\Request;

class WarningController extends Controller
{
    public function getMyWarnings()
    {
        $array = ['error' => ''];
        $user = \auth()->user();
        $units = Unit::where('owner', $user->id)->get();
        $warnings = Warning::whereIn('unit_id', $units->pluck('id'))
            ->orderBy('created_at', 'desc')
            ->orderBy('id', 'desc')
            ->get();

        foreach ($warnings as $key => $value) {
            $photoList = [];
            $photos = explode(',', $value['photos']);

            foreach ($photos as $photo) {
                $photoList[] = asset('storage/' . $photo);
            }

            $warnings[$key]['photos'] = $photoList;
        }

        $array['list'] = $warnings;

        return $array;
    }

    public function setWarning()
    {
        $array = ['error' => ''];
        $user = \auth()->user();

        return $array;
    }

    public function addWarningFile()
    {
        $array = ['error' => ''];
        $user = \auth()->user();

        return $array;
    }
}
