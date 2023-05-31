<?php

namespace Tests\Feature\Controllers;

use App\Models\User;
use App\Models\KeyPeformanceIndicatorT;

use App\Models\KeyPeformanceIndicator;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class KeyPeformanceIndicatorTControllerTest extends TestCase
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
    public function it_displays_index_view_with_key_peformance_indicator_ts(): void
    {
        $keyPeformanceIndicatorTs = KeyPeformanceIndicatorT::factory()
            ->count(5)
            ->create();

        $response = $this->get(route('key-peformance-indicator-ts.index'));

        $response
            ->assertOk()
            ->assertViewIs('app.key_peformance_indicator_ts.index')
            ->assertViewHas('keyPeformanceIndicatorTs');
    }

    /**
     * @test
     */
    public function it_displays_create_view_for_key_peformance_indicator_t(): void
    {
        $response = $this->get(route('key-peformance-indicator-ts.create'));

        $response
            ->assertOk()
            ->assertViewIs('app.key_peformance_indicator_ts.create');
    }

    /**
     * @test
     */
    public function it_stores_the_key_peformance_indicator_t(): void
    {
        $data = KeyPeformanceIndicatorT::factory()
            ->make()
            ->toArray();

        $response = $this->post(
            route('key-peformance-indicator-ts.store'),
            $data
        );

        $this->assertDatabaseHas('key_peformance_indicator_ts', $data);

        $keyPeformanceIndicatorT = KeyPeformanceIndicatorT::latest(
            'id'
        )->first();

        $response->assertRedirect(
            route('key-peformance-indicator-ts.edit', $keyPeformanceIndicatorT)
        );
    }

    /**
     * @test
     */
    public function it_displays_show_view_for_key_peformance_indicator_t(): void
    {
        $keyPeformanceIndicatorT = KeyPeformanceIndicatorT::factory()->create();

        $response = $this->get(
            route('key-peformance-indicator-ts.show', $keyPeformanceIndicatorT)
        );

        $response
            ->assertOk()
            ->assertViewIs('app.key_peformance_indicator_ts.show')
            ->assertViewHas('keyPeformanceIndicatorT');
    }

    /**
     * @test
     */
    public function it_displays_edit_view_for_key_peformance_indicator_t(): void
    {
        $keyPeformanceIndicatorT = KeyPeformanceIndicatorT::factory()->create();

        $response = $this->get(
            route('key-peformance-indicator-ts.edit', $keyPeformanceIndicatorT)
        );

        $response
            ->assertOk()
            ->assertViewIs('app.key_peformance_indicator_ts.edit')
            ->assertViewHas('keyPeformanceIndicatorT');
    }

    /**
     * @test
     */
    public function it_updates_the_key_peformance_indicator_t(): void
    {
        $keyPeformanceIndicatorT = KeyPeformanceIndicatorT::factory()->create();

        $keyPeformanceIndicator = KeyPeformanceIndicator::factory()->create();

        $data = [
            'name' => $this->faker->name(),
            'description' => $this->faker->text(),
            'out_put' => $this->faker->text(),
            'out_come' => $this->faker->text(),
            'translation_id' => $keyPeformanceIndicator->id,
        ];

        $response = $this->put(
            route(
                'key-peformance-indicator-ts.update',
                $keyPeformanceIndicatorT
            ),
            $data
        );

        $data['id'] = $keyPeformanceIndicatorT->id;

        $this->assertDatabaseHas('key_peformance_indicator_ts', $data);

        $response->assertRedirect(
            route('key-peformance-indicator-ts.edit', $keyPeformanceIndicatorT)
        );
    }

    /**
     * @test
     */
    public function it_deletes_the_key_peformance_indicator_t(): void
    {
        $keyPeformanceIndicatorT = KeyPeformanceIndicatorT::factory()->create();

        $response = $this->delete(
            route(
                'key-peformance-indicator-ts.destroy',
                $keyPeformanceIndicatorT
            )
        );

        $response->assertRedirect(route('key-peformance-indicator-ts.index'));

        $this->assertModelMissing($keyPeformanceIndicatorT);
    }
}
