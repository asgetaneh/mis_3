<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Office;
use App\Models\SuitableKpi;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OfficeSuitableKpisTest extends TestCase
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
    public function it_gets_office_suitable_kpis(): void
    {
        $office = Office::factory()->create();
        $suitableKpis = SuitableKpi::factory()
            ->count(2)
            ->create([
                'office_id' => $office->id,
            ]);

        $response = $this->getJson(
            route('api.offices.suitable-kpis.index', $office)
        );

        $response->assertOk()->assertSee($suitableKpis[0]->id);
    }

    /**
     * @test
     */
    public function it_stores_the_office_suitable_kpis(): void
    {
        $office = Office::factory()->create();
        $data = SuitableKpi::factory()
            ->make([
                'office_id' => $office->id,
            ])
            ->toArray();

        $response = $this->postJson(
            route('api.offices.suitable-kpis.store', $office),
            $data
        );

        $this->assertDatabaseHas('suitable_kpis', $data);

        $response->assertStatus(201)->assertJsonFragment($data);

        $suitableKpi = SuitableKpi::latest('id')->first();

        $this->assertEquals($office->id, $suitableKpi->office_id);
    }
}
