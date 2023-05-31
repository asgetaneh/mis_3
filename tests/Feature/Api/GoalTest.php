<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Goal;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GoalTest extends TestCase
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
    public function it_gets_goals_list(): void
    {
        $goals = Goal::factory()
            ->count(5)
            ->create();

        $response = $this->getJson(route('api.goals.index'));

        $response->assertOk()->assertSee($goals[0]->id);
    }

    /**
     * @test
     */
    public function it_stores_the_goal(): void
    {
        $data = Goal::factory()
            ->make()
            ->toArray();

        $response = $this->postJson(route('api.goals.store'), $data);

        unset($data['updated_by']);

        $this->assertDatabaseHas('goals', $data);

        $response->assertStatus(201)->assertJsonFragment($data);
    }

    /**
     * @test
     */
    public function it_updates_the_goal(): void
    {
        $goal = Goal::factory()->create();

        $user = User::factory()->create();

        $data = [
            'is_active' => $this->faker->boolean(),
            'created_by_id' => $this->faker->randomNumber(),
            'updated_by' => $this->faker->randomNumber(),
            'updated_by' => $user->id,
        ];

        $response = $this->putJson(route('api.goals.update', $goal), $data);

        unset($data['updated_by']);

        $data['id'] = $goal->id;

        $this->assertDatabaseHas('goals', $data);

        $response->assertOk()->assertJsonFragment($data);
    }

    /**
     * @test
     */
    public function it_deletes_the_goal(): void
    {
        $goal = Goal::factory()->create();

        $response = $this->deleteJson(route('api.goals.destroy', $goal));

        $this->assertModelMissing($goal);

        $response->assertNoContent();
    }
}
