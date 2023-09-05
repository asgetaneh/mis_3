<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\OfficeTranslation;

use App\Models\Office;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OfficeTranslationTest extends TestCase
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
    public function it_gets_office_translations_list(): void
    {
        $officeTranslations = OfficeTranslation::factory()
            ->count(5)
            ->create();

        $response = $this->getJson(route('api.office-translations.index'));

        $response->assertOk()->assertSee($officeTranslations[0]->name);
    }

    /**
     * @test
     */
    public function it_stores_the_office_translation(): void
    {
        $data = OfficeTranslation::factory()
            ->make()
            ->toArray();

        $response = $this->postJson(
            route('api.office-translations.store'),
            $data
        );

        $this->assertDatabaseHas('office_translations', $data);

        $response->assertStatus(201)->assertJsonFragment($data);
    }

    /**
     * @test
     */
    public function it_updates_the_office_translation(): void
    {
        $officeTranslation = OfficeTranslation::factory()->create();

        $office = Office::factory()->create();

        $data = [
            'name' => $this->faker->name(),
            'description' => $this->faker->sentence(15),
            'translation_id' => $office->id,
        ];

        $response = $this->putJson(
            route('api.office-translations.update', $officeTranslation),
            $data
        );

        $data['id'] = $officeTranslation->id;

        $this->assertDatabaseHas('office_translations', $data);

        $response->assertOk()->assertJsonFragment($data);
    }

    /**
     * @test
     */
    public function it_deletes_the_office_translation(): void
    {
        $officeTranslation = OfficeTranslation::factory()->create();

        $response = $this->deleteJson(
            route('api.office-translations.destroy', $officeTranslation)
        );

        $this->assertModelMissing($officeTranslation);

        $response->assertNoContent();
    }
}
