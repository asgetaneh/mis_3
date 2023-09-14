<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Office;
use App\Models\OfficeTranslation;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OfficeOfficeTranslationsTest extends TestCase
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
    public function it_gets_office_office_translations(): void
    {
        $office = Office::factory()->create();
        $officeTranslations = OfficeTranslation::factory()
            ->count(2)
            ->create([
                'translation_id' => $office->id,
            ]);

        $response = $this->getJson(
            route('api.offices.office-translations.index', $office)
        );

        $response->assertOk()->assertSee($officeTranslations[0]->name);
    }

    /**
     * @test
     */
    public function it_stores_the_office_office_translations(): void
    {
        $office = Office::factory()->create();
        $data = OfficeTranslation::factory()
            ->make([
                'translation_id' => $office->id,
            ])
            ->toArray();

        $response = $this->postJson(
            route('api.offices.office-translations.store', $office),
            $data
        );

        $this->assertDatabaseHas('office_translations', $data);

        $response->assertStatus(201)->assertJsonFragment($data);

        $officeTranslation = OfficeTranslation::latest('id')->first();

        $this->assertEquals($office->id, $officeTranslation->translation_id);
    }
}
