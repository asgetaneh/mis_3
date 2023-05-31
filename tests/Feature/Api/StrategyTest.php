<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Strategy;

use App\Models\Objective;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StrategyTest extends TestCase
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
    public function it_gets_strategies_list(): void
    {
        $strategies = Strategy::factory()
            ->count(5)
            ->create();

        $response = $this->getJson(route('api.strategies.index'));

        $response->assertOk()->assertSee($strategies[0]->id);
    }

    /**
     * @test
     */
    public function it_stores_the_strategy(): void
    {
        $data = Strategy::factory()
            ->make()
            ->toArray();

        $response = $this->postJson(route('api.strategies.store'), $data);

        $this->assertDatabaseHas('strategies', $data);

        $response->assertStatus(201)->assertJsonFragment($data);
    }

    /**
     * @test
     */
    public function it_updates_the_strategy(): void
    {
        $strategy = Strategy::factory()->create();

        $objective = Objective::factory()->create();
        $user = User::factory()->create();
        $user = User::factory()->create();

        $data = [
            'objective_id' => $objective->id,
            'created_by_id' => $user->id,
            'updated_by_id' => $user->id,
        ];

        $response = $this->putJson(
            route('api.strategies.update', $strategy),
            $data
        );

        $data['id'] = $strategy->id;

        $this->assertDatabaseHas('strategies', $data);

        $response->assertOk()->assertJsonFragment($data);
    }

    /**
     * @test
     */
    public function it_deletes_the_strategy(): void
    {
        $strategy = Strategy::factory()->create();

        $response = $this->deleteJson(
            route('api.strategies.destroy', $strategy)
        );

        $this->assertModelMissing($strategy);

        $response->assertNoContent();
    }
}
