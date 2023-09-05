<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Objective;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserObjectivesTest extends TestCase
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
    public function it_gets_user_objectives(): void
    {
        $user = User::factory()->create();
        $objectives = Objective::factory()
            ->count(2)
            ->create([
                'updated_by_id' => $user->id,
            ]);

        $response = $this->getJson(route('api.users.objectives.index', $user));

        $response->assertOk()->assertSee($objectives[0]->id);
    }

    /**
     * @test
     */
    public function it_stores_the_user_objectives(): void
    {
        $user = User::factory()->create();
        $data = Objective::factory()
            ->make([
                'updated_by_id' => $user->id,
            ])
            ->toArray();

        $response = $this->postJson(
            route('api.users.objectives.store', $user),
            $data
        );

        $this->assertDatabaseHas('objectives', $data);

        $response->assertStatus(201)->assertJsonFragment($data);

        $objective = Objective::latest('id')->first();

        $this->assertEquals($user->id, $objective->updated_by_id);
    }
}
