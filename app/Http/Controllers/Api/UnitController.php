<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Unit;
use App\Models\UnitPeoples;
use App\Models\UnitPets;
use App\Models\UnitVehicles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UnitController extends Controller
{
    public function getInfo($id)
    {
        $array = ['error' => ''];

        $unit = Unit::find($id);

        if ($unit) {
            $array['peoples'] = UnitPeoples::where('unit_id', $id)->get();
            $array['vehicles'] = UnitVehicles::where('unit_id', $id)->get();
            $array['pets'] = UnitPets::where('unit_id', $id)->get();
        } else {
            $array['error'] = "Propriedade Inexistente";
        }

        return $array;
    }

    public function addPerson($id, Request $request)
    {
        $array = ['error' => ''];

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'birth' => 'required|date'
        ]);

        if (!$validator->fails()) {
            $unitPeople = new UnitPeoples();
            $unitPeople->name = $request->name;
            $unitPeople->birth = $request->birth;
            $unitPeople->unit_id = $id;
            $unitPeople->save();
        } else {
            $array['error'] = $validator->errors()->first();
        }

        return $array;
    }

    public function addVehicle($id, Request $request)
    {
        $array = ['error' => ''];

        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'color' => 'required',
            'plate' => 'required'
        ]);

        if (!$validator->fails()) {
            $unitVehicle = new UnitVehicles();
            $unitVehicle->title = $request->title;
            $unitVehicle->color = $request->color;
            $unitVehicle->plate = $request->plate;
            $unitVehicle->unit_id = $id;
            $unitVehicle->save();
        } else {
            $array['error'] = $validator->errors()->first();
        }

        return $array;
    }

    public function addPet($id, Request $request)
    {
        $array = ['error' => ''];

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'race' => 'required'
        ]);

        if (!$validator->fails()) {
            $unitPet = new UnitPets();
            $unitPet->name = $request->name;
            $unitPet->race = $request->race;
            $unitPet->unit_id = $id;
            $unitPet->save();
        } else {
            $array['error'] = $validator->errors()->first();
        }

        return $array;
    }

    public function removePerson($id, Request $request)
    {
        $array = ['error' => ''];

        $id = $request->id;
        if ($id) {
            UnitPeoples::where('id', $id)->where('unit_id', $id)->delete();
        } else {
            $array['error'] = 'ID inexistente';
        }
        return $array;
    }

    public function removeVehicle($id, Request $request)
    {
        $array = ['error' => ''];

        $id = $request->id;
        if ($id) {
            UnitVehicles::where('id', $id)->where('unit_id', $id)->delete();
        } else {
            $array['error'] = 'ID inexistente';
        }
        return $array;
    }

    public function removePet($id, Request $request)
    {
        $array = ['error' => ''];

        $id = $request->id;
        if ($id) {
            UnitPets::where('id', $id)->where('unit_id', $id)->delete();
        } else {
            $array['error'] = 'ID inexistente';
        }
        return $array;
    }
}
