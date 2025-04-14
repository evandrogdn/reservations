<?php

namespace App\Http\Controllers;

use App\Models\Tables;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class TablesController extends Controller
{
    /**
     * @return Collection<int, Tables>
     */
    public function index(Request $request): JsonResponse
    {
        $query = Tables::query();

        if ($request->has('identification')) {
            $query->where('identification', 'like', '%' . $request->input('identification') . '%');
        }
        if ($request->has('places')) {
            $query->where('places', '>=', $request->input('places'));
        }

        return response()->json($query->get());
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return Tables
     */
    public function store(Request $request): Tables
    {
        $data = $request->validate([
            'identification' => 'required|string|unique:tables',
            'places' => 'required|integer',
        ]);

        return Tables::create($data);
    }

    /**
     * @param \App\Models\Tables $table
     * @return Tables
     */
    public function show(Tables $table): Tables
    {
        return $table;
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Tables $table
     * @return Tables
     */
    public function update(Request $request, Tables $table): Tables
    {
        $data = $request->validate([
            'identification' => 'sometimes|required|string',
            'places' => 'sometimes|required|integer',
        ]);

        $table->update($data);
        return $table;
    }

    /**
     * @param \App\Models\Tables $table
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function destroy(Tables $table)
    {
        $table->delete();

        return response()->json(['message' => 'Mesa removida com sucesso']);
    }
}
