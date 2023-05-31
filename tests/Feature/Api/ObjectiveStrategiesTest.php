<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Strategy;
use App\Models\Objective;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ObjectiveStrategiesTest extends TestCase
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
    public function it_gets_objective_strategies(): void
    {
        $objective = Objective::factory()->create();
        $strategies = Strategy::factory()
            ->count(2)
            ->create([
                'objective_id' => $objective->id,
            ]);

        $response = $this->getJson(
            route('api.objectives.strategies.index', $objective)
        );

        $response->assertOk()->assertSee($strategies[0]->id);
    }

    /**
     * @test
     */
    public function it_stores_the_objective_strategies(): void
    {
        $objective = Objective::factory()->create();
        $data = Strategy::factory()
            ->make([
                'objective_id' => $objective->id,
            ])
            ->toArray();

        $response = $this->postJson(
            route('api.objectives.strategies.store', $objective),
            $data
        );

        $this->assertDatabaseHas('strategies', $data);

        $response->assertStatus(201)->assertJsonFragment($data);

        $strategy = Strategy::latest('id')->first();

        $this->assertEquals($objective->id, $strategy->objective_id);
    }
}
