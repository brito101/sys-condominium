<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Unit;
use App\Models\UnitPeoples;
use App\Models\UnitPets;
use App\Models\UnitVehicles;
use Illuminate\Http\Request;

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

    public function addPerson()
    {
    }

    public function addVehicle()
    {
    }

    public function addPet()
    {
    }

    public function removePerson()
    {
    }

    public function removeVehicle()
    {
    }

    public function removePet()
    {
    }
}
