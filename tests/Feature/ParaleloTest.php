<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Paralelo;
use Illuminate\Support\Facades\Log;

class ParaleloTest extends TestCase
{
    use RefreshDatabase;

    public function test_crear_paralelo_POST()
    {
        Log::info('🧪 Iniciando test: crear paralelo');

        $response = $this->postJson('/api/paralelos', [
            'nombre' => 'A1',
        ]);

        Log::info('📥 Respuesta:', $response->json());

        $response->assertStatus(201);
        $this->assertDatabaseHas('paralelos', ['nombre' => 'A1']);

        Log::info('✅ Test crear paralelo finalizado correctamente');
    }

    public function test_obtener_paralelos_GET()
    {
        $paralelo = Paralelo::factory()->create(['nombre' => 'B1']);
        Log::info('🧪 Iniciando test: obtener paralelos');

        $response = $this->getJson('/api/paralelos');

        Log::info('📥 Respuesta:', $response->json());

        $response->assertStatus(200);
        $response->assertJsonFragment(['nombre' => 'B1']);

        Log::info('✅ Test obtener paralelos finalizado correctamente');
    }

    public function test_actualizar_paralelo_PUT()
    {
        $paralelo = Paralelo::factory()->create(['nombre' => 'C1']);
        Log::info('🧪 Iniciando test: actualizar paralelo', ['id' => $paralelo->id]);

        $response = $this->putJson('/api/paralelos/' . $paralelo->id, [
            'nombre' => 'C1 Actualizado',
        ]);

        Log::info('📥 Respuesta:', $response->json());

        $response->assertStatus(200);
        $this->assertDatabaseHas('paralelos', [
            'id' => $paralelo->id,
            'nombre' => 'C1 Actualizado',
        ]);

        Log::info('✅ Test actualizar paralelo finalizado');
    }

    public function test_eliminar_paralelo_DELETE()
    {
        $paralelo = Paralelo::factory()->create(['nombre' => 'D1']);
        Log::info('🧪 Iniciando test: eliminar paralelo', ['id' => $paralelo->id]);

        $response = $this->deleteJson('/api/paralelos/' . $paralelo->id);

        $response->assertStatus(204);
        $this->assertDatabaseMissing('paralelos', ['id' => $paralelo->id]);

        Log::info('✅ Test eliminar paralelo finalizado');
    }
}
