<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\ReportingPeriod;
use App\Models\ReportingPeriodT;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReportingPeriodReportingPeriodTSTest extends TestCase
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
    public function it_gets_reporting_period_reporting_period_ts(): void
    {
        $reportingPeriod = ReportingPeriod::factory()->create();
        $reportingPeriodTs = ReportingPeriodT::factory()
            ->count(2)
            ->create([
                'reporting_period_id' => $reportingPeriod->id,
            ]);

        $response = $this->getJson(
            route(
                'api.reporting-periods.reporting-period-ts.index',
                $reportingPeriod
            )
        );

        $response->assertOk()->assertSee($reportingPeriodTs[0]->name);
    }

    /**
     * @test
     */
    public function it_stores_the_reporting_period_reporting_period_ts(): void
    {
        $reportingPeriod = ReportingPeriod::factory()->create();
        $data = ReportingPeriodT::factory()
            ->make([
                'reporting_period_id' => $reportingPeriod->id,
            ])
            ->toArray();

        $response = $this->postJson(
            route(
                'api.reporting-periods.reporting-period-ts.store',
                $reportingPeriod
            ),
            $data
        );

        $this->assertDatabaseHas('reporting_period_ts', $data);

        $response->assertStatus(201)->assertJsonFragment($data);

        $reportingPeriodT = ReportingPeriodT::latest('id')->first();

        $this->assertEquals(
            $reportingPeriod->id,
            $reportingPeriodT->reporting_period_id
        );
    }
}
