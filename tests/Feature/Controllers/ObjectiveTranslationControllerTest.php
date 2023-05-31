<?php

namespace Tests\Feature\Controllers;

use App\Models\User;
use App\Models\ObjectiveTranslation;

use App\Models\Objective;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ObjectiveTranslationControllerTest extends TestCase
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
    public function it_displays_index_view_with_objective_translations(): void
    {
        $objectiveTranslations = ObjectiveTranslation::factory()
            ->count(5)
            ->create();

        $response = $this->get(route('objective-translations.index'));

        $response
            ->assertOk()
            ->assertViewIs('app.objective_translations.index')
            ->assertViewHas('objectiveTranslations');
    }

    /**
     * @test
     */
    public function it_displays_create_view_for_objective_translation(): void
    {
        $response = $this->get(route('objective-translations.create'));

        $response
            ->assertOk()
            ->assertViewIs('app.objective_translations.create');
    }

    /**
     * @test
     */
    public function it_stores_the_objective_translation(): void
    {
        $data = ObjectiveTranslation::factory()
            ->make()
            ->toArray();

        $response = $this->post(route('objective-translations.store'), $data);

        $this->assertDatabaseHas('objective_translations', $data);

        $objectiveTranslation = ObjectiveTranslation::latest('id')->first();

        $response->assertRedirect(
            route('objective-translations.edit', $objectiveTranslation)
        );
    }

    /**
     * @test
     */
    public function it_displays_show_view_for_objective_translation(): void
    {
        $objectiveTranslation = ObjectiveTranslation::factory()->create();

        $response = $this->get(
            route('objective-translations.show', $objectiveTranslation)
        );

        $response
            ->assertOk()
            ->assertViewIs('app.objective_translations.show')
            ->assertViewHas('objectiveTranslation');
    }

    /**
     * @test
     */
    public function it_displays_edit_view_for_objective_translation(): void
    {
        $objectiveTranslation = ObjectiveTranslation::factory()->create();

        $response = $this->get(
            route('objective-translations.edit', $objectiveTranslation)
        );

        $response
            ->assertOk()
            ->assertViewIs('app.objective_translations.edit')
            ->assertViewHas('objectiveTranslation');
    }

    /**
     * @test
     */
    public function it_updates_the_objective_translation(): void
    {
        $objectiveTranslation = ObjectiveTranslation::factory()->create();

        $objective = Objective::factory()->create();

        $data = [
            'name' => $this->faker->text(255),
            'description' => $this->faker->text(),
            'out_put' => $this->faker->text(),
            'out_come' => $this->faker->text(),
            'translation_id' => $objective->id,
        ];

        $response = $this->put(
            route('objective-translations.update', $objectiveTranslation),
            $data
        );

        $data['id'] = $objectiveTranslation->id;

        $this->assertDatabaseHas('objective_translations', $data);

        $response->assertRedirect(
            route('objective-translations.edit', $objectiveTranslation)
        );
    }

    /**
     * @test
     */
    public function it_deletes_the_objective_translation(): void
    {
        $objectiveTranslation = ObjectiveTranslation::factory()->create();

        $response = $this->delete(
            route('objective-translations.destroy', $objectiveTranslation)
        );

        $response->assertRedirect(route('objective-translations.index'));

        $this->assertModelMissing($objectiveTranslation);
    }
}
