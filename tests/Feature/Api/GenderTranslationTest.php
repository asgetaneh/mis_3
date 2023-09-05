<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\GenderTranslation;

use App\Models\Gender;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GenderTranslationTest extends TestCase
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
    public function it_gets_gender_translations_list(): void
    {
        $genderTranslations = GenderTranslation::factory()
            ->count(5)
            ->create();

        $response = $this->getJson(route('api.gender-translations.index'));

        $response->assertOk()->assertSee($genderTranslations[0]->name);
    }

    /**
     * @test
     */
    public function it_stores_the_gender_translation(): void
    {
        $data = GenderTranslation::factory()
            ->make()
            ->toArray();

        $response = $this->postJson(
            route('api.gender-translations.store'),
            $data
        );

        $this->assertDatabaseHas('gender_translations', $data);

        $response->assertStatus(201)->assertJsonFragment($data);
    }

    /**
     * @test
     */
    public function it_updates_the_gender_translation(): void
    {
        $genderTranslation = GenderTranslation::factory()->create();

        $gender = Gender::factory()->create();

        $data = [
            'name' => $this->faker->name(),
            'description' => $this->faker->text(),
            'gender_id' => $gender->id,
        ];

        $response = $this->putJson(
            route('api.gender-translations.update', $genderTranslation),
            $data
        );

        $data['id'] = $genderTranslation->id;

        $this->assertDatabaseHas('gender_translations', $data);

        $response->assertOk()->assertJsonFragment($data);
    }

    /**
     * @test
     */
    public function it_deletes_the_gender_translation(): void
    {
        $genderTranslation = GenderTranslation::factory()->create();

        $response = $this->deleteJson(
            route('api.gender-translations.destroy', $genderTranslation)
        );

        $this->assertModelMissing($genderTranslation);

        $response->assertNoContent();
    }
}
