<?php

namespace App\Http\Controllers\Web\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class ModelAndMarkController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getMarks(Request $request): JsonResponse
    {
        $query = DB::table('mark')->select('name as text', 'id as mark_id', 'id');

        if ($request->search) {
            $query->where('name', 'LIKE', "%{$request->search}%")
                ->orWhere('cyrillic-name', 'LIKE', "%{$request->search}%");
        }

        return response()->json([
            'status' => true,
            'data' => $query->paginate(perPage: 30, page: $request->page)
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getModelsByMark(Request $request): JsonResponse
    {
        $request->validate(['mark_id' => 'required|string']);

        $query = DB::table('model')->select('name as text', 'id')
            ->where('mark_id',$request->mark_id);

        if ($request->search) {
            $query->where(function ($query) use ($request) {
                $query->where('name', 'LIKE', "%{$request->search}%")
                    ->orWhere('cyrillic-name', 'LIKE', "%{$request->search}%");
            });
        }

        return response()->json([
            'status' => true,
            'data' => $query->paginate(perPage: 30, page: $request->page)
        ]);
    }
}