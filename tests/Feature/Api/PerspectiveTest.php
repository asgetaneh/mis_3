<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Perspective;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PerspectiveTest extends TestCase
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
    public function it_gets_perspectives_list(): void
    {
        $perspectives = Perspective::factory()
            ->count(5)
            ->create();

        $response = $this->getJson(route('api.perspectives.index'));

        $response->assertOk()->assertSee($perspectives[0]->id);
    }

    /**
     * @test
     */
    public function it_stores_the_perspective(): void
    {
        $data = Perspective::factory()
            ->make()
            ->toArray();

        $response = $this->postJson(route('api.perspectives.store'), $data);

        $this->assertDatabaseHas('perspectives', $data);

        $response->assertStatus(201)->assertJsonFragment($data);
    }

    /**
     * @test
     */
    public function it_updates_the_perspective(): void
    {
        $perspective = Perspective::factory()->create();

        $user = User::factory()->create();
        $user = User::factory()->create();

        $data = [
            'created_by_id' => $user->id,
            'updated_by_id' => $user->id,
        ];

        $response = $this->putJson(
            route('api.perspectives.update', $perspective),
            $data
        );

        $data['id'] = $perspective->id;

        $this->assertDatabaseHas('perspectives', $data);

        $response->assertOk()->assertJsonFragment($data);
    }

    /**
     * @test
     */
    public function it_deletes_the_perspective(): void
    {
        $perspective = Perspective::factory()->create();

        $response = $this->deleteJson(
            route('api.perspectives.destroy', $perspective)
        );

        $this->assertModelMissing($perspective);

        $response->assertNoContent();
    }
}
