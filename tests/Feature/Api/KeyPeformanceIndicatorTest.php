<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\KeyPeformanceIndicator;

use App\Models\Strategy;
use App\Models\Objective;
use App\Models\ReportingPeriodType;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class KeyPeformanceIndicatorTest extends TestCase
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
    public function it_gets_key_peformance_indicators_list(): void
    {
        $keyPeformanceIndicators = KeyPeformanceIndicator::factory()
            ->count(5)
            ->create();

        $response = $this->getJson(
            route('api.key-peformance-indicators.index')
        );

        $response->assertOk()->assertSee($keyPeformanceIndicators[0]->id);
    }

    /**
     * @test
     */
    public function it_stores_the_key_peformance_indicator(): void
    {
        $data = KeyPeformanceIndicator::factory()
            ->make()
            ->toArray();

        $response = $this->postJson(
            route('api.key-peformance-indicators.store'),
            $data
        );

        $this->assertDatabaseHas('key_peformance_indicators', $data);

        $response->assertStatus(201)->assertJsonFragment($data);
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

        $response = $this->putJson(
            route(
                'api.key-peformance-indicators.update',
                $keyPeformanceIndicator
            ),
            $data
        );

        $data['id'] = $keyPeformanceIndicator->id;

        $this->assertDatabaseHas('key_peformance_indicators', $data);

        $response->assertOk()->assertJsonFragment($data);
    }

    /**
     * @test
     */
    public function it_deletes_the_key_peformance_indicator(): void
    {
        $keyPeformanceIndicator = KeyPeformanceIndicator::factory()->create();

        $response = $this->deleteJson(
            route(
                'api.key-peformance-indicators.destroy',
                $keyPeformanceIndicator
            )
        );

        $this->assertModelMissing($keyPeformanceIndicator);

        $response->assertNoContent();
    }
}
