<?php

namespace App\Http\Controllers;

use App\Models\Paralelo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ParaleloController extends Controller
{
    public function index() {
        Log::info('ParaleloController@index called');
        return Paralelo::with('estudiantes')->get();
    }

    public function store(Request $request) {
        Log::info('ParaleloController@store called', ['request' => $request->all()]);
        $data = $request->validate([
            'nombre' => 'required',
        ]);
        $paralelo = Paralelo::create($data);
        Log::info('Paralelo created', ['paralelo' => $paralelo]);
        return $paralelo;
    }

    public function show($id) {
        Log::info("ParaleloController@show called with id: {$id}");
        return Paralelo::with('estudiantes')->findOrFail($id);
    }

    public function update(Request $request, $id) {
        Log::info("ParaleloController@update called with id: {$id}", ['request' => $request->all()]);
        $paralelo = Paralelo::findOrFail($id);
        $data = $request->validate([
            'nombre' => 'required',
        ]);
        $paralelo->update($data);
        Log::info('Paralelo updated', ['paralelo' => $paralelo]);
        return $paralelo;
    }

    public function destroy($id) {
        Log::info("ParaleloController@destroy called with id: {$id}");
        Paralelo::destroy($id);
        Log::info("Paralelo with id {$id} deleted");
        return response()->noContent();
    }
}
