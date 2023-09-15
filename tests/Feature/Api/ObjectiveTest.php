<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Objective;

use App\Models\Goal;
use App\Models\Perspective;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ObjectiveTest extends TestCase
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
    public function it_gets_objectives_list(): void
    {
        $objectives = Objective::factory()
            ->count(5)
            ->create();

        $response = $this->getJson(route('api.objectives.index'));

        $response->assertOk()->assertSee($objectives[0]->id);
    }

    /**
     * @test
     */
    public function it_stores_the_objective(): void
    {
        $data = Objective::factory()
            ->make()
            ->toArray();

        $response = $this->postJson(route('api.objectives.store'), $data);

        $this->assertDatabaseHas('objectives', $data);

        $response->assertStatus(201)->assertJsonFragment($data);
    }

    /**
     * @test
     */
    public function it_updates_the_objective(): void
    {
        $objective = Objective::factory()->create();

        $goal = Goal::factory()->create();
        $perspective = Perspective::factory()->create();
        $user = User::factory()->create();
        $user = User::factory()->create();

        $data = [
            'weight' => $this->faker->randomNumber(2),
            'goal_id' => $goal->id,
            'perspective_id' => $perspective->id,
            'created_by_id' => $user->id,
            'updated_by_id' => $user->id,
        ];

        $response = $this->putJson(
            route('api.objectives.update', $objective),
            $data
        );

        $data['id'] = $objective->id;

        $this->assertDatabaseHas('objectives', $data);

        $response->assertOk()->assertJsonFragment($data);
    }

    /**
     * @test
     */
    public function it_deletes_the_objective(): void
    {
        $objective = Objective::factory()->create();

        $response = $this->deleteJson(
            route('api.objectives.destroy', $objective)
        );

        $this->assertModelMissing($objective);

        $response->assertNoContent();
    }
}
