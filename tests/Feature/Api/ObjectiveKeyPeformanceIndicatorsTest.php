<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Objective;
use App\Models\KeyPeformanceIndicator;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ObjectiveKeyPeformanceIndicatorsTest extends TestCase
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
    public function it_gets_objective_key_peformance_indicators(): void
    {
        $objective = Objective::factory()->create();
        $keyPeformanceIndicators = KeyPeformanceIndicator::factory()
            ->count(2)
            ->create([
                'objective_id' => $objective->id,
            ]);

        $response = $this->getJson(
            route('api.objectives.key-peformance-indicators.index', $objective)
        );

        $response->assertOk()->assertSee($keyPeformanceIndicators[0]->id);
    }

    /**
     * @test
     */
    public function it_stores_the_objective_key_peformance_indicators(): void
    {
        $objective = Objective::factory()->create();
        $data = KeyPeformanceIndicator::factory()
            ->make([
                'objective_id' => $objective->id,
            ])
            ->toArray();

        $response = $this->postJson(
            route('api.objectives.key-peformance-indicators.store', $objective),
            $data
        );

        $this->assertDatabaseHas('key_peformance_indicators', $data);

        $response->assertStatus(201)->assertJsonFragment($data);

        $keyPeformanceIndicator = KeyPeformanceIndicator::latest('id')->first();

        $this->assertEquals(
            $objective->id,
            $keyPeformanceIndicator->objective_id
        );
    }
}
