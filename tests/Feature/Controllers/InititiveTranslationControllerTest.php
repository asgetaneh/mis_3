<?php

namespace Tests\Feature\Controllers;

use App\Models\User;
use App\Models\InititiveTranslation;

use App\Models\Inititive;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class InititiveTranslationControllerTest extends TestCase
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
    public function it_displays_index_view_with_inititive_translations(): void
    {
        $inititiveTranslations = InititiveTranslation::factory()
            ->count(5)
            ->create();

        $response = $this->get(route('inititive-translations.index'));

        $response
            ->assertOk()
            ->assertViewIs('app.inititive_translations.index')
            ->assertViewHas('inititiveTranslations');
    }

    /**
     * @test
     */
    public function it_displays_create_view_for_inititive_translation(): void
    {
        $response = $this->get(route('inititive-translations.create'));

        $response
            ->assertOk()
            ->assertViewIs('app.inititive_translations.create');
    }

    /**
     * @test
     */
    public function it_stores_the_inititive_translation(): void
    {
        $data = InititiveTranslation::factory()
            ->make()
            ->toArray();

        $response = $this->post(route('inititive-translations.store'), $data);

        $this->assertDatabaseHas('inititive_translations', $data);

        $inititiveTranslation = InititiveTranslation::latest('id')->first();

        $response->assertRedirect(
            route('inititive-translations.edit', $inititiveTranslation)
        );
    }

    /**
     * @test
     */
    public function it_displays_show_view_for_inititive_translation(): void
    {
        $inititiveTranslation = InititiveTranslation::factory()->create();

        $response = $this->get(
            route('inititive-translations.show', $inititiveTranslation)
        );

        $response
            ->assertOk()
            ->assertViewIs('app.inititive_translations.show')
            ->assertViewHas('inititiveTranslation');
    }

    /**
     * @test
     */
    public function it_displays_edit_view_for_inititive_translation(): void
    {
        $inititiveTranslation = InititiveTranslation::factory()->create();

        $response = $this->get(
            route('inititive-translations.edit', $inititiveTranslation)
        );

        $response
            ->assertOk()
            ->assertViewIs('app.inititive_translations.edit')
            ->assertViewHas('inititiveTranslation');
    }

    /**
     * @test
     */
    public function it_updates_the_inititive_translation(): void
    {
        $inititiveTranslation = InititiveTranslation::factory()->create();

        $inititive = Inititive::factory()->create();

        $data = [
            'name' => $this->faker->name(),
            'description' => $this->faker->text(),
            'inititive_id' => $inititive->id,
        ];

        $response = $this->put(
            route('inititive-translations.update', $inititiveTranslation),
            $data
        );

        $data['id'] = $inititiveTranslation->id;

        $this->assertDatabaseHas('inititive_translations', $data);

        $response->assertRedirect(
            route('inititive-translations.edit', $inititiveTranslation)
        );
    }

    /**
     * @test
     */
    public function it_deletes_the_inititive_translation(): void
    {
        $inititiveTranslation = InititiveTranslation::factory()->create();

        $response = $this->delete(
            route('inititive-translations.destroy', $inititiveTranslation)
        );

        $response->assertRedirect(route('inititive-translations.index'));

        $this->assertModelMissing($inititiveTranslation);
    }
}
