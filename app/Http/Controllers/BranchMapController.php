<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use Illuminate\Http\Request;

class BranchMapController extends Controller
{
    public function peta()
    {
        $branches = Branch::select('id', 'nama', 'alamat', 'telepon', 'type', 'latitude', 'longitude')->get();

        return view('peta', compact('branches'));
    }
    // BranchController
public function update(Request $request, Branch $branch)
{
    $branch->update($request->validate([
        'nama'      => 'required|string',
        'alamat'    => 'nullable|string',
        'telepon'   => 'nullable|string',
        'type'      => 'required|in:toko,bengkel',
        'latitude'  => 'required|numeric|between:-90,90',
        'longitude' => 'required|numeric|between:-180,180',
    ]));
    return response()->json(['ok' => true]);
}}