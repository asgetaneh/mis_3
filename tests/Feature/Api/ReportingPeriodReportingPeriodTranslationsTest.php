<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\ReportingPeriod;
use App\Models\ReportingPeriodTranslation;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReportingPeriodReportingPeriodTranslationsTest extends TestCase
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
    public function it_gets_reporting_period_reporting_period_translations(): void
    {
        $reportingPeriod = ReportingPeriod::factory()->create();
        $reportingPeriodTranslations = ReportingPeriodTranslation::factory()
            ->count(2)
            ->create([
                'reporting_period_id' => $reportingPeriod->id,
            ]);

        $response = $this->getJson(
            route(
                'api.reporting-periods.reporting-period-translations.index',
                $reportingPeriod
            )
        );

        $response->assertOk()->assertSee($reportingPeriodTranslations[0]->name);
    }

    /**
     * @test
     */
    public function it_stores_the_reporting_period_reporting_period_translations(): void
    {
        $reportingPeriod = ReportingPeriod::factory()->create();
        $data = ReportingPeriodTranslation::factory()
            ->make([
                'reporting_period_id' => $reportingPeriod->id,
            ])
            ->toArray();

        $response = $this->postJson(
            route(
                'api.reporting-periods.reporting-period-translations.store',
                $reportingPeriod
            ),
            $data
        );

        $this->assertDatabaseHas('reporting_period_translations', $data);

        $response->assertStatus(201)->assertJsonFragment($data);

        $reportingPeriodTranslation = ReportingPeriodTranslation::latest(
            'id'
        )->first();

        $this->assertEquals(
            $reportingPeriod->id,
            $reportingPeriodTranslation->reporting_period_id
        );
    }
}
