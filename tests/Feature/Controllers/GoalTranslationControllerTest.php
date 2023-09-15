<?php

namespace Tests\Feature\Controllers;

use App\Models\User;
use App\Models\GoalTranslation;

use App\Models\Goal;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GoalTranslationControllerTest extends TestCase
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
    public function it_displays_index_view_with_goal_translations(): void
    {
        $goalTranslations = GoalTranslation::factory()
            ->count(5)
            ->create();

        $response = $this->get(route('goal-translations.index'));

        $response
            ->assertOk()
            ->assertViewIs('app.goal_translations.index')
            ->assertViewHas('goalTranslations');
    }

    /**
     * @test
     */
    public function it_displays_create_view_for_goal_translation(): void
    {
        $response = $this->get(route('goal-translations.create'));

        $response->assertOk()->assertViewIs('app.goal_translations.create');
    }

    /**
     * @test
     */
    public function it_stores_the_goal_translation(): void
    {
        $data = GoalTranslation::factory()
            ->make()
            ->toArray();

        $response = $this->post(route('goal-translations.store'), $data);

        $this->assertDatabaseHas('goal_translations', $data);

        $goalTranslation = GoalTranslation::latest('id')->first();

        $response->assertRedirect(
            route('goal-translations.edit', $goalTranslation)
        );
    }

    /**
     * @test
     */
    public function it_displays_show_view_for_goal_translation(): void
    {
        $goalTranslation = GoalTranslation::factory()->create();

        $response = $this->get(
            route('goal-translations.show', $goalTranslation)
        );

        $response
            ->assertOk()
            ->assertViewIs('app.goal_translations.show')
            ->assertViewHas('goalTranslation');
    }

    /**
     * @test
     */
    public function it_displays_edit_view_for_goal_translation(): void
    {
        $goalTranslation = GoalTranslation::factory()->create();

        $response = $this->get(
            route('goal-translations.edit', $goalTranslation)
        );

        $response
            ->assertOk()
            ->assertViewIs('app.goal_translations.edit')
            ->assertViewHas('goalTranslation');
    }

    /**
     * @test
     */
    public function it_updates_the_goal_translation(): void
    {
        $goalTranslation = GoalTranslation::factory()->create();

        $goal = Goal::factory()->create();

        $data = [
            'translation_id' => $this->faker->randomNumber(),
            'name' => $this->faker->text(255),
            'out_put' => $this->faker->text(),
            'out_come' => $this->faker->text(),
            'description' => $this->faker->sentence(15),
            'locale' => $this->faker->locale(),
            'translation_id' => $goal->id,
        ];

        $response = $this->put(
            route('goal-translations.update', $goalTranslation),
            $data
        );

        $data['id'] = $goalTranslation->id;

        $this->assertDatabaseHas('goal_translations', $data);

        $response->assertRedirect(
            route('goal-translations.edit', $goalTranslation)
        );
    }

    /**
     * @test
     */
    public function it_deletes_the_goal_translation(): void
    {
        $goalTranslation = GoalTranslation::factory()->create();

        $response = $this->delete(
            route('goal-translations.destroy', $goalTranslation)
        );

        $response->assertRedirect(route('goal-translations.index'));

        $this->assertModelMissing($goalTranslation);
    }
}
