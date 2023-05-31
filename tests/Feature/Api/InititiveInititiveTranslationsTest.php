<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Inititive;
use App\Models\InititiveTranslation;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class InititiveInititiveTranslationsTest extends TestCase
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
    public function it_gets_inititive_inititive_translations(): void
    {
        $inititive = Inititive::factory()->create();
        $inititiveTranslations = InititiveTranslation::factory()
            ->count(2)
            ->create([
                'inititive_id' => $inititive->id,
            ]);

        $response = $this->getJson(
            route('api.inititives.inititive-translations.index', $inititive)
        );

        $response->assertOk()->assertSee($inititiveTranslations[0]->name);
    }

    /**
     * @test
     */
    public function it_stores_the_inititive_inititive_translations(): void
    {
        $inititive = Inititive::factory()->create();
        $data = InititiveTranslation::factory()
            ->make([
                'inititive_id' => $inititive->id,
            ])
            ->toArray();

        $response = $this->postJson(
            route('api.inititives.inititive-translations.store', $inititive),
            $data
        );

        $this->assertDatabaseHas('inititive_translations', $data);

        $response->assertStatus(201)->assertJsonFragment($data);

        $inititiveTranslation = InititiveTranslation::latest('id')->first();

        $this->assertEquals(
            $inititive->id,
            $inititiveTranslation->inititive_id
        );
    }
}
