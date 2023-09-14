<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\SuitableKpi;
use App\Models\PlanAccomplishment;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SuitableKpiPlanAccomplishmentsTest extends TestCase
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
    public function it_gets_suitable_kpi_plan_accomplishments(): void
    {
        $suitableKpi = SuitableKpi::factory()->create();
        $planAccomplishments = PlanAccomplishment::factory()
            ->count(2)
            ->create([
                'suitable_kpi_id' => $suitableKpi->id,
            ]);

        $response = $this->getJson(
            route('api.suitable-kpis.plan-accomplishments.index', $suitableKpi)
        );

        $response->assertOk()->assertSee($planAccomplishments[0]->id);
    }

    /**
     * @test
     */
    public function it_stores_the_suitable_kpi_plan_accomplishments(): void
    {
        $suitableKpi = SuitableKpi::factory()->create();
        $data = PlanAccomplishment::factory()
            ->make([
                'suitable_kpi_id' => $suitableKpi->id,
            ])
            ->toArray();

        $response = $this->postJson(
            route('api.suitable-kpis.plan-accomplishments.store', $suitableKpi),
            $data
        );

        $this->assertDatabaseHas('plan_accomplishments', $data);

        $response->assertStatus(201)->assertJsonFragment($data);

        $planAccomplishment = PlanAccomplishment::latest('id')->first();

        $this->assertEquals(
            $suitableKpi->id,
            $planAccomplishment->suitable_kpi_id
        );
    }
}
