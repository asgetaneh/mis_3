<?php

namespace Tests\Feature\Controllers;

use App\Models\User;
use App\Models\PlaningYear;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PlaningYearControllerTest extends TestCase
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
    public function it_displays_index_view_with_planing_years(): void
    {
        $planingYears = PlaningYear::factory()
            ->count(5)
            ->create();

        $response = $this->get(route('planing-years.index'));

        $response
            ->assertOk()
            ->assertViewIs('app.planing_years.index')
            ->assertViewHas('planingYears');
    }

    /**
     * @test
     */
    public function it_displays_create_view_for_planing_year(): void
    {
        $response = $this->get(route('planing-years.create'));

        $response->assertOk()->assertViewIs('app.planing_years.create');
    }

    /**
     * @test
     */
    public function it_stores_the_planing_year(): void
    {
        $data = PlaningYear::factory()
            ->make()
            ->toArray();

        $response = $this->post(route('planing-years.store'), $data);

        $this->assertDatabaseHas('planing_years', $data);

        $planingYear = PlaningYear::latest('id')->first();

        $response->assertRedirect(route('planing-years.edit', $planingYear));
    }

    /**
     * @test
     */
    public function it_displays_show_view_for_planing_year(): void
    {
        $planingYear = PlaningYear::factory()->create();

        $response = $this->get(route('planing-years.show', $planingYear));

        $response
            ->assertOk()
            ->assertViewIs('app.planing_years.show')
            ->assertViewHas('planingYear');
    }

    /**
     * @test
     */
    public function it_displays_edit_view_for_planing_year(): void
    {
        $planingYear = PlaningYear::factory()->create();

        $response = $this->get(route('planing-years.edit', $planingYear));

        $response
            ->assertOk()
            ->assertViewIs('app.planing_years.edit')
            ->assertViewHas('planingYear');
    }

    /**
     * @test
     */
    public function it_updates_the_planing_year(): void
    {
        $planingYear = PlaningYear::factory()->create();

        $data = [];

        $response = $this->put(
            route('planing-years.update', $planingYear),
            $data
        );

        $data['id'] = $planingYear->id;

        $this->assertDatabaseHas('planing_years', $data);

        $response->assertRedirect(route('planing-years.edit', $planingYear));
    }

    /**
     * @test
     */
    public function it_deletes_the_planing_year(): void
    {
        $planingYear = PlaningYear::factory()->create();

        $response = $this->delete(route('planing-years.destroy', $planingYear));

        $response->assertRedirect(route('planing-years.index'));

        $this->assertModelMissing($planingYear);
    }
}
