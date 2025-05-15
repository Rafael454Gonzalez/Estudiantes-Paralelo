<?php

namespace App\Http\Controllers;

use App\Models\Estudiante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EstudianteController extends Controller
{
    public function index() {
        Log::info('EstudianteController@index called');
        return Estudiante::with('paralelo')->get();
    }

    public function store(Request $request) {
        Log::info('EstudianteController@store called', ['request' => $request->all()]);
        $data = $request->validate([
            'nombre' => 'required',
            'cedula' => 'required|unique:estudiantes',
            'correo' => 'required|email|unique:estudiantes',
            'paralelo_id' => 'required|exists:paralelos,id',
        ]);
        $estudiante = Estudiante::create($data);
        Log::info('Estudiante created', ['estudiante' => $estudiante]);
        return $estudiante;
    }

    public function show($id) {
        Log::info("EstudianteController@show called with id: {$id}");
        return Estudiante::with('paralelo')->findOrFail($id);
    }

    public function update(Request $request, $id) {
        Log::info("EstudianteController@update called with id: {$id}", ['request' => $request->all()]);
        $estudiante = Estudiante::findOrFail($id);
        $data = $request->validate([
            'nombre' => 'sometimes|required',
            'cedula' => 'sometimes|required|unique:estudiantes,cedula,' . $id,
            'correo' => 'sometimes|required|email|unique:estudiantes,correo,' . $id,
            'paralelo_id' => 'sometimes|required|exists:paralelos,id',
        ]);
        $estudiante->update($data);
        Log::info('Estudiante updated', ['estudiante' => $estudiante]);
        return $estudiante;
    }

    public function destroy($id) {
        Log::info("EstudianteController@destroy called with id: {$id}");
        Estudiante::destroy($id);
        Log::info("Estudiante with id {$id} deleted");
        return response()->noContent();
    }
}
