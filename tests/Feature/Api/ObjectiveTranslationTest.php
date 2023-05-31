<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\ObjectiveTranslation;

use App\Models\Objective;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ObjectiveTranslationTest extends TestCase
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
    public function it_gets_objective_translations_list(): void
    {
        $objectiveTranslations = ObjectiveTranslation::factory()
            ->count(5)
            ->create();

        $response = $this->getJson(route('api.objective-translations.index'));

        $response->assertOk()->assertSee($objectiveTranslations[0]->name);
    }

    /**
     * @test
     */
    public function it_stores_the_objective_translation(): void
    {
        $data = ObjectiveTranslation::factory()
            ->make()
            ->toArray();

        $response = $this->postJson(
            route('api.objective-translations.store'),
            $data
        );

        $this->assertDatabaseHas('objective_translations', $data);

        $response->assertStatus(201)->assertJsonFragment($data);
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

        $response = $this->putJson(
            route('api.objective-translations.update', $objectiveTranslation),
            $data
        );

        $data['id'] = $objectiveTranslation->id;

        $this->assertDatabaseHas('objective_translations', $data);

        $response->assertOk()->assertJsonFragment($data);
    }

    /**
     * @test
     */
    public function it_deletes_the_objective_translation(): void
    {
        $objectiveTranslation = ObjectiveTranslation::factory()->create();

        $response = $this->deleteJson(
            route('api.objective-translations.destroy', $objectiveTranslation)
        );

        $this->assertModelMissing($objectiveTranslation);

        $response->assertNoContent();
    }
}
