<?php

namespace Tests\Feature\Controllers;

use App\Models\User;
use App\Models\Strategy;

use App\Models\Objective;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StrategyControllerTest extends TestCase
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
    public function it_displays_index_view_with_strategies(): void
    {
        $strategies = Strategy::factory()
            ->count(5)
            ->create();

        $response = $this->get(route('strategies.index'));

        $response
            ->assertOk()
            ->assertViewIs('app.strategies.index')
            ->assertViewHas('strategies');
    }

    /**
     * @test
     */
    public function it_displays_create_view_for_strategy(): void
    {
        $response = $this->get(route('strategies.create'));

        $response->assertOk()->assertViewIs('app.strategies.create');
    }

    /**
     * @test
     */
    public function it_stores_the_strategy(): void
    {
        $data = Strategy::factory()
            ->make()
            ->toArray();

        $response = $this->post(route('strategies.store'), $data);

        $this->assertDatabaseHas('strategies', $data);

        $strategy = Strategy::latest('id')->first();

        $response->assertRedirect(route('strategies.edit', $strategy));
    }

    /**
     * @test
     */
    public function it_displays_show_view_for_strategy(): void
    {
        $strategy = Strategy::factory()->create();

        $response = $this->get(route('strategies.show', $strategy));

        $response
            ->assertOk()
            ->assertViewIs('app.strategies.show')
            ->assertViewHas('strategy');
    }

    /**
     * @test
     */
    public function it_displays_edit_view_for_strategy(): void
    {
        $strategy = Strategy::factory()->create();

        $response = $this->get(route('strategies.edit', $strategy));

        $response
            ->assertOk()
            ->assertViewIs('app.strategies.edit')
            ->assertViewHas('strategy');
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

        $response = $this->put(route('strategies.update', $strategy), $data);

        $data['id'] = $strategy->id;

        $this->assertDatabaseHas('strategies', $data);

        $response->assertRedirect(route('strategies.edit', $strategy));
    }

    /**
     * @test
     */
    public function it_deletes_the_strategy(): void
    {
        $strategy = Strategy::factory()->create();

        $response = $this->delete(route('strategies.destroy', $strategy));

        $response->assertRedirect(route('strategies.index'));

        $this->assertModelMissing($strategy);
    }
}
