<?php

namespace App\Http\Controllers;

use App\Models\Estudiante;
use Illuminate\Http\Request;

class EstudianteController extends Controller
{
    public function index() {
        return Estudiante::with('paralelo')->get();
    }

    public function store(Request $request) {
        $data = $request->validate([
            'nombre' => 'required',
            'cedula' => 'required|unique:estudiantes',
            'correo' => 'required|email|unique:estudiantes',
            'paralelo_id' => 'required|exists:paralelos,id',
        ]);
        return Estudiante::create($data);
    }

    public function show($id) {
        return Estudiante::with('paralelo')->findOrFail($id);
    }

    public function update(Request $request, $id) {
        $estudiante = Estudiante::findOrFail($id);
        $data = $request->validate([
            'nombre' => 'sometimes|required',
            'cedula' => 'sometimes|required|unique:estudiantes,cedula,' . $id,
            'correo' => 'sometimes|required|email|unique:estudiantes,correo,' . $id,
            'paralelo_id' => 'sometimes|required|exists:paralelos,id',
        ]);
        $estudiante->update($data);
        return $estudiante;
    }

    public function destroy($id) {
        Estudiante::destroy($id);
        return response()->noContent();
    }
}
