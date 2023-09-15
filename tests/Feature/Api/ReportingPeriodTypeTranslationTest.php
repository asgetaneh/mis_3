<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\ReportingPeriodTypeTranslation;

use App\Models\ReportingPeriodType;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReportingPeriodTypeTranslationTest extends TestCase
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
    public function it_gets_reporting_period_type_translations_list(): void
    {
        $reportingPeriodTypeTranslations = ReportingPeriodTypeTranslation::factory()
            ->count(5)
            ->create();

        $response = $this->getJson(
            route('api.reporting-period-type-translations.index')
        );

        $response
            ->assertOk()
            ->assertSee($reportingPeriodTypeTranslations[0]->name);
    }

    /**
     * @test
     */
    public function it_stores_the_reporting_period_type_translation(): void
    {
        $data = ReportingPeriodTypeTranslation::factory()
            ->make()
            ->toArray();

        $response = $this->postJson(
            route('api.reporting-period-type-translations.store'),
            $data
        );

        $this->assertDatabaseHas('reporting_period_type_translations', $data);

        $response->assertStatus(201)->assertJsonFragment($data);
    }

    /**
     * @test
     */
    public function it_updates_the_reporting_period_type_translation(): void
    {
        $reportingPeriodTypeTranslation = ReportingPeriodTypeTranslation::factory()->create();

        $reportingPeriodType = ReportingPeriodType::factory()->create();

        $data = [
            'name' => $this->faker->name(),
            'description' => $this->faker->text(),
            'reporting_period_type_id' => $reportingPeriodType->id,
        ];

        $response = $this->putJson(
            route(
                'api.reporting-period-type-translations.update',
                $reportingPeriodTypeTranslation
            ),
            $data
        );

        $data['id'] = $reportingPeriodTypeTranslation->id;

        $this->assertDatabaseHas('reporting_period_type_translations', $data);

        $response->assertOk()->assertJsonFragment($data);
    }

    /**
     * @test
     */
    public function it_deletes_the_reporting_period_type_translation(): void
    {
        $reportingPeriodTypeTranslation = ReportingPeriodTypeTranslation::factory()->create();

        $response = $this->deleteJson(
            route(
                'api.reporting-period-type-translations.destroy',
                $reportingPeriodTypeTranslation
            )
        );

        $this->assertModelMissing($reportingPeriodTypeTranslation);

        $response->assertNoContent();
    }
}
