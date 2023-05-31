<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\PlanAccomplishment;

use App\Models\SuitableKpi;
use App\Models\ReportingPeriod;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PlanAccomplishmentTest extends TestCase
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
    public function it_gets_plan_accomplishments_list(): void
    {
        $planAccomplishments = PlanAccomplishment::factory()
            ->count(5)
            ->create();

        $response = $this->getJson(route('api.plan-accomplishments.index'));

        $response->assertOk()->assertSee($planAccomplishments[0]->id);
    }

    /**
     * @test
     */
    public function it_stores_the_plan_accomplishment(): void
    {
        $data = PlanAccomplishment::factory()
            ->make()
            ->toArray();

        $response = $this->postJson(
            route('api.plan-accomplishments.store'),
            $data
        );

        $this->assertDatabaseHas('plan_accomplishments', $data);

        $response->assertStatus(201)->assertJsonFragment($data);
    }

    /**
     * @test
     */
    public function it_updates_the_plan_accomplishment(): void
    {
        $planAccomplishment = PlanAccomplishment::factory()->create();

        $suitableKpi = SuitableKpi::factory()->create();
        $reportingPeriod = ReportingPeriod::factory()->create();

        $data = [
            'plan_value' => $this->faker->randomNumber(2),
            'accom_value' => $this->faker->randomNumber(2),
            'plan_status' => $this->faker->numberBetween(0, 127),
            'accom_status' => $this->faker->numberBetween(0, 127),
            'suitable_kpi_id' => $suitableKpi->id,
            'reporting_period_id' => $reportingPeriod->id,
        ];

        $response = $this->putJson(
            route('api.plan-accomplishments.update', $planAccomplishment),
            $data
        );

        $data['id'] = $planAccomplishment->id;

        $this->assertDatabaseHas('plan_accomplishments', $data);

        $response->assertOk()->assertJsonFragment($data);
    }

    /**
     * @test
     */
    public function it_deletes_the_plan_accomplishment(): void
    {
        $planAccomplishment = PlanAccomplishment::factory()->create();

        $response = $this->deleteJson(
            route('api.plan-accomplishments.destroy', $planAccomplishment)
        );

        $this->assertModelMissing($planAccomplishment);

        $response->assertNoContent();
    }
}
