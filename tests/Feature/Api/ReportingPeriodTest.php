<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\ReportingPeriod;

use App\Models\PlaningYear;
use App\Models\ReportingPeriodType;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReportingPeriodTest extends TestCase
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
    public function it_gets_reporting_periods_list(): void
    {
        $reportingPeriods = ReportingPeriod::factory()
            ->count(5)
            ->create();

        $response = $this->getJson(route('api.reporting-periods.index'));

        $response->assertOk()->assertSee($reportingPeriods[0]->start_date);
    }

    /**
     * @test
     */
    public function it_stores_the_reporting_period(): void
    {
        $data = ReportingPeriod::factory()
            ->make()
            ->toArray();

        $response = $this->postJson(
            route('api.reporting-periods.store'),
            $data
        );

        $this->assertDatabaseHas('reporting_periods', $data);

        $response->assertStatus(201)->assertJsonFragment($data);
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

        $response = $this->putJson(
            route('api.reporting-periods.update', $reportingPeriod),
            $data
        );

        $data['id'] = $reportingPeriod->id;

        $this->assertDatabaseHas('reporting_periods', $data);

        $response->assertOk()->assertJsonFragment($data);
    }

    /**
     * @test
     */
    public function it_deletes_the_reporting_period(): void
    {
        $reportingPeriod = ReportingPeriod::factory()->create();

        $response = $this->deleteJson(
            route('api.reporting-periods.destroy', $reportingPeriod)
        );

        $this->assertModelMissing($reportingPeriod);

        $response->assertNoContent();
    }
}
