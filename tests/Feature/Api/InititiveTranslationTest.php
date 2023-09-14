<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\InititiveTranslation;

use App\Models\Inititive;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class InititiveTranslationTest extends TestCase
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
    public function it_gets_inititive_translations_list(): void
    {
        $inititiveTranslations = InititiveTranslation::factory()
            ->count(5)
            ->create();

        $response = $this->getJson(route('api.inititive-translations.index'));

        $response->assertOk()->assertSee($inititiveTranslations[0]->name);
    }

    /**
     * @test
     */
    public function it_stores_the_inititive_translation(): void
    {
        $data = InititiveTranslation::factory()
            ->make()
            ->toArray();

        $response = $this->postJson(
            route('api.inititive-translations.store'),
            $data
        );

        $this->assertDatabaseHas('inititive_translations', $data);

        $response->assertStatus(201)->assertJsonFragment($data);
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

        $response = $this->putJson(
            route('api.inititive-translations.update', $inititiveTranslation),
            $data
        );

        $data['id'] = $inititiveTranslation->id;

        $this->assertDatabaseHas('inititive_translations', $data);

        $response->assertOk()->assertJsonFragment($data);
    }

    /**
     * @test
     */
    public function it_deletes_the_inititive_translation(): void
    {
        $inititiveTranslation = InititiveTranslation::factory()->create();

        $response = $this->deleteJson(
            route('api.inititive-translations.destroy', $inititiveTranslation)
        );

        $this->assertModelMissing($inititiveTranslation);

        $response->assertNoContent();
    }
}
