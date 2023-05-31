<?php

namespace Tests\Feature\Controllers;

use App\Models\User;
use App\Models\ReportingPeriodTypeT;

use App\Models\ReportingPeriodType;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReportingPeriodTypeTControllerTest extends TestCase
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
    public function it_displays_index_view_with_reporting_period_type_ts(): void
    {
        $reportingPeriodTypeTs = ReportingPeriodTypeT::factory()
            ->count(5)
            ->create();

        $response = $this->get(route('reporting-period-type-ts.index'));

        $response
            ->assertOk()
            ->assertViewIs('app.reporting_period_type_ts.index')
            ->assertViewHas('reportingPeriodTypeTs');
    }

    /**
     * @test
     */
    public function it_displays_create_view_for_reporting_period_type_t(): void
    {
        $response = $this->get(route('reporting-period-type-ts.create'));

        $response
            ->assertOk()
            ->assertViewIs('app.reporting_period_type_ts.create');
    }

    /**
     * @test
     */
    public function it_stores_the_reporting_period_type_t(): void
    {
        $data = ReportingPeriodTypeT::factory()
            ->make()
            ->toArray();

        $response = $this->post(route('reporting-period-type-ts.store'), $data);

        $this->assertDatabaseHas('reporting_period_type_ts', $data);

        $reportingPeriodTypeT = ReportingPeriodTypeT::latest('id')->first();

        $response->assertRedirect(
            route('reporting-period-type-ts.edit', $reportingPeriodTypeT)
        );
    }

    /**
     * @test
     */
    public function it_displays_show_view_for_reporting_period_type_t(): void
    {
        $reportingPeriodTypeT = ReportingPeriodTypeT::factory()->create();

        $response = $this->get(
            route('reporting-period-type-ts.show', $reportingPeriodTypeT)
        );

        $response
            ->assertOk()
            ->assertViewIs('app.reporting_period_type_ts.show')
            ->assertViewHas('reportingPeriodTypeT');
    }

    /**
     * @test
     */
    public function it_displays_edit_view_for_reporting_period_type_t(): void
    {
        $reportingPeriodTypeT = ReportingPeriodTypeT::factory()->create();

        $response = $this->get(
            route('reporting-period-type-ts.edit', $reportingPeriodTypeT)
        );

        $response
            ->assertOk()
            ->assertViewIs('app.reporting_period_type_ts.edit')
            ->assertViewHas('reportingPeriodTypeT');
    }

    /**
     * @test
     */
    public function it_updates_the_reporting_period_type_t(): void
    {
        $reportingPeriodTypeT = ReportingPeriodTypeT::factory()->create();

        $reportingPeriodType = ReportingPeriodType::factory()->create();

        $data = [
            'name' => $this->faker->name(),
            'description' => $this->faker->text(),
            'reporting_period_type_id' => $reportingPeriodType->id,
        ];

        $response = $this->put(
            route('reporting-period-type-ts.update', $reportingPeriodTypeT),
            $data
        );

        $data['id'] = $reportingPeriodTypeT->id;

        $this->assertDatabaseHas('reporting_period_type_ts', $data);

        $response->assertRedirect(
            route('reporting-period-type-ts.edit', $reportingPeriodTypeT)
        );
    }

    /**
     * @test
     */
    public function it_deletes_the_reporting_period_type_t(): void
    {
        $reportingPeriodTypeT = ReportingPeriodTypeT::factory()->create();

        $response = $this->delete(
            route('reporting-period-type-ts.destroy', $reportingPeriodTypeT)
        );

        $response->assertRedirect(route('reporting-period-type-ts.index'));

        $this->assertModelMissing($reportingPeriodTypeT);
    }
}
