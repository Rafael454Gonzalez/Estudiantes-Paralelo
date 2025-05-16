<?php

namespace App\Http\Controllers;

use App\Models\Estudiante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * @OA\Tag(
 *     name="Estudiantes",
 *     description="Operaciones relacionadas con los estudiantes"
 * )
 */
class EstudianteController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/estudiantes",
     *     tags={"Estudiantes"},
     *     summary="Listar todos los estudiantes",
     *     @OA\Response(response=200, description="Lista de estudiantes")
     * )
     */
    public function index() {
        Log::info('EstudianteController@index called');
        return Estudiante::with('paralelo')->get();
    }

    /**
     * @OA\Post(
     *     path="/api/estudiantes",
     *     tags={"Estudiantes"},
     *     summary="Crear un nuevo estudiante",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nombre", "cedula", "correo", "paralelo_id"},
     *             @OA\Property(property="nombre", type="string"),
     *             @OA\Property(property="cedula", type="string"),
     *             @OA\Property(property="correo", type="string", format="email"),
     *             @OA\Property(property="paralelo_id", type="integer")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Estudiante creado")
     * )
     */
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

    /**
     * @OA\Get(
     *     path="/api/estudiantes/{id}",
     *     tags={"Estudiantes"},
     *     summary="Obtener un estudiante por ID",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Información del estudiante"),
     *     @OA\Response(response=404, description="Estudiante no encontrado")
     * )
     */
    public function show($id) {
        Log::info("EstudianteController@show called with id: {$id}");
        return Estudiante::with('paralelo')->findOrFail($id);
    }

    /**
     * @OA\Put(
     *     path="/api/estudiantes/{id}",
     *     tags={"Estudiantes"},
     *     summary="Actualizar un estudiante",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="nombre", type="string"),
     *             @OA\Property(property="cedula", type="string"),
     *             @OA\Property(property="correo", type="string", format="email"),
     *             @OA\Property(property="paralelo_id", type="integer")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Estudiante actualizado")
     * )
     */
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

    /**
     * @OA\Delete(
     *     path="/api/estudiantes/{id}",
     *     tags={"Estudiantes"},
     *     summary="Eliminar un estudiante",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=204, description="Estudiante eliminado")
     * )
     */
    public function destroy($id) {
        Log::info("EstudianteController@destroy called with id: {$id}");
        Estudiante::destroy($id);
        Log::info("Estudiante with id {$id} deleted");
        return response()->noContent();
    }
}
