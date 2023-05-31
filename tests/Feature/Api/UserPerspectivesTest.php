<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Perspective;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserPerspectivesTest extends TestCase
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
    public function it_gets_user_perspectives(): void
    {
        $user = User::factory()->create();
        $perspectives = Perspective::factory()
            ->count(2)
            ->create([
                'updated_by_id' => $user->id,
            ]);

        $response = $this->getJson(
            route('api.users.perspectives.index', $user)
        );

        $response->assertOk()->assertSee($perspectives[0]->id);
    }

    /**
     * @test
     */
    public function it_stores_the_user_perspectives(): void
    {
        $user = User::factory()->create();
        $data = Perspective::factory()
            ->make([
                'updated_by_id' => $user->id,
            ])
            ->toArray();

        $response = $this->postJson(
            route('api.users.perspectives.store', $user),
            $data
        );

        $this->assertDatabaseHas('perspectives', $data);

        $response->assertStatus(201)->assertJsonFragment($data);

        $perspective = Perspective::latest('id')->first();

        $this->assertEquals($user->id, $perspective->updated_by_id);
    }
}
