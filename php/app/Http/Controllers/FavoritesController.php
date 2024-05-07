<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use Illuminate\Http\Request;

class FavoritesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request) {
        $user = auth()->user();
        $items = $user->favorites->map(function($item) {
            unset($item->pivot);
            $item->imageUrl = asset($item->imageUrl);
            return $item;
        });
        return response()->json($items);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id'
        ]);

        $favorite = Favorite::create([
            'user_id' => auth()->user()->id,
            'item_id' => $request->item_id
        ]);

        return response()->json([
            'id' => $favorite->id
        ], 201);
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
        Favorite::where('user_id', auth()->user()->id)->where('item_id', $id)->delete();
    }
}
