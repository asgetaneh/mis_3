<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\PlaningYear;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PlaningYearTest extends TestCase
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
    public function it_gets_planing_years_list(): void
    {
        $planingYears = PlaningYear::factory()
            ->count(5)
            ->create();

        $response = $this->getJson(route('api.planing-years.index'));

        $response->assertOk()->assertSee($planingYears[0]->id);
    }

    /**
     * @test
     */
    public function it_stores_the_planing_year(): void
    {
        $data = PlaningYear::factory()
            ->make()
            ->toArray();

        $response = $this->postJson(route('api.planing-years.store'), $data);

        $this->assertDatabaseHas('planing_years', $data);

        $response->assertStatus(201)->assertJsonFragment($data);
    }

    /**
     * @test
     */
    public function it_updates_the_planing_year(): void
    {
        $planingYear = PlaningYear::factory()->create();

        $data = [];

        $response = $this->putJson(
            route('api.planing-years.update', $planingYear),
            $data
        );

        $data['id'] = $planingYear->id;

        $this->assertDatabaseHas('planing_years', $data);

        $response->assertOk()->assertJsonFragment($data);
    }

    /**
     * @test
     */
    public function it_deletes_the_planing_year(): void
    {
        $planingYear = PlaningYear::factory()->create();

        $response = $this->deleteJson(
            route('api.planing-years.destroy', $planingYear)
        );

        $this->assertModelMissing($planingYear);

        $response->assertNoContent();
    }
}
