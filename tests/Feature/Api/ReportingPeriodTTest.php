<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\ReportingPeriodT;

use App\Models\ReportingPeriod;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReportingPeriodTTest extends TestCase
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
    public function it_gets_reporting_period_ts_list(): void
    {
        $reportingPeriodTs = ReportingPeriodT::factory()
            ->count(5)
            ->create();

        $response = $this->getJson(route('api.reporting-period-ts.index'));

        $response->assertOk()->assertSee($reportingPeriodTs[0]->name);
    }

    /**
     * @test
     */
    public function it_stores_the_reporting_period_t(): void
    {
        $data = ReportingPeriodT::factory()
            ->make()
            ->toArray();

        $response = $this->postJson(
            route('api.reporting-period-ts.store'),
            $data
        );

        $this->assertDatabaseHas('reporting_period_ts', $data);

        $response->assertStatus(201)->assertJsonFragment($data);
    }

    /**
     * @test
     */
    public function it_updates_the_reporting_period_t(): void
    {
        $reportingPeriodT = ReportingPeriodT::factory()->create();

        $reportingPeriod = ReportingPeriod::factory()->create();

        $data = [
            'name' => $this->faker->name(),
            'description' => $this->faker->text(),
            'reporting_period_id' => $reportingPeriod->id,
        ];

        $response = $this->putJson(
            route('api.reporting-period-ts.update', $reportingPeriodT),
            $data
        );

        $data['id'] = $reportingPeriodT->id;

        $this->assertDatabaseHas('reporting_period_ts', $data);

        $response->assertOk()->assertJsonFragment($data);
    }

    /**
     * @test
     */
    public function it_deletes_the_reporting_period_t(): void
    {
        $reportingPeriodT = ReportingPeriodT::factory()->create();

        $response = $this->deleteJson(
            route('api.reporting-period-ts.destroy', $reportingPeriodT)
        );

        $this->assertModelMissing($reportingPeriodT);

        $response->assertNoContent();
    }
}
