<?php

namespace Tests\Feature\Controllers;

use App\Models\User;
use App\Models\Objective;

use App\Models\Goal;
use App\Models\Perspective;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ObjectiveControllerTest extends TestCase
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
    public function it_displays_index_view_with_objectives(): void
    {
        $objectives = Objective::factory()
            ->count(5)
            ->create();

        $response = $this->get(route('objectives.index'));

        $response
            ->assertOk()
            ->assertViewIs('app.objectives.index')
            ->assertViewHas('objectives');
    }

    /**
     * @test
     */
    public function it_displays_create_view_for_objective(): void
    {
        $response = $this->get(route('objectives.create'));

        $response->assertOk()->assertViewIs('app.objectives.create');
    }

    /**
     * @test
     */
    public function it_stores_the_objective(): void
    {
        $data = Objective::factory()
            ->make()
            ->toArray();

        $response = $this->post(route('objectives.store'), $data);

        $this->assertDatabaseHas('objectives', $data);

        $objective = Objective::latest('id')->first();

        $response->assertRedirect(route('objectives.edit', $objective));
    }

    /**
     * @test
     */
    public function it_displays_show_view_for_objective(): void
    {
        $objective = Objective::factory()->create();

        $response = $this->get(route('objectives.show', $objective));

        $response
            ->assertOk()
            ->assertViewIs('app.objectives.show')
            ->assertViewHas('objective');
    }

    /**
     * @test
     */
    public function it_displays_edit_view_for_objective(): void
    {
        $objective = Objective::factory()->create();

        $response = $this->get(route('objectives.edit', $objective));

        $response
            ->assertOk()
            ->assertViewIs('app.objectives.edit')
            ->assertViewHas('objective');
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

        $response = $this->put(route('objectives.update', $objective), $data);

        $data['id'] = $objective->id;

        $this->assertDatabaseHas('objectives', $data);

        $response->assertRedirect(route('objectives.edit', $objective));
    }

    /**
     * @test
     */
    public function it_deletes_the_objective(): void
    {
        $objective = Objective::factory()->create();

        $response = $this->delete(route('objectives.destroy', $objective));

        $response->assertRedirect(route('objectives.index'));

        $this->assertModelMissing($objective);
    }
}
