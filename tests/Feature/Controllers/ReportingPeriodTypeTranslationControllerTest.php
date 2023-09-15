<?php

namespace Tests\Feature\Controllers;

use App\Models\User;
use App\Models\ReportingPeriodTypeTranslation;

use App\Models\ReportingPeriodType;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReportingPeriodTypeTranslationControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(
            User::factory()->create(['email' => 'admin@admin.com'])
        );

        $this->seed(\Database\Seeders\PermissionsSeeder::class);

        $this->withoutExceptionHandling();
    }

    /**
     * @test
     */
    public function it_displays_index_view_with_reporting_period_type_translations(): void
    {
        $reportingPeriodTypeTranslations = ReportingPeriodTypeTranslation::factory()
            ->count(5)
            ->create();

        $response = $this->get(
            route('reporting-period-type-translations.index')
        );

        $response
            ->assertOk()
            ->assertViewIs('app.reporting_period_type_translations.index')
            ->assertViewHas('reportingPeriodTypeTranslations');
    }

    /**
     * @test
     */
    public function it_displays_create_view_for_reporting_period_type_translation(): void
    {
        $response = $this->get(
            route('reporting-period-type-translations.create')
        );

        $response
            ->assertOk()
            ->assertViewIs('app.reporting_period_type_translations.create');
    }

    /**
     * @test
     */
    public function it_stores_the_reporting_period_type_translation(): void
    {
        $data = ReportingPeriodTypeTranslation::factory()
            ->make()
            ->toArray();

        $response = $this->post(
            route('reporting-period-type-translations.store'),
            $data
        );

        $this->assertDatabaseHas('reporting_period_type_translations', $data);

        $reportingPeriodTypeTranslation = ReportingPeriodTypeTranslation::latest(
            'id'
        )->first();

        $response->assertRedirect(
            route(
                'reporting-period-type-translations.edit',
                $reportingPeriodTypeTranslation
            )
        );
    }

    /**
     * @test
     */
    public function it_displays_show_view_for_reporting_period_type_translation(): void
    {
        $reportingPeriodTypeTranslation = ReportingPeriodTypeTranslation::factory()->create();

        $response = $this->get(
            route(
                'reporting-period-type-translations.show',
                $reportingPeriodTypeTranslation
            )
        );

        $response
            ->assertOk()
            ->assertViewIs('app.reporting_period_type_translations.show')
            ->assertViewHas('reportingPeriodTypeTranslation');
    }

    /**
     * @test
     */
    public function it_displays_edit_view_for_reporting_period_type_translation(): void
    {
        $reportingPeriodTypeTranslation = ReportingPeriodTypeTranslation::factory()->create();

        $response = $this->get(
            route(
                'reporting-period-type-translations.edit',
                $reportingPeriodTypeTranslation
            )
        );

        $response
            ->assertOk()
            ->assertViewIs('app.reporting_period_type_translations.edit')
            ->assertViewHas('reportingPeriodTypeTranslation');
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

        $response = $this->put(
            route(
                'reporting-period-type-translations.update',
                $reportingPeriodTypeTranslation
            ),
            $data
        );

        $data['id'] = $reportingPeriodTypeTranslation->id;

        $this->assertDatabaseHas('reporting_period_type_translations', $data);

        $response->assertRedirect(
            route(
                'reporting-period-type-translations.edit',
                $reportingPeriodTypeTranslation
            )
        );
    }

    /**
     * @test
     */
    public function it_deletes_the_reporting_period_type_translation(): void
    {
        $reportingPeriodTypeTranslation = ReportingPeriodTypeTranslation::factory()->create();

        $response = $this->delete(
            route(
                'reporting-period-type-translations.destroy',
                $reportingPeriodTypeTranslation
            )
        );

        $response->assertRedirect(
            route('reporting-period-type-translations.index')
        );

        $this->assertModelMissing($reportingPeriodTypeTranslation);
    }
}
