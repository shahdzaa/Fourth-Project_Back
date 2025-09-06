<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BuildingResource;
use App\Models\Building;
use Illuminate\Http\Request;

class BuildingController extends Controller
{
    public function index()
    {
        $buildings = Building::all();
        return response()->json([
            'status' => 'success',
            'data'=>BuildingResource::collection($buildings),
        ]);
    }
    public function show(Building $building)
    {
        // تحميل العلاقات لتحسين الأداء
        $building->load('damageReports.committee', 'damageReports.foundation');

        // إرجاع الـ Resource مباشرة (هذه الطريقة يجب أن تعمل الآن)
        return new BuildingResource($building);
    }

}
