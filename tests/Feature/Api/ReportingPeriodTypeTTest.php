<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\ReportingPeriodTypeT;

use App\Models\ReportingPeriodType;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReportingPeriodTypeTTest extends TestCase
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
    public function it_gets_reporting_period_type_ts_list(): void
    {
        $reportingPeriodTypeTs = ReportingPeriodTypeT::factory()
            ->count(5)
            ->create();

        $response = $this->getJson(route('api.reporting-period-type-ts.index'));

        $response->assertOk()->assertSee($reportingPeriodTypeTs[0]->name);
    }

    /**
     * @test
     */
    public function it_stores_the_reporting_period_type_t(): void
    {
        $data = ReportingPeriodTypeT::factory()
            ->make()
            ->toArray();

        $response = $this->postJson(
            route('api.reporting-period-type-ts.store'),
            $data
        );

        $this->assertDatabaseHas('reporting_period_type_ts', $data);

        $response->assertStatus(201)->assertJsonFragment($data);
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

        $response = $this->putJson(
            route('api.reporting-period-type-ts.update', $reportingPeriodTypeT),
            $data
        );

        $data['id'] = $reportingPeriodTypeT->id;

        $this->assertDatabaseHas('reporting_period_type_ts', $data);

        $response->assertOk()->assertJsonFragment($data);
    }

    /**
     * @test
     */
    public function it_deletes_the_reporting_period_type_t(): void
    {
        $reportingPeriodTypeT = ReportingPeriodTypeT::factory()->create();

        $response = $this->deleteJson(
            route('api.reporting-period-type-ts.destroy', $reportingPeriodTypeT)
        );

        $this->assertModelMissing($reportingPeriodTypeT);

        $response->assertNoContent();
    }
}
