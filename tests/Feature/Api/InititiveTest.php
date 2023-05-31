<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Inititive;

use App\Models\KeyPeformanceIndicator;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class InititiveTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(\Database\Seeders\PermissionsSeeder::class);

        $this->withoutExceptionHandling();
    }

    /**
     * @test
     */
    public function it_gets_inititives_list(): void
    {
        $inititives = Inititive::factory()
            ->count(5)
            ->create();

        $response = $this->getJson(route('api.inititives.index'));

        $response->assertOk()->assertSee($inititives[0]->id);
    }

    /**
     * @test
     */
    public function it_stores_the_inititive(): void
    {
        $data = Inititive::factory()
            ->make()
            ->toArray();

        $response = $this->postJson(route('api.inititives.store'), $data);

        $this->assertDatabaseHas('inititives', $data);

        $response->assertStatus(201)->assertJsonFragment($data);
    }

    /**
     * @test
     */
    public function it_updates_the_inititive(): void
    {
        $inititive = Inititive::factory()->create();

        $keyPeformanceIndicator = KeyPeformanceIndicator::factory()->create();

        $data = [
            'key_peformance_indicator_id' => $keyPeformanceIndicator->id,
        ];

        $response = $this->putJson(
            route('api.inititives.update', $inititive),
            $data
        );

        $data['id'] = $inititive->id;

        $this->assertDatabaseHas('inititives', $data);

        $response->assertOk()->assertJsonFragment($data);
    }

    /**
     * @test
     */
    public function it_deletes_the_inititive(): void
    {
        $inititive = Inititive::factory()->create();

        $response = $this->deleteJson(
            route('api.inititives.destroy', $inititive)
        );

        $this->assertModelMissing($inititive);

        $response->assertNoContent();
    }
}
