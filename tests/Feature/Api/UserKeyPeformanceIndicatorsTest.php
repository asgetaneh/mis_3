<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\KeyPeformanceIndicator;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserKeyPeformanceIndicatorsTest extends TestCase
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
    public function it_gets_user_key_peformance_indicators(): void
    {
        $user = User::factory()->create();
        $keyPeformanceIndicators = KeyPeformanceIndicator::factory()
            ->count(2)
            ->create([
                'created_by_id' => $user->id,
            ]);

        $response = $this->getJson(
            route('api.users.key-peformance-indicators.index', $user)
        );

        $response->assertOk()->assertSee($keyPeformanceIndicators[0]->id);
    }

    /**
     * @test
     */
    public function it_stores_the_user_key_peformance_indicators(): void
    {
        $user = User::factory()->create();
        $data = KeyPeformanceIndicator::factory()
            ->make([
                'created_by_id' => $user->id,
            ])
            ->toArray();

        $response = $this->postJson(
            route('api.users.key-peformance-indicators.store', $user),
            $data
        );

        $this->assertDatabaseHas('key_peformance_indicators', $data);

        $response->assertStatus(201)->assertJsonFragment($data);

        $keyPeformanceIndicator = KeyPeformanceIndicator::latest('id')->first();

        $this->assertEquals($user->id, $keyPeformanceIndicator->created_by_id);
    }
}
