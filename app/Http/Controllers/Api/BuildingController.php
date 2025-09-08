<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateForm1Request;
use App\Http\Resources\BuildingResource;
use App\Models\Building;
use App\Models\DamageReport;
use Illuminate\Http\Request;

class BuildingController extends Controller
{
    public function index()
    {
        $buildings = Building::all();
        return response()->json([
            'data' => BuildingResource::collection($buildings),
        ]);
    }
    public function statsByTypeLevel1()
    {
        $types = ['بناء سكني', 'مستشفى', 'مدرسة', 'جامع', 'كنيسة'];
        $counts = [];

        foreach ($types as $type) {
            $counts[] = \App\Models\Building::where('type', $type)
                ->where('level_of_damage', 1)
                ->count();
        }

        return response()->json([
            'labels' => $types,
            'data' => $counts,
        ]);
    }
    public function statsLegalVsIllegalLevel4()
    {
        $legal = \App\Models\Building::where('is_legal', true)
            ->where('level_of_damage', 4)
            ->count();

        $illegal = \App\Models\Building::where('is_legal', false)
            ->where('level_of_damage', 4)
            ->count();

        return response()->json([
            'labels' => ['نظامية', 'غير نظامية'],
            'data' => [$legal, $illegal],
        ]);
    }

}
