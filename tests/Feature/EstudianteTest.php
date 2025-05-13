<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Estudiante;
use App\Models\Paralelo;

class EstudianteTest extends TestCase
{
    use RefreshDatabase;

public function test_crear_estudiante_POST()
{
    $paralelo = Paralelo::factory()->create(); // Creamos un paralelo para la relación

    $response = $this->postJson('/api/estudiantes', [
        'nombre' => 'Juan',
        'cedula' => '1234567890',
        'correo' => 'juan@test.com',
        'paralelo_id' => $paralelo->id,
    ]);

    $response->assertStatus(201);  // Verifica que se haya creado el recurso
    $this->assertDatabaseHas('estudiantes', [
        'cedula' => '1234567890',
        'nombre' => 'Juan',
        'correo' => 'juan@test.com',
    ]);  // Verifica que los datos estén en la base de datos
}
public function test_no_puede_crear_estudiante_sin_datos_obligatorios_EXTRA()
{
    $response = $this->postJson('/api/estudiantes', []);
    $response->assertStatus(422); // Verifica que se reciba un error de validación
}
public function test_obtener_estudiantes_GET()
{
    $paralelo = Paralelo::factory()->create();
    $estudiante = Estudiante::factory()->create(['paralelo_id' => $paralelo->id]);

    $response = $this->getJson('/api/estudiantes');
    $response->assertStatus(200);  // Verifica que la respuesta sea exitosa
    $response->assertJsonFragment([
        'cedula' => $estudiante->cedula,
        'nombre' => $estudiante->nombre,
    ]);  // Verifica que los datos de estudiantes estén en la respuesta
}
public function test_actualizar_estudiante_PUT()
{
    $paralelo = Paralelo::factory()->create();
    $estudiante = Estudiante::factory()->create(['paralelo_id' => $paralelo->id]);

    $response = $this->putJson('/api/estudiantes/' . $estudiante->id, [
        'nombre' => 'Juan Actualizado',
        'cedula' => $estudiante->cedula,
        'correo' => 'juan_actualizado@test.com',
        'paralelo_id' => $paralelo->id,
    ]);

    $response->assertStatus(200);  // Verifica que la actualización fue exitosa
    $this->assertDatabaseHas('estudiantes', [
        'nombre' => 'Juan Actualizado',
        'correo' => 'juan_actualizado@test.com',
    ]);  // Verifica que los datos en la base de datos se han actualizado
}
public function test_eliminar_estudiante_DELETE()
{
    $paralelo = Paralelo::factory()->create();
    $estudiante = Estudiante::factory()->create(['paralelo_id' => $paralelo->id]);

    $response = $this->deleteJson('/api/estudiantes/' . $estudiante->id);
    $response->assertStatus(204);  // Verifica que no haya contenido en la respuesta

    $this->assertDatabaseMissing('estudiantes', [
        'cedula' => $estudiante->cedula,
    ]);  // Verifica que el estudiante ya no existe en la base de datos
}
public function test_no_puede_crear_estudiante_con_cedula_duplicada_EXTRA()
{
    $paralelo = Paralelo::factory()->create();
    Estudiante::factory()->create(['cedula' => '1234567890']);

    $response = $this->postJson('/api/estudiantes', [
        'nombre' => 'Juan',
        'cedula' => '1234567890',  // Cedula duplicada
        'correo' => 'juan@test.com',
        'paralelo_id' => $paralelo->id,
    ]);

    $response->assertStatus(422);  // Verifica que haya error de validación
}

}
