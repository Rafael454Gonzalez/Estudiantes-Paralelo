<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Paralelo;

class ParaleloTest extends TestCase
{
    use RefreshDatabase;

    public function test_crear_paralelo_POST()
    {
        $response = $this->postJson('/api/paralelos', [
            'nombre' => 'A1',
        ]);

        $response->assertStatus(201);  // Creación exitosa
        $this->assertDatabaseHas('paralelos', [
            'nombre' => 'A1',
        ]);
    }

    public function test_obtener_paralelos_GET()
    {
        $paralelo = Paralelo::factory()->create(['nombre' => 'B1']);

        $response = $this->getJson('/api/paralelos');

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'nombre' => 'B1',
        ]);
    }

    public function test_actualizar_paralelo_PUT()
    {
        $paralelo = Paralelo::factory()->create(['nombre' => 'C1']);

        $response = $this->putJson('/api/paralelos/' . $paralelo->id, [
            'nombre' => 'C1 Actualizado',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('paralelos', [
            'id' => $paralelo->id,
            'nombre' => 'C1 Actualizado',
        ]);
    }

    public function test_eliminar_paralelo_DELETE()
    {
        $paralelo = Paralelo::factory()->create(['nombre' => 'D1']);

        $response = $this->deleteJson('/api/paralelos/' . $paralelo->id);

        $response->assertStatus(204);
        $this->assertDatabaseMissing('paralelos', [
            'id' => $paralelo->id,
        ]);
    }
}
