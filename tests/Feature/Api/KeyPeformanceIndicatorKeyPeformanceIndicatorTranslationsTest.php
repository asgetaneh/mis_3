<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\KeyPeformanceIndicator;
use App\Models\KeyPeformanceIndicatorTranslation;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class KeyPeformanceIndicatorKeyPeformanceIndicatorTranslationsTest extends
    TestCase
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
    public function it_gets_key_peformance_indicator_key_peformance_indicator_translations(): void
    {
        $keyPeformanceIndicator = KeyPeformanceIndicator::factory()->create();
        $keyPeformanceIndicatorTranslations = KeyPeformanceIndicatorTranslation::factory()
            ->count(2)
            ->create([
                'translation_id' => $keyPeformanceIndicator->id,
            ]);

        $response = $this->getJson(
            route(
                'api.key-peformance-indicators.key-peformance-indicator-translations.index',
                $keyPeformanceIndicator
            )
        );

        $response
            ->assertOk()
            ->assertSee($keyPeformanceIndicatorTranslations[0]->name);
    }

    /**
     * @test
     */
    public function it_stores_the_key_peformance_indicator_key_peformance_indicator_translations(): void
    {
        $keyPeformanceIndicator = KeyPeformanceIndicator::factory()->create();
        $data = KeyPeformanceIndicatorTranslation::factory()
            ->make([
                'translation_id' => $keyPeformanceIndicator->id,
            ])
            ->toArray();

        $response = $this->postJson(
            route(
                'api.key-peformance-indicators.key-peformance-indicator-translations.store',
                $keyPeformanceIndicator
            ),
            $data
        );

        $this->assertDatabaseHas(
            'key_peformance_indicator_translations',
            $data
        );

        $response->assertStatus(201)->assertJsonFragment($data);

        $keyPeformanceIndicatorTranslation = KeyPeformanceIndicatorTranslation::latest(
            'id'
        )->first();

        $this->assertEquals(
            $keyPeformanceIndicator->id,
            $keyPeformanceIndicatorTranslation->translation_id
        );
    }
}
