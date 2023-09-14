<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\KeyPeformanceIndicatorTranslation;

use App\Models\KeyPeformanceIndicator;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class KeyPeformanceIndicatorTranslationTest extends TestCase
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
    public function it_gets_key_peformance_indicator_translations_list(): void
    {
        $keyPeformanceIndicatorTranslations = KeyPeformanceIndicatorTranslation::factory()
            ->count(5)
            ->create();

        $response = $this->getJson(
            route('api.key-peformance-indicator-translations.index')
        );

        $response
            ->assertOk()
            ->assertSee($keyPeformanceIndicatorTranslations[0]->name);
    }

    /**
     * @test
     */
    public function it_stores_the_key_peformance_indicator_translation(): void
    {
        $data = KeyPeformanceIndicatorTranslation::factory()
            ->make()
            ->toArray();

        $response = $this->postJson(
            route('api.key-peformance-indicator-translations.store'),
            $data
        );

        $this->assertDatabaseHas(
            'key_peformance_indicator_translations',
            $data
        );

        $response->assertStatus(201)->assertJsonFragment($data);
    }

    /**
     * @test
     */
    public function it_updates_the_key_peformance_indicator_translation(): void
    {
        $keyPeformanceIndicatorTranslation = KeyPeformanceIndicatorTranslation::factory()->create();

        $keyPeformanceIndicator = KeyPeformanceIndicator::factory()->create();

        $data = [
            'name' => $this->faker->name(),
            'description' => $this->faker->text(),
            'out_put' => $this->faker->text(),
            'out_come' => $this->faker->text(),
            'translation_id' => $keyPeformanceIndicator->id,
        ];

        $response = $this->putJson(
            route(
                'api.key-peformance-indicator-translations.update',
                $keyPeformanceIndicatorTranslation
            ),
            $data
        );

        $data['id'] = $keyPeformanceIndicatorTranslation->id;

        $this->assertDatabaseHas(
            'key_peformance_indicator_translations',
            $data
        );

        $response->assertOk()->assertJsonFragment($data);
    }

    /**
     * @test
     */
    public function it_deletes_the_key_peformance_indicator_translation(): void
    {
        $keyPeformanceIndicatorTranslation = KeyPeformanceIndicatorTranslation::factory()->create();

        $response = $this->deleteJson(
            route(
                'api.key-peformance-indicator-translations.destroy',
                $keyPeformanceIndicatorTranslation
            )
        );

        $this->assertModelMissing($keyPeformanceIndicatorTranslation);

        $response->assertNoContent();
    }
}
