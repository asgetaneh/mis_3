<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\PlaningYear;
use App\Models\ReportingPeriod;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PlaningYearReportingPeriodsTest extends TestCase
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
    public function it_gets_planing_year_reporting_periods(): void
    {
        $planingYear = PlaningYear::factory()->create();
        $reportingPeriods = ReportingPeriod::factory()
            ->count(2)
            ->create([
                'planing_year_id' => $planingYear->id,
            ]);

        $response = $this->getJson(
            route('api.planing-years.reporting-periods.index', $planingYear)
        );

        $response->assertOk()->assertSee($reportingPeriods[0]->start_date);
    }

    /**
     * @test
     */
    public function it_stores_the_planing_year_reporting_periods(): void
    {
        $planingYear = PlaningYear::factory()->create();
        $data = ReportingPeriod::factory()
            ->make([
                'planing_year_id' => $planingYear->id,
            ])
            ->toArray();

        $response = $this->postJson(
            route('api.planing-years.reporting-periods.store', $planingYear),
            $data
        );

        $this->assertDatabaseHas('reporting_periods', $data);

        $response->assertStatus(201)->assertJsonFragment($data);

        $reportingPeriod = ReportingPeriod::latest('id')->first();

        $this->assertEquals(
            $planingYear->id,
            $reportingPeriod->planing_year_id
        );
    }
}
