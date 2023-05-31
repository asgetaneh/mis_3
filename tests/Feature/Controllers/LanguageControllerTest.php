<?php

namespace Tests\Feature\Controllers;

use App\Models\User;
use App\Models\Language;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LanguageControllerTest extends TestCase
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
    public function it_displays_index_view_with_languages(): void
    {
        $languages = Language::factory()
            ->count(5)
            ->create();

        $response = $this->get(route('languages.index'));

        $response
            ->assertOk()
            ->assertViewIs('app.languages.index')
            ->assertViewHas('languages');
    }

    /**
     * @test
     */
    public function it_displays_create_view_for_language(): void
    {
        $response = $this->get(route('languages.create'));

        $response->assertOk()->assertViewIs('app.languages.create');
    }

    /**
     * @test
     */
    public function it_stores_the_language(): void
    {
        $data = Language::factory()
            ->make()
            ->toArray();

        $response = $this->post(route('languages.store'), $data);

        $this->assertDatabaseHas('languages', $data);

        $language = Language::latest('id')->first();

        $response->assertRedirect(route('languages.edit', $language));
    }

    /**
     * @test
     */
    public function it_displays_show_view_for_language(): void
    {
        $language = Language::factory()->create();

        $response = $this->get(route('languages.show', $language));

        $response
            ->assertOk()
            ->assertViewIs('app.languages.show')
            ->assertViewHas('language');
    }

    /**
     * @test
     */
    public function it_displays_edit_view_for_language(): void
    {
        $language = Language::factory()->create();

        $response = $this->get(route('languages.edit', $language));

        $response
            ->assertOk()
            ->assertViewIs('app.languages.edit')
            ->assertViewHas('language');
    }

    /**
     * @test
     */
    public function it_updates_the_language(): void
    {
        $language = Language::factory()->create();

        $user = User::factory()->create();

        $data = [
            'name' => $this->faker->name(),
            'description' => $this->faker->text(),
            'locale' => $this->faker->locale(),
            'created_by_id' => $user->id,
        ];

        $response = $this->put(route('languages.update', $language), $data);

        $data['id'] = $language->id;

        $this->assertDatabaseHas('languages', $data);

        $response->assertRedirect(route('languages.edit', $language));
    }

    /**
     * @test
     */
    public function it_deletes_the_language(): void
    {
        $language = Language::factory()->create();

        $response = $this->delete(route('languages.destroy', $language));

        $response->assertRedirect(route('languages.index'));

        $this->assertModelMissing($language);
    }
}
