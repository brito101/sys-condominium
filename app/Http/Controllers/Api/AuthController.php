<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $array = ['error' => ''];

        $validator = Validator::make($request->all(), [
            'document_person' => 'required|max:11',
            'password' => 'required',
        ]);

        if (!$validator->fails()) {
            $token = auth()->attempt([
                'document_person' => $request->input('document_person'),
                'password' => $request->input('password'),
            ]);

            if (!$token) {
                $array['error'] = 'Credenciais inválidas';
                return $array;
            }

            $array['token'] = $token;
            $user = auth()->user();
            $array['user'] = $user;

            $properties = Unit::select(['id', 'name'])->where('owner', $user->id)->get();
            $array['user']['properties'] = $properties;
        } else {
            $array['error'] = $validator->errors()->first();
            return $array;
        }
        return $array;
    }

    public function register(Request $request)
    {
        $array = ['error' => ''];

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,except,id,deleted_at,NULL',
            'document_person' => 'required|digits:11|unique:users,document_person,except,id,deleted_at,NULL',
            'password' => 'required',
            'password_confirm' => 'required|same:password'
        ]);


        if (!$validator->fails()) {
            $password = $request->input('password');
            $data = $request->all();
            $hash = \password_hash($password, \PASSWORD_DEFAULT);
            $data['password'] = $hash;
            $user = new User();
            $user->create($data);

            $token = auth()->attempt([
                'document_person' => $request->input('document_person'),
                'password' => $password,
            ]);

            if (!$token) {
                $array['error'] = 'Ocorreu um erro';
                return $array;
            }

            $array['token'] = $token;
            $user = auth()->user();
            $array['user'] = $user;

            $properties = Unit::select(['id', 'name'])->where('owner', $user->id)->get();
            $array['user']['properties'] = $properties;
        } else {
            $array['error'] = $validator->errors()->first();
            return $array;
        }

        return $array;
    }

    public function validateToken(Request $request)
    {
        $array = ['error' => ''];

        $user = auth()->user();
        $array['user'] = $user;
        $properties = Unit::select(['id', 'name'])->where('owner', $user->id)->get();
        $array['user']['properties'] = $properties;

        return $array;
    }

    public function logout()
    {
        $array = ['error' => ''];

        $user = auth()->logout();

        return $array;
    }

    public function unauthorized()
    {
        return response()->json([
            'error' => 'Não autorizado'
        ], 401);
    }
}
