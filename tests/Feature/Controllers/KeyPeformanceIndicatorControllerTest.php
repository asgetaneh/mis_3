<?php

namespace Tests\Feature\Controllers;

use App\Models\User;
use App\Models\KeyPeformanceIndicator;

use App\Models\Strategy;
use App\Models\Objective;
use App\Models\ReportingPeriodType;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class KeyPeformanceIndicatorControllerTest extends TestCase
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
    public function it_displays_index_view_with_key_peformance_indicators(): void
    {
        $keyPeformanceIndicators = KeyPeformanceIndicator::factory()
            ->count(5)
            ->create();

        $response = $this->get(route('key-peformance-indicators.index'));

        $response
            ->assertOk()
            ->assertViewIs('app.key_peformance_indicators.index')
            ->assertViewHas('keyPeformanceIndicators');
    }

    /**
     * @test
     */
    public function it_displays_create_view_for_key_peformance_indicator(): void
    {
        $response = $this->get(route('key-peformance-indicators.create'));

        $response
            ->assertOk()
            ->assertViewIs('app.key_peformance_indicators.create');
    }

    /**
     * @test
     */
    public function it_stores_the_key_peformance_indicator(): void
    {
        $data = KeyPeformanceIndicator::factory()
            ->make()
            ->toArray();

        $response = $this->post(
            route('key-peformance-indicators.store'),
            $data
        );

        $this->assertDatabaseHas('key_peformance_indicators', $data);

        $keyPeformanceIndicator = KeyPeformanceIndicator::latest('id')->first();

        $response->assertRedirect(
            route('key-peformance-indicators.edit', $keyPeformanceIndicator)
        );
    }

    /**
     * @test
     */
    public function it_displays_show_view_for_key_peformance_indicator(): void
    {
        $keyPeformanceIndicator = KeyPeformanceIndicator::factory()->create();

        $response = $this->get(
            route('key-peformance-indicators.show', $keyPeformanceIndicator)
        );

        $response
            ->assertOk()
            ->assertViewIs('app.key_peformance_indicators.show')
            ->assertViewHas('keyPeformanceIndicator');
    }

    /**
     * @test
     */
    public function it_displays_edit_view_for_key_peformance_indicator(): void
    {
        $keyPeformanceIndicator = KeyPeformanceIndicator::factory()->create();

        $response = $this->get(
            route('key-peformance-indicators.edit', $keyPeformanceIndicator)
        );

        $response
            ->assertOk()
            ->assertViewIs('app.key_peformance_indicators.edit')
            ->assertViewHas('keyPeformanceIndicator');
    }

    /**
     * @test
     */
    public function it_updates_the_key_peformance_indicator(): void
    {
        $keyPeformanceIndicator = KeyPeformanceIndicator::factory()->create();

        $objective = Objective::factory()->create();
        $strategy = Strategy::factory()->create();
        $user = User::factory()->create();
        $reportingPeriodType = ReportingPeriodType::factory()->create();

        $data = [
            'weight' => $this->faker->randomNumber(2),
            'objective_id' => $objective->id,
            'strategy_id' => $strategy->id,
            'created_by_id' => $user->id,
            'reporting_period_type_id' => $reportingPeriodType->id,
        ];

        $response = $this->put(
            route('key-peformance-indicators.update', $keyPeformanceIndicator),
            $data
        );

        $data['id'] = $keyPeformanceIndicator->id;

        $this->assertDatabaseHas('key_peformance_indicators', $data);

        $response->assertRedirect(
            route('key-peformance-indicators.edit', $keyPeformanceIndicator)
        );
    }

    /**
     * @test
     */
    public function it_deletes_the_key_peformance_indicator(): void
    {
        $keyPeformanceIndicator = KeyPeformanceIndicator::factory()->create();

        $response = $this->delete(
            route('key-peformance-indicators.destroy', $keyPeformanceIndicator)
        );

        $response->assertRedirect(route('key-peformance-indicators.index'));

        $this->assertModelMissing($keyPeformanceIndicator);
    }
}
