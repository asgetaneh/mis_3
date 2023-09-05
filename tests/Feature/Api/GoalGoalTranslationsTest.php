<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Goal;
use App\Models\GoalTranslation;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GoalGoalTranslationsTest extends TestCase
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
    public function it_gets_goal_goal_translations(): void
    {
        $goal = Goal::factory()->create();
        $goalTranslations = GoalTranslation::factory()
            ->count(2)
            ->create([
                'translation_id' => $goal->id,
            ]);

        $response = $this->getJson(
            route('api.goals.goal-translations.index', $goal)
        );

        $response->assertOk()->assertSee($goalTranslations[0]->name);
    }

    /**
     * @test
     */
    public function it_stores_the_goal_goal_translations(): void
    {
        $goal = Goal::factory()->create();
        $data = GoalTranslation::factory()
            ->make([
                'translation_id' => $goal->id,
            ])
            ->toArray();

        $response = $this->postJson(
            route('api.goals.goal-translations.store', $goal),
            $data
        );

        $this->assertDatabaseHas('goal_translations', $data);

        $response->assertStatus(201)->assertJsonFragment($data);

        $goalTranslation = GoalTranslation::latest('id')->first();

        $this->assertEquals($goal->id, $goalTranslation->translation_id);
    }
}
