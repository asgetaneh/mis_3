<?php

namespace Tests\Feature\Controllers;

use App\Models\User;
use App\Models\PerspectiveTranslation;

use App\Models\Perspective;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PerspectiveTranslationControllerTest extends TestCase
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
    public function it_displays_index_view_with_perspective_translations(): void
    {
        $perspectiveTranslations = PerspectiveTranslation::factory()
            ->count(5)
            ->create();

        $response = $this->get(route('perspective-translations.index'));

        $response
            ->assertOk()
            ->assertViewIs('app.perspective_translations.index')
            ->assertViewHas('perspectiveTranslations');
    }

    /**
     * @test
     */
    public function it_displays_create_view_for_perspective_translation(): void
    {
        $response = $this->get(route('perspective-translations.create'));

        $response
            ->assertOk()
            ->assertViewIs('app.perspective_translations.create');
    }

    /**
     * @test
     */
    public function it_stores_the_perspective_translation(): void
    {
        $data = PerspectiveTranslation::factory()
            ->make()
            ->toArray();

        $response = $this->post(route('perspective-translations.store'), $data);

        $this->assertDatabaseHas('perspective_translations', $data);

        $perspectiveTranslation = PerspectiveTranslation::latest('id')->first();

        $response->assertRedirect(
            route('perspective-translations.edit', $perspectiveTranslation)
        );
    }

    /**
     * @test
     */
    public function it_displays_show_view_for_perspective_translation(): void
    {
        $perspectiveTranslation = PerspectiveTranslation::factory()->create();

        $response = $this->get(
            route('perspective-translations.show', $perspectiveTranslation)
        );

        $response
            ->assertOk()
            ->assertViewIs('app.perspective_translations.show')
            ->assertViewHas('perspectiveTranslation');
    }

    /**
     * @test
     */
    public function it_displays_edit_view_for_perspective_translation(): void
    {
        $perspectiveTranslation = PerspectiveTranslation::factory()->create();

        $response = $this->get(
            route('perspective-translations.edit', $perspectiveTranslation)
        );

        $response
            ->assertOk()
            ->assertViewIs('app.perspective_translations.edit')
            ->assertViewHas('perspectiveTranslation');
    }

    /**
     * @test
     */
    public function it_updates_the_perspective_translation(): void
    {
        $perspectiveTranslation = PerspectiveTranslation::factory()->create();

        $perspective = Perspective::factory()->create();

        $data = [
            'name' => $this->faker->text(255),
            'description' => $this->faker->sentence(15),
            'translation_id' => $this->faker->randomNumber(),
            'translation_id' => $perspective->id,
        ];

        $response = $this->put(
            route('perspective-translations.update', $perspectiveTranslation),
            $data
        );

        $data['id'] = $perspectiveTranslation->id;

        $this->assertDatabaseHas('perspective_translations', $data);

        $response->assertRedirect(
            route('perspective-translations.edit', $perspectiveTranslation)
        );
    }

    /**
     * @test
     */
    public function it_deletes_the_perspective_translation(): void
    {
        $perspectiveTranslation = PerspectiveTranslation::factory()->create();

        $response = $this->delete(
            route('perspective-translations.destroy', $perspectiveTranslation)
        );

        $response->assertRedirect(route('perspective-translations.index'));

        $this->assertModelMissing($perspectiveTranslation);
    }
}
