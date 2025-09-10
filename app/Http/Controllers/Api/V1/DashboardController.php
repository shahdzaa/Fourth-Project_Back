<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Building;
use App\Models\DamageReport;
use App\Models\Committee;
use App\Models\Engineer;
use Illuminate\Http\JsonResponse; // استيراد JsonResponse

class DashboardController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v1/dashboard",
     *     summary="جلب بيانات لوحة التحكم",
     *     tags={"Dashboard"},
     *     @OA\Response(
     *         response=200,
     *         description="تم جلب البيانات بنجاح",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Dashboard data retrieved successfully."),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="خطأ في الخادم"
     *     )
     * )
     */
    public function index(): JsonResponse
    {
        try {
            // --- إحصائيات رئيسية (Cards) ---
            $stats = [
                'severe_buildings_count' => Building::where('level_of_damage', 4)->count(),
                'minor_buildings_count'  => Building::where('level_of_damage', 1)->count(),
                'unique_reports_count'   => DamageReport::distinct('building_id')->count(),
                'committees_count'       => Committee::count(),
            ];

            // --- بيانات المخططات (Charts) ---
            $charts = [
                // 1) الأبنية المتضررة بشدة (Level 4) حسب النوع
                'severe_buildings_by_type' => Building::where('level_of_damage', 4)
                    ->selectRaw('type, COUNT(*) as total')
                    ->groupBy('type')
                    ->pluck('total', 'type'),

                // 2) عدد العائلات في كل حي
                'families_by_neighbourhood' => Building::selectRaw('neighbourhood_id, SUM(number_of_families_before_departure) as total_families')
                    ->groupBy('neighbourhood_id')
                    ->with('neighbourhood:id,name') // جلب اسم الحي فقط
                    ->get()
                    ->map(function ($item) {
                        return [
                            'neighbourhood_name' => $item->neighbourhood->name ?? 'غير محدد',
                            'total_families' => (int) $item->total_families,
                        ];
                    }),

                // 3) المهندسون حسب التخصص
                'engineers_by_specialization' => Engineer::selectRaw('specialization, COUNT(*) as total')
                    ->groupBy('specialization')
                    ->pluck('total', 'specialization'),

                // 4) الأبنية حسب درجة الضرر
                'buildings_by_damage_level' => Building::selectRaw('level_of_damage, COUNT(*) as total')
                    ->groupBy('level_of_damage')
                    ->pluck('total', 'level_of_damage'),

                // 5) الأبنية المتضررة بشدة (Level 4) حسب النمط الإنشائي
                'severe_buildings_by_structural_pattern' => Building::where('level_of_damage', 4)
                    ->selectRaw('structural_pattern, COUNT(*) as total')
                    ->groupBy('structural_pattern')
                    ->pluck('total', 'structural_pattern'),

                // 6) الأبنية النظامية وغير النظامية (Level 4)
                'severe_legal_status' => [
                    'legal'   => Building::where('level_of_damage', 4)->where('is_legal', true)->count(),
                    'illegal' => Building::where('level_of_damage', 4)->where('is_legal', false)->count(),
                ],
            ];

            // تجميع كل البيانات في مصفوفة واحدة
            $data = [
                'stats' => $stats,
                'charts' => $charts,
            ];

            // إرجاع استجابة JSON منظمة
            return response()->json([
                'data' => $data,
            ], 200);

        } catch (\Exception $e) {
            // في حال حدوث أي خطأ، يتم إرجاع رسالة خطأ
            return response()->json([
                'message' => 'An error occurred while fetching dashboard data.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    public function legalVsIllegalLevel4(): \Illuminate\Http\JsonResponse
{
    $legal = \App\Models\Building::where('level_of_damage', 4)->where('is_legal', true)->count();
    $illegal = \App\Models\Building::where('level_of_damage', 4)->where('is_legal', false)->count();

    return response()->json([
        'labels' => ['نظامية', 'غير نظامية'],
        'data' => [$legal, $illegal],
    ]);
}
public function severeBuildingsByType(): \Illuminate\Http\JsonResponse
{
    $data = \App\Models\Building::where('level_of_damage', 4)
        ->selectRaw('type, COUNT(*) as total')
        ->groupBy('type')
        ->pluck('total', 'type');

    // تحويل الـ pluck إلى مصفوفات labels و data
    $labels = [];
    $values = [];
    foreach ($data as $type => $count) {
        $labels[] = $type;
        $values[] = $count;
    }

    return response()->json([
        'labels' => $labels,
        'data' => $values,
    ]);
}
public function buildingsByDamageLevel(): \Illuminate\Http\JsonResponse
{
    $data = \App\Models\Building::selectRaw('level_of_damage, COUNT(*) as total')
        ->groupBy('level_of_damage')
        ->orderBy('level_of_damage')
        ->pluck('total', 'level_of_damage');

    $labels = [];
    $values = [];
    foreach ($data as $level => $count) {
        $labels[] = $level;
        $values[] = $count;
    }

    return response()->json([
        'labels' => $labels,
        'data' => $values,
    ]);
}

public function familiesByNeighbourhood(): \Illuminate\Http\JsonResponse
{
    $data = \App\Models\Building::selectRaw('neighbourhood_id, SUM(number_of_families_before_departure) as total_families')
        ->groupBy('neighbourhood_id')
        ->with('neighbourhood:id,name')
        ->get();

    $labels = [];
    $values = [];
    foreach ($data as $item) {
        $labels[] = $item->neighbourhood->name ?? 'غير محدد';
        $values[] = (int) $item->total_families;
    }

    return response()->json([
        'labels' => $labels,
        'data' => $values,
    ]);
}
public function engineersBySpecialization(): \Illuminate\Http\JsonResponse
{
    $data = \App\Models\Engineer::selectRaw('specialization, COUNT(*) as total')
        ->groupBy('specialization')
        ->get();

    return response()->json($data);
}
public function stats(): \Illuminate\Http\JsonResponse
{
    $stats = [
        'severe_buildings_count' => \App\Models\Building::where('level_of_damage', 4)->count(),
        'minor_buildings_count'  => \App\Models\Building::where('level_of_damage', 1)->count(),
        'unique_reports_count'   => \App\Models\DamageReport::distinct('building_id')->count(),
        'committees_count'       => \App\Models\Committee::count(),
    ];

    return response()->json($stats);
}
public function latestCases(): \Illuminate\Http\JsonResponse
{
    $cases = \App\Models\Building::with('neighbourhood')
    ->orderBy('created_at', 'desc')
    ->take(4)
    ->get()
    ->map(function($item) {
        $damage_status = match((int)$item->level_of_damage) {
            0 => 'سليم',
            1 => 'ضرر بسيط',
            2 => 'ضرر متوسط',
            3 => 'ضرر شديد',
            4 => 'خطر انهيار',
            default => 'غير محدد'
        };
        return [
            'building_number' => $item->external_id ?? $item->id,
            'neighbourhood' => $item->neighbourhood->name ?? 'غير محدد',
            'damage_status' => $damage_status,
        ];
    });

return response()->json($cases);
}
public function committees(): \Illuminate\Http\JsonResponse
{
    $committees = \App\Models\Committee::with([
        'engineers', // جميع المهندسين في اللجنة
        'neighbourhoods' // جميع الأحياء المعززة للجنة
    ])
    ->orderBy('created_at', 'desc')
    ->take(4)
    ->get()
    ->map(function($committee) {
        // جلب المدير
        $manager = $committee->engineers()->wherePivot('is_manager', true)->first();
        // جلب المهندسين غير المديرين
        $otherEngineers = $committee->engineers()->wherePivot('is_manager', false)->take(2)->get();

        return [
            'manager' => $manager ? $manager->first_name . ' ' . $manager->last_name : 'غير محدد',
            'engineer2' => $otherEngineers[0]->first_name . ' ' . $otherEngineers[0]->last_name ?? 'غير محدد',
            'engineer3' => $otherEngineers[1]->first_name . ' ' . $otherEngineers[1]->last_name ?? 'غير محدد',
            'neighbourhood' => $committee->neighbourhoods->pluck('name')->implode(', ') ?: 'غير محدد',
        ];
    });

    return response()->json($committees);
}
public function dangerousBuildings(): \Illuminate\Http\JsonResponse
{
    $buildings = \App\Models\Building::with('neighbourhood')
        ->where('level_of_damage', 4)
        ->orWhere('is_materials_from_the_neighborhood', true)
        ->orderBy('created_at', 'desc')
        ->take(10)
        ->get()
        ->map(function($item) {
            return [
                'building_number' => $item->external_id ?? $item->id,
                'neighbourhood' => $item->neighbourhood->name ?? 'غير محدد',
            ];
        });

    return response()->json($buildings);
}
public function storeCommittee(Request $request)
{
    $request->validate([
        'manager_id' => 'required|exists:engineers,id',
        'engineer2_id' => 'required|exists:engineers,id',
        'engineer3_id' => 'required|exists:engineers,id',
        'neighbourhood_id' => 'required|exists:neighbourhoods,id',
    ]);

    // إنشاء اللجنة
    $committee = \App\Models\Committee::create([
        'user_id' => optional(auth()->user())->id ?? 1, // أو أي مستخدم افتراضي
    ]);

    // ربط المهندسين
    $committee->engineers()->attach($request->manager_id, ['is_manager' => true]);
    $committee->engineers()->attach($request->engineer2_id, ['is_manager' => false]);
    $committee->engineers()->attach($request->engineer3_id, ['is_manager' => false]);

    // ربط الحي
    $committee->neighbourhoods()->attach($request->neighbourhood_id);

    return response()->json(['success' => true, 'committee_id' => $committee->id]);
}
}
