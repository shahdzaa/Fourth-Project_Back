<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreForm1Request;
use App\Http\Requests\UpdateForm1Request;
use App\Http\Resources\BuildingResource;
use App\Models\Building;
use App\Models\DamageReport;
use Illuminate\Http\Request;


class Form1Controller extends Controller

{
    public function index()
    {
        $buildings = Building::all();
        return response()->json([
            'data'=>BuildingResource::collection($buildings),
        ]);
    }
    public function editData($id)
    {
        $building = Building::with(['damageReports'])->findOrFail($id);

        return response()->json([
            'building' => $building,
            'damage_reports' => $building->damageReports,
        ]);
    }
    public function update(UpdateForm1Request $request, $id)
    {
        $request->validated();
        $building = Building::findOrFail($id);
        // تعديل بيانات البناء
        $building->update($request->input('building'));
        // تعديل تقارير الضرر
        foreach ($request->input('damage_reports') as $reportData) {
            $report = DamageReport::find($reportData['id']);
            if ($report && $report->building_id == $building->id) {
                $report->update($reportData);
            }
        }

        return response()->json(['message' => 'تم التعديل بنجاح']);
    }
    public function store(StoreForm1Request $request, Building $building)
    {
        // --- أولاً: إنشاء تقرير الضرر (الكود الحالي) ---
        $reportData = $request->validated('damage_report');
        $report = new DamageReport($reportData);
        $report->building_id = $building->id;
        $report->save();

        // --- ثانياً: تحديث بيانات البناء (الإضافة الجديدة) ---
        // احصل على بيانات البناء التي تم التحقق منها فقط
        $buildingData = $request->validated('building');

        // تحقق مما إذا كانت هناك بيانات لتحديثها لتجنب استعلام غير ضروري
        if (!empty($buildingData)) {
            $building->update($buildingData);
        }

        // --- ثالثاً: إرجاع الاستجابة ---
        return response()->json([
            'message' => 'تم إنشاء التقرير وتحديث بيانات البناء بنجاح',
            'report' => $report,
            'building' => $building->fresh() // إرجاع بيانات البناء المحدثة
        ]);
    }

}
