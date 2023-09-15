<?php

namespace Tests\Feature\Controllers;

use App\Models\User;
use App\Models\SuitableKpi;

use App\Models\Office;
use App\Models\PlaningYear;
use App\Models\KeyPeformanceIndicator;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SuitableKpiControllerTest extends TestCase
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
    public function it_displays_index_view_with_suitable_kpis(): void
    {
        $suitableKpis = SuitableKpi::factory()
            ->count(5)
            ->create();

        $response = $this->get(route('suitable-kpis.index'));

        $response
            ->assertOk()
            ->assertViewIs('app.suitable_kpis.index')
            ->assertViewHas('suitableKpis');
    }

    /**
     * @test
     */
    public function it_displays_create_view_for_suitable_kpi(): void
    {
        $response = $this->get(route('suitable-kpis.create'));

        $response->assertOk()->assertViewIs('app.suitable_kpis.create');
    }

    /**
     * @test
     */
    public function it_stores_the_suitable_kpi(): void
    {
        $data = SuitableKpi::factory()
            ->make()
            ->toArray();

        $response = $this->post(route('suitable-kpis.store'), $data);

        $this->assertDatabaseHas('suitable_kpis', $data);

        $suitableKpi = SuitableKpi::latest('id')->first();

        $response->assertRedirect(route('suitable-kpis.edit', $suitableKpi));
    }

    /**
     * @test
     */
    public function it_displays_show_view_for_suitable_kpi(): void
    {
        $suitableKpi = SuitableKpi::factory()->create();

        $response = $this->get(route('suitable-kpis.show', $suitableKpi));

        $response
            ->assertOk()
            ->assertViewIs('app.suitable_kpis.show')
            ->assertViewHas('suitableKpi');
    }

    /**
     * @test
     */
    public function it_displays_edit_view_for_suitable_kpi(): void
    {
        $suitableKpi = SuitableKpi::factory()->create();

        $response = $this->get(route('suitable-kpis.edit', $suitableKpi));

        $response
            ->assertOk()
            ->assertViewIs('app.suitable_kpis.edit')
            ->assertViewHas('suitableKpi');
    }

    /**
     * @test
     */
    public function it_updates_the_suitable_kpi(): void
    {
        $suitableKpi = SuitableKpi::factory()->create();

        $keyPeformanceIndicator = KeyPeformanceIndicator::factory()->create();
        $office = Office::factory()->create();
        $planingYear = PlaningYear::factory()->create();

        $data = [
            'key_peformance_indicator_id' => $keyPeformanceIndicator->id,
            'office_id' => $office->id,
            'planing_year_id' => $planingYear->id,
        ];

        $response = $this->put(
            route('suitable-kpis.update', $suitableKpi),
            $data
        );

        $data['id'] = $suitableKpi->id;

        $this->assertDatabaseHas('suitable_kpis', $data);

        $response->assertRedirect(route('suitable-kpis.edit', $suitableKpi));
    }

    /**
     * @test
     */
    public function it_deletes_the_suitable_kpi(): void
    {
        $suitableKpi = SuitableKpi::factory()->create();

        $response = $this->delete(route('suitable-kpis.destroy', $suitableKpi));

        $response->assertRedirect(route('suitable-kpis.index'));

        $this->assertModelMissing($suitableKpi);
    }
}
