<?php

namespace Tests\Feature\Controllers;

use App\Models\User;
use App\Models\PlaningYearTranslation;

use App\Models\PlaningYear;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PlaningYearTranslationControllerTest extends TestCase
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
    public function it_displays_index_view_with_planing_year_translations(): void
    {
        $planingYearTranslations = PlaningYearTranslation::factory()
            ->count(5)
            ->create();

        $response = $this->get(route('planing-year-translations.index'));

        $response
            ->assertOk()
            ->assertViewIs('app.planing_year_translations.index')
            ->assertViewHas('planingYearTranslations');
    }

    /**
     * @test
     */
    public function it_displays_create_view_for_planing_year_translation(): void
    {
        $response = $this->get(route('planing-year-translations.create'));

        $response
            ->assertOk()
            ->assertViewIs('app.planing_year_translations.create');
    }

    /**
     * @test
     */
    public function it_stores_the_planing_year_translation(): void
    {
        $data = PlaningYearTranslation::factory()
            ->make()
            ->toArray();

        $response = $this->post(
            route('planing-year-translations.store'),
            $data
        );

        $this->assertDatabaseHas('planing_year_translations', $data);

        $planingYearTranslation = PlaningYearTranslation::latest('id')->first();

        $response->assertRedirect(
            route('planing-year-translations.edit', $planingYearTranslation)
        );
    }

    /**
     * @test
     */
    public function it_displays_show_view_for_planing_year_translation(): void
    {
        $planingYearTranslation = PlaningYearTranslation::factory()->create();

        $response = $this->get(
            route('planing-year-translations.show', $planingYearTranslation)
        );

        $response
            ->assertOk()
            ->assertViewIs('app.planing_year_translations.show')
            ->assertViewHas('planingYearTranslation');
    }

    /**
     * @test
     */
    public function it_displays_edit_view_for_planing_year_translation(): void
    {
        $planingYearTranslation = PlaningYearTranslation::factory()->create();

        $response = $this->get(
            route('planing-year-translations.edit', $planingYearTranslation)
        );

        $response
            ->assertOk()
            ->assertViewIs('app.planing_year_translations.edit')
            ->assertViewHas('planingYearTranslation');
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

        $response = $this->put(
            route('planing-year-translations.update', $planingYearTranslation),
            $data
        );

        $data['id'] = $planingYearTranslation->id;

        $this->assertDatabaseHas('planing_year_translations', $data);

        $response->assertRedirect(
            route('planing-year-translations.edit', $planingYearTranslation)
        );
    }

    /**
     * @test
     */
    public function it_deletes_the_planing_year_translation(): void
    {
        $planingYearTranslation = PlaningYearTranslation::factory()->create();

        $response = $this->delete(
            route('planing-year-translations.destroy', $planingYearTranslation)
        );

        $response->assertRedirect(route('planing-year-translations.index'));

        $this->assertModelMissing($planingYearTranslation);
    }
}
