<?php

namespace Tests\Feature\Controllers;

use App\Models\User;
use App\Models\KeyPeformanceIndicatorTranslation;

use App\Models\KeyPeformanceIndicator;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class KeyPeformanceIndicatorTranslationControllerTest extends TestCase
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
    public function it_displays_index_view_with_key_peformance_indicator_translations(): void
    {
        $keyPeformanceIndicatorTranslations = KeyPeformanceIndicatorTranslation::factory()
            ->count(5)
            ->create();

        $response = $this->get(
            route('key-peformance-indicator-translations.index')
        );

        $response
            ->assertOk()
            ->assertViewIs('app.key_peformance_indicator_translations.index')
            ->assertViewHas('keyPeformanceIndicatorTranslations');
    }

    /**
     * @test
     */
    public function it_displays_create_view_for_key_peformance_indicator_translation(): void
    {
        $response = $this->get(
            route('key-peformance-indicator-translations.create')
        );

        $response
            ->assertOk()
            ->assertViewIs('app.key_peformance_indicator_translations.create');
    }

    /**
     * @test
     */
    public function it_stores_the_key_peformance_indicator_translation(): void
    {
        $data = KeyPeformanceIndicatorTranslation::factory()
            ->make()
            ->toArray();

        $response = $this->post(
            route('key-peformance-indicator-translations.store'),
            $data
        );

        $this->assertDatabaseHas(
            'key_peformance_indicator_translations',
            $data
        );

        $keyPeformanceIndicatorTranslation = KeyPeformanceIndicatorTranslation::latest(
            'id'
        )->first();

        $response->assertRedirect(
            route(
                'key-peformance-indicator-translations.edit',
                $keyPeformanceIndicatorTranslation
            )
        );
    }

    /**
     * @test
     */
    public function it_displays_show_view_for_key_peformance_indicator_translation(): void
    {
        $keyPeformanceIndicatorTranslation = KeyPeformanceIndicatorTranslation::factory()->create();

        $response = $this->get(
            route(
                'key-peformance-indicator-translations.show',
                $keyPeformanceIndicatorTranslation
            )
        );

        $response
            ->assertOk()
            ->assertViewIs('app.key_peformance_indicator_translations.show')
            ->assertViewHas('keyPeformanceIndicatorTranslation');
    }

    /**
     * @test
     */
    public function it_displays_edit_view_for_key_peformance_indicator_translation(): void
    {
        $keyPeformanceIndicatorTranslation = KeyPeformanceIndicatorTranslation::factory()->create();

        $response = $this->get(
            route(
                'key-peformance-indicator-translations.edit',
                $keyPeformanceIndicatorTranslation
            )
        );

        $response
            ->assertOk()
            ->assertViewIs('app.key_peformance_indicator_translations.edit')
            ->assertViewHas('keyPeformanceIndicatorTranslation');
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

        $response = $this->put(
            route(
                'key-peformance-indicator-translations.update',
                $keyPeformanceIndicatorTranslation
            ),
            $data
        );

        $data['id'] = $keyPeformanceIndicatorTranslation->id;

        $this->assertDatabaseHas(
            'key_peformance_indicator_translations',
            $data
        );

        $response->assertRedirect(
            route(
                'key-peformance-indicator-translations.edit',
                $keyPeformanceIndicatorTranslation
            )
        );
    }

    /**
     * @test
     */
    public function it_deletes_the_key_peformance_indicator_translation(): void
    {
        $keyPeformanceIndicatorTranslation = KeyPeformanceIndicatorTranslation::factory()->create();

        $response = $this->delete(
            route(
                'key-peformance-indicator-translations.destroy',
                $keyPeformanceIndicatorTranslation
            )
        );

        $response->assertRedirect(
            route('key-peformance-indicator-translations.index')
        );

        $this->assertModelMissing($keyPeformanceIndicatorTranslation);
    }
}
