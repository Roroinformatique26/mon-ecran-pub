<?php

namespace App\Http\Controllers;

use App\Models\Zone;
use Illuminate\Http\Request;

class ZoneController extends Controller
{
    public function index()
    {
        $zones = Zone::orderBy('name')->get();
        return view('zones.index', compact('zones'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:zones,name',
        ]);

        Zone::create(['name' => $request->name]);

        return redirect()->route('zones.index')
            ->with('success', 'Zone ajoutée.');
    }

    public function destroy(Zone $zone)
    {
        // Vérifier si des incidents utilisent cette zone
        if ($zone->incidents()->count() > 0) {
            return redirect()->route('zones.index')
                ->with('error', 'Impossible : des incidents sont rattachés à cette zone.');
        }

        $zone->delete();

        return redirect()->route('zones.index')
            ->with('success', 'Zone supprimée.');
    }
}