<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Strategy;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserStrategiesTest extends TestCase
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
    public function it_gets_user_strategies(): void
    {
        $user = User::factory()->create();
        $strategies = Strategy::factory()
            ->count(2)
            ->create([
                'updated_by_id' => $user->id,
            ]);

        $response = $this->getJson(route('api.users.strategies.index', $user));

        $response->assertOk()->assertSee($strategies[0]->id);
    }

    /**
     * @test
     */
    public function it_stores_the_user_strategies(): void
    {
        $user = User::factory()->create();
        $data = Strategy::factory()
            ->make([
                'updated_by_id' => $user->id,
            ])
            ->toArray();

        $response = $this->postJson(
            route('api.users.strategies.store', $user),
            $data
        );

        $this->assertDatabaseHas('strategies', $data);

        $response->assertStatus(201)->assertJsonFragment($data);

        $strategy = Strategy::latest('id')->first();

        $this->assertEquals($user->id, $strategy->updated_by_id);
    }
}
