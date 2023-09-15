<?php

namespace Tests\Feature\Controllers;

use App\Models\User;
use App\Models\Goal;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GoalControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(
            User::factory()->create(['email' => 'admin@admin.com'])
        );

        $this->seed(\Database\Seeders\PermissionsSeeder::class);

        $this->withoutExceptionHandling();
    }

    /**
     * @test
     */
    public function it_displays_index_view_with_goals(): void
    {
        $goals = Goal::factory()
            ->count(5)
            ->create();

        $response = $this->get(route('goals.index'));

        $response
            ->assertOk()
            ->assertViewIs('app.goals.index')
            ->assertViewHas('goals');
    }

    /**
     * @test
     */
    public function it_displays_create_view_for_goal(): void
    {
        $response = $this->get(route('goals.create'));

        $response->assertOk()->assertViewIs('app.goals.create');
    }

    /**
     * @test
     */
    public function it_stores_the_goal(): void
    {
        $data = Goal::factory()
            ->make()
            ->toArray();

        $response = $this->post(route('goals.store'), $data);

        unset($data['updated_by']);

        $this->assertDatabaseHas('goals', $data);

        $goal = Goal::latest('id')->first();

        $response->assertRedirect(route('goals.edit', $goal));
    }

    /**
     * @test
     */
    public function it_displays_show_view_for_goal(): void
    {
        $goal = Goal::factory()->create();

        $response = $this->get(route('goals.show', $goal));

        $response
            ->assertOk()
            ->assertViewIs('app.goals.show')
            ->assertViewHas('goal');
    }

    /**
     * @test
     */
    public function it_displays_edit_view_for_goal(): void
    {
        $goal = Goal::factory()->create();

        $response = $this->get(route('goals.edit', $goal));

        $response
            ->assertOk()
            ->assertViewIs('app.goals.edit')
            ->assertViewHas('goal');
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

        $response = $this->put(route('goals.update', $goal), $data);

        unset($data['updated_by']);

        $data['id'] = $goal->id;

        $this->assertDatabaseHas('goals', $data);

        $response->assertRedirect(route('goals.edit', $goal));
    }

    /**
     * @test
     */
    public function it_deletes_the_goal(): void
    {
        $goal = Goal::factory()->create();

        $response = $this->delete(route('goals.destroy', $goal));

        $response->assertRedirect(route('goals.index'));

        $this->assertModelMissing($goal);
    }
}
