<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LostFound;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LostFoundController extends Controller
{
    public function getAll()
    {
        $array = ['error' => ''];

        $lost = LostFound::where('status', 'lost')
            ->orderBy('created_at', 'desc')
            ->orderBy('id', 'desc')
            ->get();

        foreach ($lost as $key => $value) {
            if (!empty($value['photo'])) {
                $lost[$key]['photo'] = asset('storage/lost-found/' . $value['photo']);
            }
        }

        $array['lost'] = $lost;

        $found = LostFound::where('status', 'found')
            ->orderBy('created_at', 'desc')
            ->orderBy('id', 'desc')
            ->get();

        foreach ($found as $key => $value) {
            if (!empty($value['photo'])) {
                $found[$key]['photo'] = asset('storage/lost-found/' . $value['photo']);
            }
        }

        $array['found'] = $found;

        return $array;
    }

    public function insert(Request $request)
    {
        $array = ['error' => ''];

        $validator = Validator::make($request->all(), [
            'location' => 'required',
            'description' => 'required',
            'photo' => 'required|file|mimes:png,jpg',
        ]);

        if (!$validator->fails()) {
            $file = $request->file('photo')->store('lost-found');
            $fileName = explode('lost-found/', $file);

            $lostFound = new LostFound();
            $lostFound->location = $request->location;
            $lostFound->description = $request->description;
            $lostFound->photo =  $fileName[1];
            $lostFound->save();
        } else {
            $array['error'] = $validator->errors()->first();
        }

        return $array;
    }

    public function update($id, Request $request)
    {
        $array = ['error' => ''];

        $status = $request->input('status');

        if ($status && \in_array($status, ['lost', 'recovered'])) {
            $lostFound = LostFound::find($id);
            if ($lostFound) {
                $lostFound->status = $status;
                $lostFound->update();
            } else {
                $array['error'] = 'Item inválido';
            }
        } else {
            $array['error'] = 'Status inválido';
        }

        return $array;
    }
}
