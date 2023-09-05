<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Strategy;
use App\Models\KeyPeformanceIndicator;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StrategyKeyPeformanceIndicatorsTest extends TestCase
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
    public function it_gets_strategy_key_peformance_indicators(): void
    {
        $strategy = Strategy::factory()->create();
        $keyPeformanceIndicators = KeyPeformanceIndicator::factory()
            ->count(2)
            ->create([
                'strategy_id' => $strategy->id,
            ]);

        $response = $this->getJson(
            route('api.strategies.key-peformance-indicators.index', $strategy)
        );

        $response->assertOk()->assertSee($keyPeformanceIndicators[0]->id);
    }

    /**
     * @test
     */
    public function it_stores_the_strategy_key_peformance_indicators(): void
    {
        $strategy = Strategy::factory()->create();
        $data = KeyPeformanceIndicator::factory()
            ->make([
                'strategy_id' => $strategy->id,
            ])
            ->toArray();

        $response = $this->postJson(
            route('api.strategies.key-peformance-indicators.store', $strategy),
            $data
        );

        $this->assertDatabaseHas('key_peformance_indicators', $data);

        $response->assertStatus(201)->assertJsonFragment($data);

        $keyPeformanceIndicator = KeyPeformanceIndicator::latest('id')->first();

        $this->assertEquals(
            $strategy->id,
            $keyPeformanceIndicator->strategy_id
        );
    }
}
