<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Office;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OfficeOfficesTest extends TestCase
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
    public function it_gets_office_offices(): void
    {
        $office = Office::factory()->create();
        $offices = Office::factory()
            ->count(2)
            ->create([
                'parent_office_id' => $office->id,
            ]);

        $response = $this->getJson(route('api.offices.offices.index', $office));

        $response->assertOk()->assertSee($offices[0]->id);
    }

    /**
     * @test
     */
    public function it_stores_the_office_offices(): void
    {
        $office = Office::factory()->create();
        $data = Office::factory()
            ->make([
                'parent_office_id' => $office->id,
            ])
            ->toArray();

        $response = $this->postJson(
            route('api.offices.offices.store', $office),
            $data
        );

        $this->assertDatabaseHas('offices', $data);

        $response->assertStatus(201)->assertJsonFragment($data);

        $office = Office::latest('id')->first();

        $this->assertEquals($office->id, $office->parent_office_id);
    }
}
