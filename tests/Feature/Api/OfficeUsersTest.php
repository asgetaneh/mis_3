<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Office;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OfficeUsersTest extends TestCase
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
    public function it_gets_office_users(): void
    {
        $office = Office::factory()->create();
        $user = User::factory()->create();

        $office->users()->attach($user);

        $response = $this->getJson(route('api.offices.users.index', $office));

        $response->assertOk()->assertSee($user->name);
    }

    /**
     * @test
     */
    public function it_can_attach_users_to_office(): void
    {
        $office = Office::factory()->create();
        $user = User::factory()->create();

        $response = $this->postJson(
            route('api.offices.users.store', [$office, $user])
        );

        $response->assertNoContent();

        $this->assertTrue(
            $office
                ->users()
                ->where('users.id', $user->id)
                ->exists()
        );
    }

    /**
     * @test
     */
    public function it_can_detach_users_from_office(): void
    {
        $office = Office::factory()->create();
        $user = User::factory()->create();

        $response = $this->deleteJson(
            route('api.offices.users.store', [$office, $user])
        );

        $response->assertNoContent();

        $this->assertFalse(
            $office
                ->users()
                ->where('users.id', $user->id)
                ->exists()
        );
    }
}
