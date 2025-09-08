<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreForm2Request; // <-- استخدام الـ Request الجديد
use App\Http\Resources\BuildingF2Resource;
use App\Models\Building;
use App\Models\DamageReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Form2Controller extends Controller
{
    public function index()
    {
        $buildings = Building::whereHas('damageReports', function ($query) {
            $query->where('report_number', 2);
        })->get();

        return response()->json([
            'data' => BuildingF2Resource::collection($buildings),
        ]);
    }
    /**
     * جلب البيانات لعرضها في النموذج
     * سأبقيها كما هي حالياً لتوفير البيانات الكاملة للواجهة الأمامية
     */
    public function editData($id)
    {
        $building = Building::with([
            'neighbourhood',
            'damageReports.foundation',
            'damageReports.committee'
        ])->findOrFail($id);

        return response()->json([
            'building' => $building,
        ]);
    }

    /**
     * تحديث البيانات بناءً على الحقول المحددة لـ Form2
     */
    public function update(StoreForm2Request $request, $id)
    {
        $request->validated();

        $building = Building::findOrFail($id);
        $building->update($request->input('building'));

        $damageReports = $request->input('damage_reports') ?? [];

        foreach ($damageReports as $reportData) {
            $report = DamageReport::find($reportData['id']);
            if ($report && $report->building_id == $building->id) {
                $report->update($reportData);
            }
        }

        return response()->json(['message' => 'تم التعديل بنجاح']);
    }

    /**
     * إنشاء مبنى جديد وتقرير ضرر واحد مرتبط به بناءً على حقول Form2.
     */
    public function store(StoreForm2Request $request, $id)
    {
        $validated = $request->validated();

        $building = Building::findOrFail($id);

        // تحديث بيانات البناء
        $buildingData = $validated['building'] ?? [];
        if (!empty($buildingData)) {
            $building->update($buildingData);
        }

        // إنشاء تقارير أضرار جديدة
        $damageReportsData = $validated['damage_reports'] ?? [];
        $createdReports = [];

        foreach ($damageReportsData as $reportData) {
            $report = new DamageReport($reportData);
            $report->building_id = $building->id;
            $report->save();
            $createdReports[] = $report;
        }
        return response()->json([
            'message' => 'تم تحديث البناء وإنشاء التقارير بنجاح',
            'building' => $building->fresh(),
            'created_reports' => $createdReports
        ]);
    }
    public function updateAndCleanReports(Request $request, $id)
    {
        $validated = $request->validate([
            'level_of_damage' => 'required|in:0,1,2,3,4',
            'is_materials_from_the_neighborhood' => 'required|boolean',
        ]);

        DB::transaction(function () use ($id, $validated) {
            $building = Building::findOrFail($id);

            $building->damageReports()->where('report_number', 1)->delete();

            $building->update($validated);
        });

        return response()->json(['message' => 'تم تعديل البناية وحذف التقارير المرتبطة بها بنجاح']);
    }

}

