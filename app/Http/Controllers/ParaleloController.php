<?php

namespace App\Http\Controllers;

use App\Models\Paralelo;
use Illuminate\Http\Request;

class ParaleloController extends Controller
{
    public function index() {
        return Paralelo::with('estudiantes')->get();
    }

    public function store(Request $request) {
        $data = $request->validate([
            'nombre' => 'required',
        ]);
        return Paralelo::create($data);
    }

    public function show($id) {
        return Paralelo::with('estudiantes')->findOrFail($id);
    }

    public function update(Request $request, $id) {
        $paralelo = Paralelo::findOrFail($id);
        $data = $request->validate([
            'nombre' => 'required',
        ]);
        $paralelo->update($data);
        return $paralelo;
    }

    public function destroy($id) {
        Paralelo::destroy($id);
        return response()->noContent();
    }
}
