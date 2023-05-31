<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Office;
use App\Models\KeyPeformanceIndicator;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class KeyPeformanceIndicatorOfficesTest extends TestCase
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
    public function it_gets_key_peformance_indicator_offices(): void
    {
        $keyPeformanceIndicator = KeyPeformanceIndicator::factory()->create();
        $office = Office::factory()->create();

        $keyPeformanceIndicator->offices()->attach($office);

        $response = $this->getJson(
            route(
                'api.key-peformance-indicators.offices.index',
                $keyPeformanceIndicator
            )
        );

        $response->assertOk()->assertSee($office->id);
    }

    /**
     * @test
     */
    public function it_can_attach_offices_to_key_peformance_indicator(): void
    {
        $keyPeformanceIndicator = KeyPeformanceIndicator::factory()->create();
        $office = Office::factory()->create();

        $response = $this->postJson(
            route('api.key-peformance-indicators.offices.store', [
                $keyPeformanceIndicator,
                $office,
            ])
        );

        $response->assertNoContent();

        $this->assertTrue(
            $keyPeformanceIndicator
                ->offices()
                ->where('offices.id', $office->id)
                ->exists()
        );
    }

    /**
     * @test
     */
    public function it_can_detach_offices_from_key_peformance_indicator(): void
    {
        $keyPeformanceIndicator = KeyPeformanceIndicator::factory()->create();
        $office = Office::factory()->create();

        $response = $this->deleteJson(
            route('api.key-peformance-indicators.offices.store', [
                $keyPeformanceIndicator,
                $office,
            ])
        );

        $response->assertNoContent();

        $this->assertFalse(
            $keyPeformanceIndicator
                ->offices()
                ->where('offices.id', $office->id)
                ->exists()
        );
    }
}
