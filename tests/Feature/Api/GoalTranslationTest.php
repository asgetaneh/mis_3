<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\GoalTranslation;

use App\Models\Goal;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GoalTranslationTest extends TestCase
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
    public function it_gets_goal_translations_list(): void
    {
        $goalTranslations = GoalTranslation::factory()
            ->count(5)
            ->create();

        $response = $this->getJson(route('api.goal-translations.index'));

        $response->assertOk()->assertSee($goalTranslations[0]->name);
    }

    /**
     * @test
     */
    public function it_stores_the_goal_translation(): void
    {
        $data = GoalTranslation::factory()
            ->make()
            ->toArray();

        $response = $this->postJson(
            route('api.goal-translations.store'),
            $data
        );

        $this->assertDatabaseHas('goal_translations', $data);

        $response->assertStatus(201)->assertJsonFragment($data);
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

        $response = $this->putJson(
            route('api.goal-translations.update', $goalTranslation),
            $data
        );

        $data['id'] = $goalTranslation->id;

        $this->assertDatabaseHas('goal_translations', $data);

        $response->assertOk()->assertJsonFragment($data);
    }

    /**
     * @test
     */
    public function it_deletes_the_goal_translation(): void
    {
        $goalTranslation = GoalTranslation::factory()->create();

        $response = $this->deleteJson(
            route('api.goal-translations.destroy', $goalTranslation)
        );

        $this->assertModelMissing($goalTranslation);

        $response->assertNoContent();
    }
}
