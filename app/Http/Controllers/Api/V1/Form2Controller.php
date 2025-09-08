<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\BuildingF2Resource;
use App\Http\Resources\BuildingResource;
use App\Models\Building;
use Illuminate\Http\Request;

class Form2Controller extends Controller
{
    public function index()
    {
        $buildings = Building::whereHas('damageReports', function ($query) {
            $query->where('report_number', 2);
        })->get();

        return response()->json([
            'data' => BuildingResource::collection($buildings),
        ]);
    }
    public function editData($id)
    {
        $building = Building::with(['damageReports'])->findOrFail($id);

        return response()->json([
            'building' => new BuildingF2Resource($building),
            'damage_reports' => $building->damageReports,
        ]);
    }

}
