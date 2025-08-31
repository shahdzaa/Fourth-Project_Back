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
                'message' => 'Dashboard data retrieved successfully.',
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
}
