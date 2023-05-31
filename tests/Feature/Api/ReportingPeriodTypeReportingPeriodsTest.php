<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\ReportingPeriod;
use App\Models\ReportingPeriodType;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReportingPeriodTypeReportingPeriodsTest extends TestCase
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
    public function it_gets_reporting_period_type_reporting_periods(): void
    {
        $reportingPeriodType = ReportingPeriodType::factory()->create();
        $reportingPeriods = ReportingPeriod::factory()
            ->count(2)
            ->create([
                'reporting_period_type_id' => $reportingPeriodType->id,
            ]);

        $response = $this->getJson(
            route(
                'api.reporting-period-types.reporting-periods.index',
                $reportingPeriodType
            )
        );

        $response->assertOk()->assertSee($reportingPeriods[0]->start_date);
    }

    /**
     * @test
     */
    public function it_stores_the_reporting_period_type_reporting_periods(): void
    {
        $reportingPeriodType = ReportingPeriodType::factory()->create();
        $data = ReportingPeriod::factory()
            ->make([
                'reporting_period_type_id' => $reportingPeriodType->id,
            ])
            ->toArray();

        $response = $this->postJson(
            route(
                'api.reporting-period-types.reporting-periods.store',
                $reportingPeriodType
            ),
            $data
        );

        $this->assertDatabaseHas('reporting_periods', $data);

        $response->assertStatus(201)->assertJsonFragment($data);

        $reportingPeriod = ReportingPeriod::latest('id')->first();

        $this->assertEquals(
            $reportingPeriodType->id,
            $reportingPeriod->reporting_period_type_id
        );
    }
}
