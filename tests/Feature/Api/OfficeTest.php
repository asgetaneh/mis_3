<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Office;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OfficeTest extends TestCase
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
    public function it_gets_offices_list(): void
    {
        $offices = Office::factory()
            ->count(5)
            ->create();

        $response = $this->getJson(route('api.offices.index'));

        $response->assertOk()->assertSee($offices[0]->id);
    }

    /**
     * @test
     */
    public function it_stores_the_office(): void
    {
        $data = Office::factory()
            ->make()
            ->toArray();

        $response = $this->postJson(route('api.offices.store'), $data);

        $this->assertDatabaseHas('offices', $data);

        $response->assertStatus(201)->assertJsonFragment($data);
    }

    /**
     * @test
     */
    public function it_updates_the_office(): void
    {
        $office = Office::factory()->create();

        $user = User::factory()->create();
        $office = Office::factory()->create();

        $data = [
            'holder_id' => $user->id,
            'parent_office_id' => $office->id,
        ];

        $response = $this->putJson(route('api.offices.update', $office), $data);

        $data['id'] = $office->id;

        $this->assertDatabaseHas('offices', $data);

        $response->assertOk()->assertJsonFragment($data);
    }

    /**
     * @test
     */
    public function it_deletes_the_office(): void
    {
        $office = Office::factory()->create();

        $response = $this->deleteJson(route('api.offices.destroy', $office));

        $this->assertModelMissing($office);

        $response->assertNoContent();
    }
}
