<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Goal;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserGoalsTest extends TestCase
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
    public function it_gets_user_goals(): void
    {
        $user = User::factory()->create();
        $goals = Goal::factory()
            ->count(2)
            ->create([
                'updated_by' => $user->id,
            ]);

        $response = $this->getJson(route('api.users.goals.index', $user));

        $response->assertOk()->assertSee($goals[0]->id);
    }

    /**
     * @test
     */
    public function it_stores_the_user_goals(): void
    {
        $user = User::factory()->create();
        $data = Goal::factory()
            ->make([
                'updated_by' => $user->id,
            ])
            ->toArray();

        $response = $this->postJson(
            route('api.users.goals.store', $user),
            $data
        );

        unset($data['updated_by']);

        $this->assertDatabaseHas('goals', $data);

        $response->assertStatus(201)->assertJsonFragment($data);

        $goal = Goal::latest('id')->first();

        $this->assertEquals($user->id, $goal->updated_by);
    }
}
