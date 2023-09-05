<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Strategy;
use App\Models\StrategyTranslation;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StrategyStrategyTranslationsTest extends TestCase
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
    public function it_gets_strategy_strategy_translations(): void
    {
        $strategy = Strategy::factory()->create();
        $strategyTranslations = StrategyTranslation::factory()
            ->count(2)
            ->create([
                'translation_id' => $strategy->id,
            ]);

        $response = $this->getJson(
            route('api.strategies.strategy-translations.index', $strategy)
        );

        $response->assertOk()->assertSee($strategyTranslations[0]->name);
    }

    /**
     * @test
     */
    public function it_stores_the_strategy_strategy_translations(): void
    {
        $strategy = Strategy::factory()->create();
        $data = StrategyTranslation::factory()
            ->make([
                'translation_id' => $strategy->id,
            ])
            ->toArray();

        $response = $this->postJson(
            route('api.strategies.strategy-translations.store', $strategy),
            $data
        );

        $this->assertDatabaseHas('strategy_translations', $data);

        $response->assertStatus(201)->assertJsonFragment($data);

        $strategyTranslation = StrategyTranslation::latest('id')->first();

        $this->assertEquals(
            $strategy->id,
            $strategyTranslation->translation_id
        );
    }
}
