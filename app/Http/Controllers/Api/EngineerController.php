<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Engineer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EngineerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function bySpecialization()
    {
        $data = Engineer::select('specialization', DB::raw('count(*) as total'))
            ->groupBy('specialization')
            ->get();

        return response()->json($data);
    }
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
