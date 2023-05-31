<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Office;
use App\Models\KeyPeformanceIndicator;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OfficeKeyPeformanceIndicatorsTest extends TestCase
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
    public function it_gets_office_key_peformance_indicators(): void
    {
        $office = Office::factory()->create();
        $keyPeformanceIndicator = KeyPeformanceIndicator::factory()->create();

        $office->keyPeformanceIndicators()->attach($keyPeformanceIndicator);

        $response = $this->getJson(
            route('api.offices.key-peformance-indicators.index', $office)
        );

        $response->assertOk()->assertSee($keyPeformanceIndicator->id);
    }

    /**
     * @test
     */
    public function it_can_attach_key_peformance_indicators_to_office(): void
    {
        $office = Office::factory()->create();
        $keyPeformanceIndicator = KeyPeformanceIndicator::factory()->create();

        $response = $this->postJson(
            route('api.offices.key-peformance-indicators.store', [
                $office,
                $keyPeformanceIndicator,
            ])
        );

        $response->assertNoContent();

        $this->assertTrue(
            $office
                ->keyPeformanceIndicators()
                ->where(
                    'key_peformance_indicators.id',
                    $keyPeformanceIndicator->id
                )
                ->exists()
        );
    }

    /**
     * @test
     */
    public function it_can_detach_key_peformance_indicators_from_office(): void
    {
        $office = Office::factory()->create();
        $keyPeformanceIndicator = KeyPeformanceIndicator::factory()->create();

        $response = $this->deleteJson(
            route('api.offices.key-peformance-indicators.store', [
                $office,
                $keyPeformanceIndicator,
            ])
        );

        $response->assertNoContent();

        $this->assertFalse(
            $office
                ->keyPeformanceIndicators()
                ->where(
                    'key_peformance_indicators.id',
                    $keyPeformanceIndicator->id
                )
                ->exists()
        );
    }
}
