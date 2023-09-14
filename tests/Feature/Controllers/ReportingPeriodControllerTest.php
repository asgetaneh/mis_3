<?php

namespace Tests\Feature\Controllers;

use App\Models\User;
use App\Models\ReportingPeriod;

use App\Models\PlaningYear;
use App\Models\ReportingPeriodType;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReportingPeriodControllerTest extends TestCase
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
    public function it_displays_index_view_with_reporting_periods(): void
    {
        $reportingPeriods = ReportingPeriod::factory()
            ->count(5)
            ->create();

        $response = $this->get(route('reporting-periods.index'));

        $response
            ->assertOk()
            ->assertViewIs('app.reporting_periods.index')
            ->assertViewHas('reportingPeriods');
    }

    /**
     * @test
     */
    public function it_displays_create_view_for_reporting_period(): void
    {
        $response = $this->get(route('reporting-periods.create'));

        $response->assertOk()->assertViewIs('app.reporting_periods.create');
    }

    /**
     * @test
     */
    public function it_stores_the_reporting_period(): void
    {
        $data = ReportingPeriod::factory()
            ->make()
            ->toArray();

        $response = $this->post(route('reporting-periods.store'), $data);

        $this->assertDatabaseHas('reporting_periods', $data);

        $reportingPeriod = ReportingPeriod::latest('id')->first();

        $response->assertRedirect(
            route('reporting-periods.edit', $reportingPeriod)
        );
    }

    /**
     * @test
     */
    public function it_displays_show_view_for_reporting_period(): void
    {
        $reportingPeriod = ReportingPeriod::factory()->create();

        $response = $this->get(
            route('reporting-periods.show', $reportingPeriod)
        );

        $response
            ->assertOk()
            ->assertViewIs('app.reporting_periods.show')
            ->assertViewHas('reportingPeriod');
    }

    /**
     * @test
     */
    public function it_displays_edit_view_for_reporting_period(): void
    {
        $reportingPeriod = ReportingPeriod::factory()->create();

        $response = $this->get(
            route('reporting-periods.edit', $reportingPeriod)
        );

        $response
            ->assertOk()
            ->assertViewIs('app.reporting_periods.edit')
            ->assertViewHas('reportingPeriod');
    }

    /**
     * @test
     */
    public function it_updates_the_reporting_period(): void
    {
        $reportingPeriod = ReportingPeriod::factory()->create();

        $planingYear = PlaningYear::factory()->create();
        $reportingPeriodType = ReportingPeriodType::factory()->create();

        $data = [
            'start_date' => $this->faker->text(255),
            'end_date' => $this->faker->text(255),
            'planing_year_id' => $planingYear->id,
            'reporting_period_type_id' => $reportingPeriodType->id,
        ];

        $response = $this->put(
            route('reporting-periods.update', $reportingPeriod),
            $data
        );

        $data['id'] = $reportingPeriod->id;

        $this->assertDatabaseHas('reporting_periods', $data);

        $response->assertRedirect(
            route('reporting-periods.edit', $reportingPeriod)
        );
    }

    /**
     * @test
     */
    public function it_deletes_the_reporting_period(): void
    {
        $reportingPeriod = ReportingPeriod::factory()->create();

        $response = $this->delete(
            route('reporting-periods.destroy', $reportingPeriod)
        );

        $response->assertRedirect(route('reporting-periods.index'));

        $this->assertModelMissing($reportingPeriod);
    }
}
