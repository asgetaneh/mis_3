<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\ReportingPeriodTranslation;

use App\Models\ReportingPeriod;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReportingPeriodTranslationTest extends TestCase
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
    public function it_gets_reporting_period_translations_list(): void
    {
        $reportingPeriodTranslations = ReportingPeriodTranslation::factory()
            ->count(5)
            ->create();

        $response = $this->getJson(
            route('api.reporting-period-translations.index')
        );

        $response->assertOk()->assertSee($reportingPeriodTranslations[0]->name);
    }

    /**
     * @test
     */
    public function it_stores_the_reporting_period_translation(): void
    {
        $data = ReportingPeriodTranslation::factory()
            ->make()
            ->toArray();

        $response = $this->postJson(
            route('api.reporting-period-translations.store'),
            $data
        );

        $this->assertDatabaseHas('reporting_period_translations', $data);

        $response->assertStatus(201)->assertJsonFragment($data);
    }

    /**
     * @test
     */
    public function it_updates_the_reporting_period_translation(): void
    {
        $reportingPeriodTranslation = ReportingPeriodTranslation::factory()->create();

        $reportingPeriod = ReportingPeriod::factory()->create();

        $data = [
            'name' => $this->faker->name(),
            'description' => $this->faker->text(),
            'reporting_period_id' => $reportingPeriod->id,
        ];

        $response = $this->putJson(
            route(
                'api.reporting-period-translations.update',
                $reportingPeriodTranslation
            ),
            $data
        );

        $data['id'] = $reportingPeriodTranslation->id;

        $this->assertDatabaseHas('reporting_period_translations', $data);

        $response->assertOk()->assertJsonFragment($data);
    }

    /**
     * @test
     */
    public function it_deletes_the_reporting_period_translation(): void
    {
        $reportingPeriodTranslation = ReportingPeriodTranslation::factory()->create();

        $response = $this->deleteJson(
            route(
                'api.reporting-period-translations.destroy',
                $reportingPeriodTranslation
            )
        );

        $this->assertModelMissing($reportingPeriodTranslation);

        $response->assertNoContent();
    }
}
