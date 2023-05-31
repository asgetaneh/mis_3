<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\SuitableKpi;

use App\Models\Office;
use App\Models\PlaningYear;
use App\Models\KeyPeformanceIndicator;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SuitableKpiTest extends TestCase
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
    public function it_gets_suitable_kpis_list(): void
    {
        $suitableKpis = SuitableKpi::factory()
            ->count(5)
            ->create();

        $response = $this->getJson(route('api.suitable-kpis.index'));

        $response->assertOk()->assertSee($suitableKpis[0]->id);
    }

    /**
     * @test
     */
    public function it_stores_the_suitable_kpi(): void
    {
        $data = SuitableKpi::factory()
            ->make()
            ->toArray();

        $response = $this->postJson(route('api.suitable-kpis.store'), $data);

        $this->assertDatabaseHas('suitable_kpis', $data);

        $response->assertStatus(201)->assertJsonFragment($data);
    }

    /**
     * @test
     */
    public function it_updates_the_suitable_kpi(): void
    {
        $suitableKpi = SuitableKpi::factory()->create();

        $keyPeformanceIndicator = KeyPeformanceIndicator::factory()->create();
        $office = Office::factory()->create();
        $planingYear = PlaningYear::factory()->create();

        $data = [
            'key_peformance_indicator_id' => $keyPeformanceIndicator->id,
            'office_id' => $office->id,
            'planing_year_id' => $planingYear->id,
        ];

        $response = $this->putJson(
            route('api.suitable-kpis.update', $suitableKpi),
            $data
        );

        $data['id'] = $suitableKpi->id;

        $this->assertDatabaseHas('suitable_kpis', $data);

        $response->assertOk()->assertJsonFragment($data);
    }

    /**
     * @test
     */
    public function it_deletes_the_suitable_kpi(): void
    {
        $suitableKpi = SuitableKpi::factory()->create();

        $response = $this->deleteJson(
            route('api.suitable-kpis.destroy', $suitableKpi)
        );

        $this->assertModelMissing($suitableKpi);

        $response->assertNoContent();
    }
}
