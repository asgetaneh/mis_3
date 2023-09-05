<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Gender;
use App\Models\GenderTranslation;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GenderGenderTranslationsTest extends TestCase
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
    public function it_gets_gender_gender_translations(): void
    {
        $gender = Gender::factory()->create();
        $genderTranslations = GenderTranslation::factory()
            ->count(2)
            ->create([
                'gender_id' => $gender->id,
            ]);

        $response = $this->getJson(
            route('api.genders.gender-translations.index', $gender)
        );

        $response->assertOk()->assertSee($genderTranslations[0]->name);
    }

    /**
     * @test
     */
    public function it_stores_the_gender_gender_translations(): void
    {
        $gender = Gender::factory()->create();
        $data = GenderTranslation::factory()
            ->make([
                'gender_id' => $gender->id,
            ])
            ->toArray();

        $response = $this->postJson(
            route('api.genders.gender-translations.store', $gender),
            $data
        );

        $this->assertDatabaseHas('gender_translations', $data);

        $response->assertStatus(201)->assertJsonFragment($data);

        $genderTranslation = GenderTranslation::latest('id')->first();

        $this->assertEquals($gender->id, $genderTranslation->gender_id);
    }
}
