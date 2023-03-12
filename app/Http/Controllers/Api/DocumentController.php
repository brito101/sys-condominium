<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Document;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    public function getAll()
    {
        $array = ['error' => ''];

        $documents = Document::all();

        foreach ($documents as $key => $value) {
            $documents[$key]['url'] = asset('storage/' . $value['url']);
        }

        $array['list'] = $documents;

        return $array;
    }
}
