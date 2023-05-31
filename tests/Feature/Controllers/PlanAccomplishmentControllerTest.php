<?php

namespace Tests\Feature\Controllers;

use App\Models\User;
use App\Models\PlanAccomplishment;

use App\Models\SuitableKpi;
use App\Models\ReportingPeriod;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PlanAccomplishmentControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(
            User::factory()->create(['email' => 'admin@admin.com'])
        );

        $this->seed(\Database\Seeders\PermissionsSeeder::class);

        $this->withoutExceptionHandling();
    }

    /**
     * @test
     */
    public function it_displays_index_view_with_plan_accomplishments(): void
    {
        $planAccomplishments = PlanAccomplishment::factory()
            ->count(5)
            ->create();

        $response = $this->get(route('plan-accomplishments.index'));

        $response
            ->assertOk()
            ->assertViewIs('app.plan_accomplishments.index')
            ->assertViewHas('planAccomplishments');
    }

    /**
     * @test
     */
    public function it_displays_create_view_for_plan_accomplishment(): void
    {
        $response = $this->get(route('plan-accomplishments.create'));

        $response->assertOk()->assertViewIs('app.plan_accomplishments.create');
    }

    /**
     * @test
     */
    public function it_stores_the_plan_accomplishment(): void
    {
        $data = PlanAccomplishment::factory()
            ->make()
            ->toArray();

        $response = $this->post(route('plan-accomplishments.store'), $data);

        $this->assertDatabaseHas('plan_accomplishments', $data);

        $planAccomplishment = PlanAccomplishment::latest('id')->first();

        $response->assertRedirect(
            route('plan-accomplishments.edit', $planAccomplishment)
        );
    }

    /**
     * @test
     */
    public function it_displays_show_view_for_plan_accomplishment(): void
    {
        $planAccomplishment = PlanAccomplishment::factory()->create();

        $response = $this->get(
            route('plan-accomplishments.show', $planAccomplishment)
        );

        $response
            ->assertOk()
            ->assertViewIs('app.plan_accomplishments.show')
            ->assertViewHas('planAccomplishment');
    }

    /**
     * @test
     */
    public function it_displays_edit_view_for_plan_accomplishment(): void
    {
        $planAccomplishment = PlanAccomplishment::factory()->create();

        $response = $this->get(
            route('plan-accomplishments.edit', $planAccomplishment)
        );

        $response
            ->assertOk()
            ->assertViewIs('app.plan_accomplishments.edit')
            ->assertViewHas('planAccomplishment');
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

        $response = $this->put(
            route('plan-accomplishments.update', $planAccomplishment),
            $data
        );

        $data['id'] = $planAccomplishment->id;

        $this->assertDatabaseHas('plan_accomplishments', $data);

        $response->assertRedirect(
            route('plan-accomplishments.edit', $planAccomplishment)
        );
    }

    /**
     * @test
     */
    public function it_deletes_the_plan_accomplishment(): void
    {
        $planAccomplishment = PlanAccomplishment::factory()->create();

        $response = $this->delete(
            route('plan-accomplishments.destroy', $planAccomplishment)
        );

        $response->assertRedirect(route('plan-accomplishments.index'));

        $this->assertModelMissing($planAccomplishment);
    }
}
