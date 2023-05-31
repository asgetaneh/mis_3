<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\PlaningYear;
use App\Models\SuitableKpi;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PlaningYearSuitableKpisTest extends TestCase
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
    public function it_gets_planing_year_suitable_kpis(): void
    {
        $planingYear = PlaningYear::factory()->create();
        $suitableKpis = SuitableKpi::factory()
            ->count(2)
            ->create([
                'planing_year_id' => $planingYear->id,
            ]);

        $response = $this->getJson(
            route('api.planing-years.suitable-kpis.index', $planingYear)
        );

        $response->assertOk()->assertSee($suitableKpis[0]->id);
    }

    /**
     * @test
     */
    public function it_stores_the_planing_year_suitable_kpis(): void
    {
        $planingYear = PlaningYear::factory()->create();
        $data = SuitableKpi::factory()
            ->make([
                'planing_year_id' => $planingYear->id,
            ])
            ->toArray();

        $response = $this->postJson(
            route('api.planing-years.suitable-kpis.store', $planingYear),
            $data
        );

        $this->assertDatabaseHas('suitable_kpis', $data);

        $response->assertStatus(201)->assertJsonFragment($data);

        $suitableKpi = SuitableKpi::latest('id')->first();

        $this->assertEquals($planingYear->id, $suitableKpi->planing_year_id);
    }
}
