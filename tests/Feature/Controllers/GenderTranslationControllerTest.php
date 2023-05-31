<?php

namespace Tests\Feature\Controllers;

use App\Models\User;
use App\Models\GenderTranslation;

use App\Models\Gender;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GenderTranslationControllerTest extends TestCase
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
    public function it_displays_index_view_with_gender_translations(): void
    {
        $genderTranslations = GenderTranslation::factory()
            ->count(5)
            ->create();

        $response = $this->get(route('gender-translations.index'));

        $response
            ->assertOk()
            ->assertViewIs('app.gender_translations.index')
            ->assertViewHas('genderTranslations');
    }

    /**
     * @test
     */
    public function it_displays_create_view_for_gender_translation(): void
    {
        $response = $this->get(route('gender-translations.create'));

        $response->assertOk()->assertViewIs('app.gender_translations.create');
    }

    /**
     * @test
     */
    public function it_stores_the_gender_translation(): void
    {
        $data = GenderTranslation::factory()
            ->make()
            ->toArray();

        $response = $this->post(route('gender-translations.store'), $data);

        $this->assertDatabaseHas('gender_translations', $data);

        $genderTranslation = GenderTranslation::latest('id')->first();

        $response->assertRedirect(
            route('gender-translations.edit', $genderTranslation)
        );
    }

    /**
     * @test
     */
    public function it_displays_show_view_for_gender_translation(): void
    {
        $genderTranslation = GenderTranslation::factory()->create();

        $response = $this->get(
            route('gender-translations.show', $genderTranslation)
        );

        $response
            ->assertOk()
            ->assertViewIs('app.gender_translations.show')
            ->assertViewHas('genderTranslation');
    }

    /**
     * @test
     */
    public function it_displays_edit_view_for_gender_translation(): void
    {
        $genderTranslation = GenderTranslation::factory()->create();

        $response = $this->get(
            route('gender-translations.edit', $genderTranslation)
        );

        $response
            ->assertOk()
            ->assertViewIs('app.gender_translations.edit')
            ->assertViewHas('genderTranslation');
    }

    /**
     * @test
     */
    public function it_updates_the_gender_translation(): void
    {
        $genderTranslation = GenderTranslation::factory()->create();

        $gender = Gender::factory()->create();

        $data = [
            'name' => $this->faker->name(),
            'description' => $this->faker->text(),
            'gender_id' => $gender->id,
        ];

        $response = $this->put(
            route('gender-translations.update', $genderTranslation),
            $data
        );

        $data['id'] = $genderTranslation->id;

        $this->assertDatabaseHas('gender_translations', $data);

        $response->assertRedirect(
            route('gender-translations.edit', $genderTranslation)
        );
    }

    /**
     * @test
     */
    public function it_deletes_the_gender_translation(): void
    {
        $genderTranslation = GenderTranslation::factory()->create();

        $response = $this->delete(
            route('gender-translations.destroy', $genderTranslation)
        );

        $response->assertRedirect(route('gender-translations.index'));

        $this->assertModelMissing($genderTranslation);
    }
}
