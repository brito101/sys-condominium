<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Unit;
use App\Models\Warning;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

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
            if (!empty($value['photos'])) {
                $photos = explode(',', $value['photos']);

                foreach ($photos as $photo) {
                    $photoList[] = asset('storage/warnings/' . $photo);
                }

                $warnings[$key]['photos'] = $photoList;
            }
        }

        $array['list'] = $warnings;

        return $array;
    }

    public function setWarning(Request $request)
    {
        $array = ['error' => ''];

        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'property' => 'required',
        ]);

        if (!$validator->fails()) {
            $list = $request->input('list');

            $warning = new Warning();
            $warning->title = $request->title;
            $warning->unit_id = $request->property;
            $warning->status = 'in review';

            if ($list && \is_array($list)) {
                $photos = [];

                foreach ($list as $item) {
                    $url = explode('/', $item);
                    $photos[] = end($url);
                }

                $warning->photos = implode(',', $photos);
            }

            $warning->save();
        } else {
            $array['error'] = $validator->errors()->first();
        }

        return $array;
    }

    public function addWarningFile(Request $request)
    {
        $array = ['error' => ''];

        $validator = Validator::make($request->all(), [
            'photo' => 'required|file|mimes:png,jpg'
        ]);

        if (!$validator->fails()) {
            $file = $request->file('photo')->store('warnings');
            $array['photo'] = asset(Storage::url($file));
        } else {
            $array['error'] = $validator->errors()->first();
        }

        return $array;
    }
}
