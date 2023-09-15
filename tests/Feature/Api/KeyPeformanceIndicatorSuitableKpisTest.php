<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\SuitableKpi;
use App\Models\KeyPeformanceIndicator;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class KeyPeformanceIndicatorSuitableKpisTest extends TestCase
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
    public function it_gets_key_peformance_indicator_suitable_kpis(): void
    {
        $keyPeformanceIndicator = KeyPeformanceIndicator::factory()->create();
        $suitableKpis = SuitableKpi::factory()
            ->count(2)
            ->create([
                'key_peformance_indicator_id' => $keyPeformanceIndicator->id,
            ]);

        $response = $this->getJson(
            route(
                'api.key-peformance-indicators.suitable-kpis.index',
                $keyPeformanceIndicator
            )
        );

        $response->assertOk()->assertSee($suitableKpis[0]->id);
    }

    /**
     * @test
     */
    public function it_stores_the_key_peformance_indicator_suitable_kpis(): void
    {
        $keyPeformanceIndicator = KeyPeformanceIndicator::factory()->create();
        $data = SuitableKpi::factory()
            ->make([
                'key_peformance_indicator_id' => $keyPeformanceIndicator->id,
            ])
            ->toArray();

        $response = $this->postJson(
            route(
                'api.key-peformance-indicators.suitable-kpis.store',
                $keyPeformanceIndicator
            ),
            $data
        );

        $this->assertDatabaseHas('suitable_kpis', $data);

        $response->assertStatus(201)->assertJsonFragment($data);

        $suitableKpi = SuitableKpi::latest('id')->first();

        $this->assertEquals(
            $keyPeformanceIndicator->id,
            $suitableKpi->key_peformance_indicator_id
        );
    }
}
