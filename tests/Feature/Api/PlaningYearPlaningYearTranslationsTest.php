<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\PlaningYear;
use App\Models\PlaningYearTranslation;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PlaningYearPlaningYearTranslationsTest extends TestCase
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
    public function it_gets_planing_year_planing_year_translations(): void
    {
        $planingYear = PlaningYear::factory()->create();
        $planingYearTranslations = PlaningYearTranslation::factory()
            ->count(2)
            ->create([
                'planing_year_id' => $planingYear->id,
            ]);

        $response = $this->getJson(
            route(
                'api.planing-years.planing-year-translations.index',
                $planingYear
            )
        );

        $response->assertOk()->assertSee($planingYearTranslations[0]->name);
    }

    /**
     * @test
     */
    public function it_stores_the_planing_year_planing_year_translations(): void
    {
        $planingYear = PlaningYear::factory()->create();
        $data = PlaningYearTranslation::factory()
            ->make([
                'planing_year_id' => $planingYear->id,
            ])
            ->toArray();

        $response = $this->postJson(
            route(
                'api.planing-years.planing-year-translations.store',
                $planingYear
            ),
            $data
        );

        $this->assertDatabaseHas('planing_year_translations', $data);

        $response->assertStatus(201)->assertJsonFragment($data);

        $planingYearTranslation = PlaningYearTranslation::latest('id')->first();

        $this->assertEquals(
            $planingYear->id,
            $planingYearTranslation->planing_year_id
        );
    }
}
