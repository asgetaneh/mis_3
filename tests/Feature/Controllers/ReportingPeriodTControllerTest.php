<?php

namespace Tests\Feature\Controllers;

use App\Models\User;
use App\Models\ReportingPeriodT;

use App\Models\ReportingPeriod;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReportingPeriodTControllerTest extends TestCase
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
    public function it_displays_index_view_with_reporting_period_ts(): void
    {
        $reportingPeriodTs = ReportingPeriodT::factory()
            ->count(5)
            ->create();

        $response = $this->get(route('reporting-period-ts.index'));

        $response
            ->assertOk()
            ->assertViewIs('app.reporting_period_ts.index')
            ->assertViewHas('reportingPeriodTs');
    }

    /**
     * @test
     */
    public function it_displays_create_view_for_reporting_period_t(): void
    {
        $response = $this->get(route('reporting-period-ts.create'));

        $response->assertOk()->assertViewIs('app.reporting_period_ts.create');
    }

    /**
     * @test
     */
    public function it_stores_the_reporting_period_t(): void
    {
        $data = ReportingPeriodT::factory()
            ->make()
            ->toArray();

        $response = $this->post(route('reporting-period-ts.store'), $data);

        $this->assertDatabaseHas('reporting_period_ts', $data);

        $reportingPeriodT = ReportingPeriodT::latest('id')->first();

        $response->assertRedirect(
            route('reporting-period-ts.edit', $reportingPeriodT)
        );
    }

    /**
     * @test
     */
    public function it_displays_show_view_for_reporting_period_t(): void
    {
        $reportingPeriodT = ReportingPeriodT::factory()->create();

        $response = $this->get(
            route('reporting-period-ts.show', $reportingPeriodT)
        );

        $response
            ->assertOk()
            ->assertViewIs('app.reporting_period_ts.show')
            ->assertViewHas('reportingPeriodT');
    }

    /**
     * @test
     */
    public function it_displays_edit_view_for_reporting_period_t(): void
    {
        $reportingPeriodT = ReportingPeriodT::factory()->create();

        $response = $this->get(
            route('reporting-period-ts.edit', $reportingPeriodT)
        );

        $response
            ->assertOk()
            ->assertViewIs('app.reporting_period_ts.edit')
            ->assertViewHas('reportingPeriodT');
    }

    /**
     * @test
     */
    public function it_updates_the_reporting_period_t(): void
    {
        $reportingPeriodT = ReportingPeriodT::factory()->create();

        $reportingPeriod = ReportingPeriod::factory()->create();

        $data = [
            'name' => $this->faker->name(),
            'description' => $this->faker->text(),
            'reporting_period_id' => $reportingPeriod->id,
        ];

        $response = $this->put(
            route('reporting-period-ts.update', $reportingPeriodT),
            $data
        );

        $data['id'] = $reportingPeriodT->id;

        $this->assertDatabaseHas('reporting_period_ts', $data);

        $response->assertRedirect(
            route('reporting-period-ts.edit', $reportingPeriodT)
        );
    }

    /**
     * @test
     */
    public function it_deletes_the_reporting_period_t(): void
    {
        $reportingPeriodT = ReportingPeriodT::factory()->create();

        $response = $this->delete(
            route('reporting-period-ts.destroy', $reportingPeriodT)
        );

        $response->assertRedirect(route('reporting-period-ts.index'));

        $this->assertModelMissing($reportingPeriodT);
    }
}
