<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\PerspectiveTranslation;

use App\Models\Perspective;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PerspectiveTranslationTest extends TestCase
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
    public function it_gets_perspective_translations_list(): void
    {
        $perspectiveTranslations = PerspectiveTranslation::factory()
            ->count(5)
            ->create();

        $response = $this->getJson(route('api.perspective-translations.index'));

        $response->assertOk()->assertSee($perspectiveTranslations[0]->name);
    }

    /**
     * @test
     */
    public function it_stores_the_perspective_translation(): void
    {
        $data = PerspectiveTranslation::factory()
            ->make()
            ->toArray();

        $response = $this->postJson(
            route('api.perspective-translations.store'),
            $data
        );

        $this->assertDatabaseHas('perspective_translations', $data);

        $response->assertStatus(201)->assertJsonFragment($data);
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

        $response = $this->putJson(
            route(
                'api.perspective-translations.update',
                $perspectiveTranslation
            ),
            $data
        );

        $data['id'] = $perspectiveTranslation->id;

        $this->assertDatabaseHas('perspective_translations', $data);

        $response->assertOk()->assertJsonFragment($data);
    }

    /**
     * @test
     */
    public function it_deletes_the_perspective_translation(): void
    {
        $perspectiveTranslation = PerspectiveTranslation::factory()->create();

        $response = $this->deleteJson(
            route(
                'api.perspective-translations.destroy',
                $perspectiveTranslation
            )
        );

        $this->assertModelMissing($perspectiveTranslation);

        $response->assertNoContent();
    }
}
