<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\ReportingPeriod;
use App\Models\PlanAccomplishment;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReportingPeriodPlanAccomplishmentsTest extends TestCase
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
    public function it_gets_reporting_period_plan_accomplishments(): void
    {
        $reportingPeriod = ReportingPeriod::factory()->create();
        $planAccomplishments = PlanAccomplishment::factory()
            ->count(2)
            ->create([
                'reporting_period_id' => $reportingPeriod->id,
            ]);

        $response = $this->getJson(
            route(
                'api.reporting-periods.plan-accomplishments.index',
                $reportingPeriod
            )
        );

        $response->assertOk()->assertSee($planAccomplishments[0]->id);
    }

    /**
     * @test
     */
    public function it_stores_the_reporting_period_plan_accomplishments(): void
    {
        $reportingPeriod = ReportingPeriod::factory()->create();
        $data = PlanAccomplishment::factory()
            ->make([
                'reporting_period_id' => $reportingPeriod->id,
            ])
            ->toArray();

        $response = $this->postJson(
            route(
                'api.reporting-periods.plan-accomplishments.store',
                $reportingPeriod
            ),
            $data
        );

        $this->assertDatabaseHas('plan_accomplishments', $data);

        $response->assertStatus(201)->assertJsonFragment($data);

        $planAccomplishment = PlanAccomplishment::latest('id')->first();

        $this->assertEquals(
            $reportingPeriod->id,
            $planAccomplishment->reporting_period_id
        );
    }
}
