<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ItemsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $sortBy = $request->sortBy;
        $search = $request->title;
        $query = Item::query();

        if ($sortBy) {
            $sortDirection = Str::startsWith($sortBy, '-') ? 'DESC' : 'ASC';

            $sortBy = ltrim($sortBy, '-');
            $items = $query->orderBy($sortBy, $sortDirection)->get();

        }

        if ($search) {
            if (Str::startsWith($search, '*')) {
                $search = '%' . ltrim($search, '*');
            }

            if (Str::endsWith($search, '*')) {
                $search = rtrim($search, '*') . '%';
            }
            $query->where('title', 'LIKE', $search);
        }
        $items = $query->get();

        return $items->toJson();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

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
