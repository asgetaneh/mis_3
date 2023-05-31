<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Perspective;
use App\Models\PerspectiveTranslation;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PerspectivePerspectiveTranslationsTest extends TestCase
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
    public function it_gets_perspective_perspective_translations(): void
    {
        $perspective = Perspective::factory()->create();
        $perspectiveTranslations = PerspectiveTranslation::factory()
            ->count(2)
            ->create([
                'translation_id' => $perspective->id,
            ]);

        $response = $this->getJson(
            route(
                'api.perspectives.perspective-translations.index',
                $perspective
            )
        );

        $response->assertOk()->assertSee($perspectiveTranslations[0]->name);
    }

    /**
     * @test
     */
    public function it_stores_the_perspective_perspective_translations(): void
    {
        $perspective = Perspective::factory()->create();
        $data = PerspectiveTranslation::factory()
            ->make([
                'translation_id' => $perspective->id,
            ])
            ->toArray();

        $response = $this->postJson(
            route(
                'api.perspectives.perspective-translations.store',
                $perspective
            ),
            $data
        );

        $this->assertDatabaseHas('perspective_translations', $data);

        $response->assertStatus(201)->assertJsonFragment($data);

        $perspectiveTranslation = PerspectiveTranslation::latest('id')->first();

        $this->assertEquals(
            $perspective->id,
            $perspectiveTranslation->translation_id
        );
    }
}
