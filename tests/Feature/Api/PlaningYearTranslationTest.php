<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\PlaningYearTranslation;

use App\Models\PlaningYear;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PlaningYearTranslationTest extends TestCase
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
    public function it_gets_planing_year_translations_list(): void
    {
        $planingYearTranslations = PlaningYearTranslation::factory()
            ->count(5)
            ->create();

        $response = $this->getJson(
            route('api.planing-year-translations.index')
        );

        $response->assertOk()->assertSee($planingYearTranslations[0]->name);
    }

    /**
     * @test
     */
    public function it_stores_the_planing_year_translation(): void
    {
        $data = PlaningYearTranslation::factory()
            ->make()
            ->toArray();

        $response = $this->postJson(
            route('api.planing-year-translations.store'),
            $data
        );

        $this->assertDatabaseHas('planing_year_translations', $data);

        $response->assertStatus(201)->assertJsonFragment($data);
    }

    /**
     * @test
     */
    public function it_updates_the_planing_year_translation(): void
    {
        $planingYearTranslation = PlaningYearTranslation::factory()->create();

        $planingYear = PlaningYear::factory()->create();

        $data = [
            'name' => $this->faker->name(),
            'description' => $this->faker->sentence(15),
            'planing_year_id' => $planingYear->id,
        ];

        $response = $this->putJson(
            route(
                'api.planing-year-translations.update',
                $planingYearTranslation
            ),
            $data
        );

        $data['id'] = $planingYearTranslation->id;

        $this->assertDatabaseHas('planing_year_translations', $data);

        $response->assertOk()->assertJsonFragment($data);
    }

    /**
     * @test
     */
    public function it_deletes_the_planing_year_translation(): void
    {
        $planingYearTranslation = PlaningYearTranslation::factory()->create();

        $response = $this->deleteJson(
            route(
                'api.planing-year-translations.destroy',
                $planingYearTranslation
            )
        );

        $this->assertModelMissing($planingYearTranslation);

        $response->assertNoContent();
    }
}
