<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\ReportingPeriodType;
use App\Models\KeyPeformanceIndicator;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReportingPeriodTypeKeyPeformanceIndicatorsTest extends TestCase
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
    public function it_gets_reporting_period_type_key_peformance_indicators(): void
    {
        $reportingPeriodType = ReportingPeriodType::factory()->create();
        $keyPeformanceIndicators = KeyPeformanceIndicator::factory()
            ->count(2)
            ->create([
                'reporting_period_type_id' => $reportingPeriodType->id,
            ]);

        $response = $this->getJson(
            route(
                'api.reporting-period-types.key-peformance-indicators.index',
                $reportingPeriodType
            )
        );

        $response->assertOk()->assertSee($keyPeformanceIndicators[0]->id);
    }

    /**
     * @test
     */
    public function it_stores_the_reporting_period_type_key_peformance_indicators(): void
    {
        $reportingPeriodType = ReportingPeriodType::factory()->create();
        $data = KeyPeformanceIndicator::factory()
            ->make([
                'reporting_period_type_id' => $reportingPeriodType->id,
            ])
            ->toArray();

        $response = $this->postJson(
            route(
                'api.reporting-period-types.key-peformance-indicators.store',
                $reportingPeriodType
            ),
            $data
        );

        $this->assertDatabaseHas('key_peformance_indicators', $data);

        $response->assertStatus(201)->assertJsonFragment($data);

        $keyPeformanceIndicator = KeyPeformanceIndicator::latest('id')->first();

        $this->assertEquals(
            $reportingPeriodType->id,
            $keyPeformanceIndicator->reporting_period_type_id
        );
    }
}
