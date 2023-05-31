<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\StrategyTranslation;

use App\Models\Strategy;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StrategyTranslationTest extends TestCase
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
    public function it_gets_strategy_translations_list(): void
    {
        $strategyTranslations = StrategyTranslation::factory()
            ->count(5)
            ->create();

        $response = $this->getJson(route('api.strategy-translations.index'));

        $response->assertOk()->assertSee($strategyTranslations[0]->name);
    }

    /**
     * @test
     */
    public function it_stores_the_strategy_translation(): void
    {
        $data = StrategyTranslation::factory()
            ->make()
            ->toArray();

        $response = $this->postJson(
            route('api.strategy-translations.store'),
            $data
        );

        $this->assertDatabaseHas('strategy_translations', $data);

        $response->assertStatus(201)->assertJsonFragment($data);
    }

    /**
     * @test
     */
    public function it_updates_the_strategy_translation(): void
    {
        $strategyTranslation = StrategyTranslation::factory()->create();

        $strategy = Strategy::factory()->create();

        $data = [
            'name' => $this->faker->name(),
            'discription' => $this->faker->text(),
            'translation_id' => $strategy->id,
        ];

        $response = $this->putJson(
            route('api.strategy-translations.update', $strategyTranslation),
            $data
        );

        $data['id'] = $strategyTranslation->id;

        $this->assertDatabaseHas('strategy_translations', $data);

        $response->assertOk()->assertJsonFragment($data);
    }

    /**
     * @test
     */
    public function it_deletes_the_strategy_translation(): void
    {
        $strategyTranslation = StrategyTranslation::factory()->create();

        $response = $this->deleteJson(
            route('api.strategy-translations.destroy', $strategyTranslation)
        );

        $this->assertModelMissing($strategyTranslation);

        $response->assertNoContent();
    }
}
