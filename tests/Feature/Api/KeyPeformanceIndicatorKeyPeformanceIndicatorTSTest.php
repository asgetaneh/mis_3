<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\KeyPeformanceIndicator;
use App\Models\KeyPeformanceIndicatorT;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class KeyPeformanceIndicatorKeyPeformanceIndicatorTSTest extends TestCase
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
    public function it_gets_key_peformance_indicator_key_peformance_indicator_ts(): void
    {
        $keyPeformanceIndicator = KeyPeformanceIndicator::factory()->create();
        $keyPeformanceIndicatorTs = KeyPeformanceIndicatorT::factory()
            ->count(2)
            ->create([
                'translation_id' => $keyPeformanceIndicator->id,
            ]);

        $response = $this->getJson(
            route(
                'api.key-peformance-indicators.key-peformance-indicator-ts.index',
                $keyPeformanceIndicator
            )
        );

        $response->assertOk()->assertSee($keyPeformanceIndicatorTs[0]->name);
    }

    /**
     * @test
     */
    public function it_stores_the_key_peformance_indicator_key_peformance_indicator_ts(): void
    {
        $keyPeformanceIndicator = KeyPeformanceIndicator::factory()->create();
        $data = KeyPeformanceIndicatorT::factory()
            ->make([
                'translation_id' => $keyPeformanceIndicator->id,
            ])
            ->toArray();

        $response = $this->postJson(
            route(
                'api.key-peformance-indicators.key-peformance-indicator-ts.store',
                $keyPeformanceIndicator
            ),
            $data
        );

        $this->assertDatabaseHas('key_peformance_indicator_ts', $data);

        $response->assertStatus(201)->assertJsonFragment($data);

        $keyPeformanceIndicatorT = KeyPeformanceIndicatorT::latest(
            'id'
        )->first();

        $this->assertEquals(
            $keyPeformanceIndicator->id,
            $keyPeformanceIndicatorT->translation_id
        );
    }
}
