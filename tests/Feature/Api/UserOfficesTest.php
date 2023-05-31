<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Office;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserOfficesTest extends TestCase
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
    public function it_gets_user_offices(): void
    {
        $user = User::factory()->create();
        $office = Office::factory()->create();

        $user->offices2()->attach($office);

        $response = $this->getJson(route('api.users.offices.index', $user));

        $response->assertOk()->assertSee($office->id);
    }

    /**
     * @test
     */
    public function it_can_attach_offices_to_user(): void
    {
        $user = User::factory()->create();
        $office = Office::factory()->create();

        $response = $this->postJson(
            route('api.users.offices.store', [$user, $office])
        );

        $response->assertNoContent();

        $this->assertTrue(
            $user
                ->offices2()
                ->where('offices.id', $office->id)
                ->exists()
        );
    }

    /**
     * @test
     */
    public function it_can_detach_offices_from_user(): void
    {
        $user = User::factory()->create();
        $office = Office::factory()->create();

        $response = $this->deleteJson(
            route('api.users.offices.store', [$user, $office])
        );

        $response->assertNoContent();

        $this->assertFalse(
            $user
                ->offices2()
                ->where('offices.id', $office->id)
                ->exists()
        );
    }
}
