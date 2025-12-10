<?php

namespace Tests\Feature;

use App\Models\Place;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PlaceApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_creates_a_place(): void
    {
        $payload = [
            'name'  => 'Sample Place',
            'city'  => 'Florianópolis',
            'state' => 'SC',
        ];

        $response = $this->postJson('/api/places', $payload);

        $response->assertCreated()
            ->assertJsonPath('data.name', 'Sample Place')
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'slug',
                    'city',
                    'state',
                    'created_at',
                    'updated_at',
                ],
            ]);

        $this->assertDatabaseHas('places', [
            'name'  => 'Sample Place',
            'city'  => 'Florianópolis',
            'state' => 'SC',
        ]);
    }

    public function test_lists_places_with_name_filter(): void
    {
        Place::factory()->create(['name' => 'Praça XV']);
        Place::factory()->create(['name' => 'Parque Municipal']);

        $response = $this->getJson('/api/places?name=Praça');

        $response->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.name', 'Praça XV');
    }

    public function test_returns_validation_errors_when_creating_place(): void
    {
        $response = $this->postJson('/api/places', []);

        $response->assertStatus(422)
            ->assertJsonStructure(['message', 'errors' => ['name', 'city', 'state']]);
    }

    public function test_enforces_unique_slug(): void
    {
        Place::factory()->create(['slug' => 'central-park']);

        $payload = [
            'name'  => 'Another Place',
            'slug'  => 'central-park',
            'city'  => 'New York',
            'state' => 'ny',
        ];

        $response = $this->postJson('/api/places', $payload);

        $response->assertStatus(422)
            ->assertJsonValidationErrorFor('slug');
    }

    public function test_updates_a_place(): void
    {
        $place = Place::factory()->create([
            'name'  => 'Old Name',
            'city'  => 'Old City',
            'state' => 'sc',
        ]);

        $payload = [
            'name'  => 'New Name',
            'city'  => 'New City',
            'state' => 'sp',
        ];

        $response = $this->putJson("/api/places/{$place->id}", $payload);

        $response->assertOk()
            ->assertJsonPath('data.name', 'New Name')
            ->assertJsonPath('data.city', 'New City')
            ->assertJsonPath('data.state', 'SP');

        $this->assertDatabaseHas('places', [
            'id'    => $place->id,
            'state' => 'SP',
        ]);
    }

    public function test_deletes_a_place(): void
    {
        $place = Place::factory()->create();

        $response = $this->deleteJson("/api/places/{$place->id}");

        $response->assertNoContent();

        $this->assertDatabaseMissing('places', ['id' => $place->id]);
    }

    public function test_returns_404_for_missing_place(): void
    {
        $response = $this->getJson('/api/places/999');

        $response->assertNotFound()
            ->assertJsonPath('message', 'Resource not found');
    }

    public function test_finds_place_by_slug(): void
    {
        $place = Place::factory()->create(['slug' => 'praia']);

        $response = $this->getJson('/api/places/slug/praia');

        $response->assertOk()
            ->assertJsonPath('data.id', $place->id);
    }

    public function test_sorts_places_by_name_desc(): void
    {
        Place::factory()->create(['name' => 'Alpha']);
        Place::factory()->create(['name' => 'Zulu']);

        $response = $this->getJson('/api/places?sort=-name');

        $response->assertOk()
            ->assertJsonPath('data.0.name', 'Zulu')
            ->assertJsonPath('data.1.name', 'Alpha');
    }

    public function test_filters_by_state_case_insensitive(): void
    {
        Place::factory()->create(['state' => 'sc']);
        Place::factory()->create(['state' => 'sp']);

        $response = $this->getJson('/api/places?state=sc');

        $response->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.state', 'SC');
    }
}
