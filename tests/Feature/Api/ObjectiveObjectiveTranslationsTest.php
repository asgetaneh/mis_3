<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Objective;
use App\Models\ObjectiveTranslation;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ObjectiveObjectiveTranslationsTest extends TestCase
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
    public function it_gets_objective_objective_translations(): void
    {
        $objective = Objective::factory()->create();
        $objectiveTranslations = ObjectiveTranslation::factory()
            ->count(2)
            ->create([
                'translation_id' => $objective->id,
            ]);

        $response = $this->getJson(
            route('api.objectives.objective-translations.index', $objective)
        );

        $response->assertOk()->assertSee($objectiveTranslations[0]->name);
    }

    /**
     * @test
     */
    public function it_stores_the_objective_objective_translations(): void
    {
        $objective = Objective::factory()->create();
        $data = ObjectiveTranslation::factory()
            ->make([
                'translation_id' => $objective->id,
            ])
            ->toArray();

        $response = $this->postJson(
            route('api.objectives.objective-translations.store', $objective),
            $data
        );

        $this->assertDatabaseHas('objective_translations', $data);

        $response->assertStatus(201)->assertJsonFragment($data);

        $objectiveTranslation = ObjectiveTranslation::latest('id')->first();

        $this->assertEquals(
            $objective->id,
            $objectiveTranslation->translation_id
        );
    }
}
