<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Engineer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EngineerController extends Controller
{

    public function bySpecialization()
    {
        $committees = \App\Models\Committee::with(['engineers', 'neighbourhoods'])->get();

        // يمكنك تخصيص البيانات حسب الحاجة للفرونت
        $result = $committees->map(function($committee) {
            return [
                'manager' => $committee->engineers[0]->name ?? null,
                'engineer2' => $committee->engineers[1]->name ?? null,
                'engineer3' => $committee->engineers[2]->name ?? null,
                'neighbourhoods' => $committee->neighbourhoods->pluck('name')->toArray(),
            ];
        });

        return response()->json($result);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
