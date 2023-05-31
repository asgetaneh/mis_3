<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\KeyPeformanceIndicatorT;

use App\Models\KeyPeformanceIndicator;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class KeyPeformanceIndicatorTTest extends TestCase
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
    public function it_gets_key_peformance_indicator_ts_list(): void
    {
        $keyPeformanceIndicatorTs = KeyPeformanceIndicatorT::factory()
            ->count(5)
            ->create();

        $response = $this->getJson(
            route('api.key-peformance-indicator-ts.index')
        );

        $response->assertOk()->assertSee($keyPeformanceIndicatorTs[0]->name);
    }

    /**
     * @test
     */
    public function it_stores_the_key_peformance_indicator_t(): void
    {
        $data = KeyPeformanceIndicatorT::factory()
            ->make()
            ->toArray();

        $response = $this->postJson(
            route('api.key-peformance-indicator-ts.store'),
            $data
        );

        $this->assertDatabaseHas('key_peformance_indicator_ts', $data);

        $response->assertStatus(201)->assertJsonFragment($data);
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

        $response = $this->putJson(
            route(
                'api.key-peformance-indicator-ts.update',
                $keyPeformanceIndicatorT
            ),
            $data
        );

        $data['id'] = $keyPeformanceIndicatorT->id;

        $this->assertDatabaseHas('key_peformance_indicator_ts', $data);

        $response->assertOk()->assertJsonFragment($data);
    }

    /**
     * @test
     */
    public function it_deletes_the_key_peformance_indicator_t(): void
    {
        $keyPeformanceIndicatorT = KeyPeformanceIndicatorT::factory()->create();

        $response = $this->deleteJson(
            route(
                'api.key-peformance-indicator-ts.destroy',
                $keyPeformanceIndicatorT
            )
        );

        $this->assertModelMissing($keyPeformanceIndicatorT);

        $response->assertNoContent();
    }
}
