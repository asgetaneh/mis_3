<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\ReportingPeriodType;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReportingPeriodTypeTest extends TestCase
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
    public function it_gets_reporting_period_types_list(): void
    {
        $reportingPeriodTypes = ReportingPeriodType::factory()
            ->count(5)
            ->create();

        $response = $this->getJson(route('api.reporting-period-types.index'));

        $response->assertOk()->assertSee($reportingPeriodTypes[0]->id);
    }

    /**
     * @test
     */
    public function it_stores_the_reporting_period_type(): void
    {
        $data = ReportingPeriodType::factory()
            ->make()
            ->toArray();

        $response = $this->postJson(
            route('api.reporting-period-types.store'),
            $data
        );

        $this->assertDatabaseHas('reporting_period_types', $data);

        $response->assertStatus(201)->assertJsonFragment($data);
    }

    /**
     * @test
     */
    public function it_updates_the_reporting_period_type(): void
    {
        $reportingPeriodType = ReportingPeriodType::factory()->create();

        $data = [];

        $response = $this->putJson(
            route('api.reporting-period-types.update', $reportingPeriodType),
            $data
        );

        $data['id'] = $reportingPeriodType->id;

        $this->assertDatabaseHas('reporting_period_types', $data);

        $response->assertOk()->assertJsonFragment($data);
    }

    /**
     * @test
     */
    public function it_deletes_the_reporting_period_type(): void
    {
        $reportingPeriodType = ReportingPeriodType::factory()->create();

        $response = $this->deleteJson(
            route('api.reporting-period-types.destroy', $reportingPeriodType)
        );

        $this->assertModelMissing($reportingPeriodType);

        $response->assertNoContent();
    }
}
