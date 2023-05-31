<?php

namespace Tests\Feature\Controllers;

use App\Models\User;
use App\Models\OfficeTranslation;

use App\Models\Office;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OfficeTranslationControllerTest extends TestCase
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
    public function it_displays_index_view_with_office_translations(): void
    {
        $officeTranslations = OfficeTranslation::factory()
            ->count(5)
            ->create();

        $response = $this->get(route('office-translations.index'));

        $response
            ->assertOk()
            ->assertViewIs('app.office_translations.index')
            ->assertViewHas('officeTranslations');
    }

    /**
     * @test
     */
    public function it_displays_create_view_for_office_translation(): void
    {
        $response = $this->get(route('office-translations.create'));

        $response->assertOk()->assertViewIs('app.office_translations.create');
    }

    /**
     * @test
     */
    public function it_stores_the_office_translation(): void
    {
        $data = OfficeTranslation::factory()
            ->make()
            ->toArray();

        $response = $this->post(route('office-translations.store'), $data);

        $this->assertDatabaseHas('office_translations', $data);

        $officeTranslation = OfficeTranslation::latest('id')->first();

        $response->assertRedirect(
            route('office-translations.edit', $officeTranslation)
        );
    }

    /**
     * @test
     */
    public function it_displays_show_view_for_office_translation(): void
    {
        $officeTranslation = OfficeTranslation::factory()->create();

        $response = $this->get(
            route('office-translations.show', $officeTranslation)
        );

        $response
            ->assertOk()
            ->assertViewIs('app.office_translations.show')
            ->assertViewHas('officeTranslation');
    }

    /**
     * @test
     */
    public function it_displays_edit_view_for_office_translation(): void
    {
        $officeTranslation = OfficeTranslation::factory()->create();

        $response = $this->get(
            route('office-translations.edit', $officeTranslation)
        );

        $response
            ->assertOk()
            ->assertViewIs('app.office_translations.edit')
            ->assertViewHas('officeTranslation');
    }

    /**
     * @test
     */
    public function it_updates_the_office_translation(): void
    {
        $officeTranslation = OfficeTranslation::factory()->create();

        $office = Office::factory()->create();

        $data = [
            'name' => $this->faker->name(),
            'description' => $this->faker->sentence(15),
            'translation_id' => $office->id,
        ];

        $response = $this->put(
            route('office-translations.update', $officeTranslation),
            $data
        );

        $data['id'] = $officeTranslation->id;

        $this->assertDatabaseHas('office_translations', $data);

        $response->assertRedirect(
            route('office-translations.edit', $officeTranslation)
        );
    }

    /**
     * @test
     */
    public function it_deletes_the_office_translation(): void
    {
        $officeTranslation = OfficeTranslation::factory()->create();

        $response = $this->delete(
            route('office-translations.destroy', $officeTranslation)
        );

        $response->assertRedirect(route('office-translations.index'));

        $this->assertModelMissing($officeTranslation);
    }
}
