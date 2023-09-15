<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Inititive;
use App\Models\KeyPeformanceIndicator;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class KeyPeformanceIndicatorInititivesTest extends TestCase
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
    public function it_gets_key_peformance_indicator_inititives(): void
    {
        $keyPeformanceIndicator = KeyPeformanceIndicator::factory()->create();
        $inititives = Inititive::factory()
            ->count(2)
            ->create([
                'key_peformance_indicator_id' => $keyPeformanceIndicator->id,
            ]);

        $response = $this->getJson(
            route(
                'api.key-peformance-indicators.inititives.index',
                $keyPeformanceIndicator
            )
        );

        $response->assertOk()->assertSee($inititives[0]->id);
    }

    /**
     * @test
     */
    public function it_stores_the_key_peformance_indicator_inititives(): void
    {
        $keyPeformanceIndicator = KeyPeformanceIndicator::factory()->create();
        $data = Inititive::factory()
            ->make([
                'key_peformance_indicator_id' => $keyPeformanceIndicator->id,
            ])
            ->toArray();

        $response = $this->postJson(
            route(
                'api.key-peformance-indicators.inititives.store',
                $keyPeformanceIndicator
            ),
            $data
        );

        $this->assertDatabaseHas('inititives', $data);

        $response->assertStatus(201)->assertJsonFragment($data);

        $inititive = Inititive::latest('id')->first();

        $this->assertEquals(
            $keyPeformanceIndicator->id,
            $inititive->key_peformance_indicator_id
        );
    }
}
