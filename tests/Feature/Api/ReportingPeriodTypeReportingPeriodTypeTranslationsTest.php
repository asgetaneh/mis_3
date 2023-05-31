<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\ReportingPeriodType;
use App\Models\ReportingPeriodTypeTranslation;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReportingPeriodTypeReportingPeriodTypeTranslationsTest extends TestCase
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
    public function it_gets_reporting_period_type_reporting_period_type_translations(): void
    {
        $reportingPeriodType = ReportingPeriodType::factory()->create();
        $reportingPeriodTypeTranslations = ReportingPeriodTypeTranslation::factory()
            ->count(2)
            ->create([
                'reporting_period_type_id' => $reportingPeriodType->id,
            ]);

        $response = $this->getJson(
            route(
                'api.reporting-period-types.reporting-period-type-translations.index',
                $reportingPeriodType
            )
        );

        $response
            ->assertOk()
            ->assertSee($reportingPeriodTypeTranslations[0]->name);
    }

    /**
     * @test
     */
    public function it_stores_the_reporting_period_type_reporting_period_type_translations(): void
    {
        $reportingPeriodType = ReportingPeriodType::factory()->create();
        $data = ReportingPeriodTypeTranslation::factory()
            ->make([
                'reporting_period_type_id' => $reportingPeriodType->id,
            ])
            ->toArray();

        $response = $this->postJson(
            route(
                'api.reporting-period-types.reporting-period-type-translations.store',
                $reportingPeriodType
            ),
            $data
        );

        $this->assertDatabaseHas('reporting_period_type_translations', $data);

        $response->assertStatus(201)->assertJsonFragment($data);

        $reportingPeriodTypeTranslation = ReportingPeriodTypeTranslation::latest(
            'id'
        )->first();

        $this->assertEquals(
            $reportingPeriodType->id,
            $reportingPeriodTypeTranslation->reporting_period_type_id
        );
    }
}
