<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\ReportingPeriodType;
use App\Models\ReportingPeriodTypeT;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReportingPeriodTypeReportingPeriodTypeTSTest extends TestCase
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
    public function it_gets_reporting_period_type_reporting_period_type_ts(): void
    {
        $reportingPeriodType = ReportingPeriodType::factory()->create();
        $reportingPeriodTypeTs = ReportingPeriodTypeT::factory()
            ->count(2)
            ->create([
                'reporting_period_type_id' => $reportingPeriodType->id,
            ]);

        $response = $this->getJson(
            route(
                'api.reporting-period-types.reporting-period-type-ts.index',
                $reportingPeriodType
            )
        );

        $response->assertOk()->assertSee($reportingPeriodTypeTs[0]->name);
    }

    /**
     * @test
     */
    public function it_stores_the_reporting_period_type_reporting_period_type_ts(): void
    {
        $reportingPeriodType = ReportingPeriodType::factory()->create();
        $data = ReportingPeriodTypeT::factory()
            ->make([
                'reporting_period_type_id' => $reportingPeriodType->id,
            ])
            ->toArray();

        $response = $this->postJson(
            route(
                'api.reporting-period-types.reporting-period-type-ts.store',
                $reportingPeriodType
            ),
            $data
        );

        $this->assertDatabaseHas('reporting_period_type_ts', $data);

        $response->assertStatus(201)->assertJsonFragment($data);

        $reportingPeriodTypeT = ReportingPeriodTypeT::latest('id')->first();

        $this->assertEquals(
            $reportingPeriodType->id,
            $reportingPeriodTypeT->reporting_period_type_id
        );
    }
}
