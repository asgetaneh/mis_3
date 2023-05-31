<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Goal;
use App\Models\Objective;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GoalObjectivesTest extends TestCase
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
    public function it_gets_goal_objectives(): void
    {
        $goal = Goal::factory()->create();
        $objectives = Objective::factory()
            ->count(2)
            ->create([
                'goal_id' => $goal->id,
            ]);

        $response = $this->getJson(route('api.goals.objectives.index', $goal));

        $response->assertOk()->assertSee($objectives[0]->id);
    }

    /**
     * @test
     */
    public function it_stores_the_goal_objectives(): void
    {
        $goal = Goal::factory()->create();
        $data = Objective::factory()
            ->make([
                'goal_id' => $goal->id,
            ])
            ->toArray();

        $response = $this->postJson(
            route('api.goals.objectives.store', $goal),
            $data
        );

        $this->assertDatabaseHas('objectives', $data);

        $response->assertStatus(201)->assertJsonFragment($data);

        $objective = Objective::latest('id')->first();

        $this->assertEquals($goal->id, $objective->goal_id);
    }
}
