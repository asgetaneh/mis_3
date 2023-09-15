<?php

namespace Tests\Feature\Controllers;

use App\Models\User;
use App\Models\ReportingPeriodType;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReportingPeriodTypeControllerTest extends TestCase
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
    public function it_displays_index_view_with_reporting_period_types(): void
    {
        $reportingPeriodTypes = ReportingPeriodType::factory()
            ->count(5)
            ->create();

        $response = $this->get(route('reporting-period-types.index'));

        $response
            ->assertOk()
            ->assertViewIs('app.reporting_period_types.index')
            ->assertViewHas('reportingPeriodTypes');
    }

    /**
     * @test
     */
    public function it_displays_create_view_for_reporting_period_type(): void
    {
        $response = $this->get(route('reporting-period-types.create'));

        $response
            ->assertOk()
            ->assertViewIs('app.reporting_period_types.create');
    }

    /**
     * @test
     */
    public function it_stores_the_reporting_period_type(): void
    {
        $data = ReportingPeriodType::factory()
            ->make()
            ->toArray();

        $response = $this->post(route('reporting-period-types.store'), $data);

        $this->assertDatabaseHas('reporting_period_types', $data);

        $reportingPeriodType = ReportingPeriodType::latest('id')->first();

        $response->assertRedirect(
            route('reporting-period-types.edit', $reportingPeriodType)
        );
    }

    /**
     * @test
     */
    public function it_displays_show_view_for_reporting_period_type(): void
    {
        $reportingPeriodType = ReportingPeriodType::factory()->create();

        $response = $this->get(
            route('reporting-period-types.show', $reportingPeriodType)
        );

        $response
            ->assertOk()
            ->assertViewIs('app.reporting_period_types.show')
            ->assertViewHas('reportingPeriodType');
    }

    /**
     * @test
     */
    public function it_displays_edit_view_for_reporting_period_type(): void
    {
        $reportingPeriodType = ReportingPeriodType::factory()->create();

        $response = $this->get(
            route('reporting-period-types.edit', $reportingPeriodType)
        );

        $response
            ->assertOk()
            ->assertViewIs('app.reporting_period_types.edit')
            ->assertViewHas('reportingPeriodType');
    }

    /**
     * @test
     */
    public function it_updates_the_reporting_period_type(): void
    {
        $reportingPeriodType = ReportingPeriodType::factory()->create();

        $data = [];

        $response = $this->put(
            route('reporting-period-types.update', $reportingPeriodType),
            $data
        );

        $data['id'] = $reportingPeriodType->id;

        $this->assertDatabaseHas('reporting_period_types', $data);

        $response->assertRedirect(
            route('reporting-period-types.edit', $reportingPeriodType)
        );
    }

    /**
     * @test
     */
    public function it_deletes_the_reporting_period_type(): void
    {
        $reportingPeriodType = ReportingPeriodType::factory()->create();

        $response = $this->delete(
            route('reporting-period-types.destroy', $reportingPeriodType)
        );

        $response->assertRedirect(route('reporting-period-types.index'));

        $this->assertModelMissing($reportingPeriodType);
    }
}
