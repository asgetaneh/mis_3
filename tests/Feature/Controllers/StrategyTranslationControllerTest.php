<?php

namespace Tests\Feature\Controllers;

use App\Models\User;
use App\Models\StrategyTranslation;

use App\Models\Strategy;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StrategyTranslationControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(
            User::factory()->create(['email' => 'admin@admin.com'])
        );

        $this->seed(\Database\Seeders\PermissionsSeeder::class);

        $this->withoutExceptionHandling();
    }

    /**
     * @test
     */
    public function it_displays_index_view_with_strategy_translations(): void
    {
        $strategyTranslations = StrategyTranslation::factory()
            ->count(5)
            ->create();

        $response = $this->get(route('strategy-translations.index'));

        $response
            ->assertOk()
            ->assertViewIs('app.strategy_translations.index')
            ->assertViewHas('strategyTranslations');
    }

    /**
     * @test
     */
    public function it_displays_create_view_for_strategy_translation(): void
    {
        $response = $this->get(route('strategy-translations.create'));

        $response->assertOk()->assertViewIs('app.strategy_translations.create');
    }

    /**
     * @test
     */
    public function it_stores_the_strategy_translation(): void
    {
        $data = StrategyTranslation::factory()
            ->make()
            ->toArray();

        $response = $this->post(route('strategy-translations.store'), $data);

        $this->assertDatabaseHas('strategy_translations', $data);

        $strategyTranslation = StrategyTranslation::latest('id')->first();

        $response->assertRedirect(
            route('strategy-translations.edit', $strategyTranslation)
        );
    }

    /**
     * @test
     */
    public function it_displays_show_view_for_strategy_translation(): void
    {
        $strategyTranslation = StrategyTranslation::factory()->create();

        $response = $this->get(
            route('strategy-translations.show', $strategyTranslation)
        );

        $response
            ->assertOk()
            ->assertViewIs('app.strategy_translations.show')
            ->assertViewHas('strategyTranslation');
    }

    /**
     * @test
     */
    public function it_displays_edit_view_for_strategy_translation(): void
    {
        $strategyTranslation = StrategyTranslation::factory()->create();

        $response = $this->get(
            route('strategy-translations.edit', $strategyTranslation)
        );

        $response
            ->assertOk()
            ->assertViewIs('app.strategy_translations.edit')
            ->assertViewHas('strategyTranslation');
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

        $response = $this->put(
            route('strategy-translations.update', $strategyTranslation),
            $data
        );

        $data['id'] = $strategyTranslation->id;

        $this->assertDatabaseHas('strategy_translations', $data);

        $response->assertRedirect(
            route('strategy-translations.edit', $strategyTranslation)
        );
    }

    /**
     * @test
     */
    public function it_deletes_the_strategy_translation(): void
    {
        $strategyTranslation = StrategyTranslation::factory()->create();

        $response = $this->delete(
            route('strategy-translations.destroy', $strategyTranslation)
        );

        $response->assertRedirect(route('strategy-translations.index'));

        $this->assertModelMissing($strategyTranslation);
    }
}
