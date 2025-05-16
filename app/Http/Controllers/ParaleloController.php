<?php

namespace App\Http\Controllers;

use App\Models\Paralelo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * @OA\Tag(
 *     name="Paralelos",
 *     description="Operaciones relacionadas a los paralelos"
 * )
 */
class ParaleloController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/paralelos",
     *     summary="Listar todos los paralelos",
     *     tags={"Paralelos"},
     *     @OA\Response(response=200, description="Lista de paralelos")
     * )
     */
    public function index() {
        Log::info('ParaleloController@index called');
        return Paralelo::with('estudiantes')->get();
    }

    /**
     * @OA\Post(
     *     path="/api/paralelos",
     *     summary="Crear un nuevo paralelo",
     *     tags={"Paralelos"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nombre"},
     *             @OA\Property(property="nombre", type="string")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Paralelo creado exitosamente")
     * )
     */
    public function store(Request $request) {
        Log::info('ParaleloController@store called', ['request' => $request->all()]);
        $data = $request->validate([
            'nombre' => 'required',
        ]);
        $paralelo = Paralelo::create($data);
        Log::info('Paralelo created', ['paralelo' => $paralelo]);
        return $paralelo;
    }

    /**
     * @OA\Get(
     *     path="/api/paralelos/{id}",
     *     summary="Obtener un paralelo por ID",
     *     tags={"Paralelos"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Paralelo encontrado"),
     *     @OA\Response(response=404, description="Paralelo no encontrado")
     * )
     */
    public function show($id) {
        Log::info("ParaleloController@show called with id: {$id}");
        return Paralelo::with('estudiantes')->findOrFail($id);
    }

    /**
     * @OA\Put(
     *     path="/api/paralelos/{id}",
     *     summary="Actualizar un paralelo existente",
     *     tags={"Paralelos"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nombre"},
     *             @OA\Property(property="nombre", type="string")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Paralelo actualizado"),
     *     @OA\Response(response=404, description="Paralelo no encontrado")
     * )
     */
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

    /**
     * @OA\Delete(
     *     path="/api/paralelos/{id}",
     *     summary="Eliminar un paralelo por ID",
     *     tags={"Paralelos"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=204, description="Paralelo eliminado"),
     *     @OA\Response(response=404, description="Paralelo no encontrado")
     * )
     */
    public function destroy($id) {
        Log::info("ParaleloController@destroy called with id: {$id}");
        Paralelo::destroy($id);
        Log::info("Paralelo with id {$id} deleted");
        return response()->noContent();
    }
}
