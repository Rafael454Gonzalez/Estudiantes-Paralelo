<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Estudiante;
use App\Models\Paralelo;
use Illuminate\Support\Facades\Log;

class EstudianteTest extends TestCase
{
    use RefreshDatabase;

    public function test_crear_estudiante_POST()
    {
        $paralelo = Paralelo::factory()->create();
        Log::info('🧪 Iniciando test: crear estudiante');

        $response = $this->postJson('/api/estudiantes', [
            'nombre' => 'Juan',
            'cedula' => '1234567890',
            'correo' => 'juan@test.com',
            'paralelo_id' => $paralelo->id,
        ]);

        Log::info('📥 Respuesta:', $response->json());

        $response->assertStatus(201);
        $this->assertDatabaseHas('estudiantes', [
            'cedula' => '1234567890',
            'nombre' => 'Juan',
            'correo' => 'juan@test.com',
        ]);

        Log::info('✅ Test crear estudiante finalizado');
    }

    public function test_no_puede_crear_estudiante_sin_datos_obligatorios_EXTRA()
    {
        Log::info('🧪 Iniciando test: validación de datos obligatorios');

        $response = $this->postJson('/api/estudiantes', []);

        $response->assertStatus(422);

        Log::info('✅ Validación de datos obligatorios verificada con error 422');
    }

    public function test_obtener_estudiantes_GET()
    {
        $paralelo = Paralelo::factory()->create();
        $estudiante = Estudiante::factory()->create(['paralelo_id' => $paralelo->id]);

        Log::info('🧪 Iniciando test: obtener estudiantes');

        $response = $this->getJson('/api/estudiantes');

        Log::info('📥 Respuesta:', $response->json());

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'cedula' => $estudiante->cedula,
            'nombre' => $estudiante->nombre,
        ]);

        Log::info('✅ Test obtener estudiantes finalizado');
    }

    public function test_actualizar_estudiante_PUT()
    {
        $paralelo = Paralelo::factory()->create();
        $estudiante = Estudiante::factory()->create(['paralelo_id' => $paralelo->id]);

        Log::info('🧪 Iniciando test: actualizar estudiante', ['id' => $estudiante->id]);

        $response = $this->putJson('/api/estudiantes/' . $estudiante->id, [
            'nombre' => 'Juan Actualizado',
            'cedula' => $estudiante->cedula,
            'correo' => 'juan_actualizado@test.com',
            'paralelo_id' => $paralelo->id,
        ]);

        Log::info('📥 Respuesta:', $response->json());

        $response->assertStatus(200);
        $this->assertDatabaseHas('estudiantes', [
            'nombre' => 'Juan Actualizado',
            'correo' => 'juan_actualizado@test.com',
        ]);

        Log::info('✅ Test actualizar estudiante finalizado');
    }

    public function test_eliminar_estudiante_DELETE()
    {
        $paralelo = Paralelo::factory()->create();
        $estudiante = Estudiante::factory()->create(['paralelo_id' => $paralelo->id]);

        Log::info('🧪 Iniciando test: eliminar estudiante', ['id' => $estudiante->id]);

        $response = $this->deleteJson('/api/estudiantes/' . $estudiante->id);

        $response->assertStatus(204);
        $this->assertDatabaseMissing('estudiantes', [
            'cedula' => $estudiante->cedula,
        ]);

        Log::info('✅ Test eliminar estudiante finalizado');
    }

    public function test_no_puede_crear_estudiante_con_cedula_duplicada_EXTRA()
    {
        $paralelo = Paralelo::factory()->create();
        Estudiante::factory()->create(['cedula' => '1234567890']);

        Log::info('🧪 Iniciando test: cedula duplicada');

        $response = $this->postJson('/api/estudiantes', [
            'nombre' => 'Juan',
            'cedula' => '1234567890',
            'correo' => 'juan@test.com',
            'paralelo_id' => $paralelo->id,
        ]);

        $response->assertStatus(422);

        Log::info('✅ Test cedula duplicada verificado con error 422');
    }
}
