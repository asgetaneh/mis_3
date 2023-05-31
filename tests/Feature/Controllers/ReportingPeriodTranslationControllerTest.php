<?php

namespace Tests\Feature\Controllers;

use App\Models\User;
use App\Models\ReportingPeriodTranslation;

use App\Models\ReportingPeriod;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReportingPeriodTranslationControllerTest extends TestCase
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
    public function it_displays_index_view_with_reporting_period_translations(): void
    {
        $reportingPeriodTranslations = ReportingPeriodTranslation::factory()
            ->count(5)
            ->create();

        $response = $this->get(route('reporting-period-translations.index'));

        $response
            ->assertOk()
            ->assertViewIs('app.reporting_period_translations.index')
            ->assertViewHas('reportingPeriodTranslations');
    }

    /**
     * @test
     */
    public function it_displays_create_view_for_reporting_period_translation(): void
    {
        $response = $this->get(route('reporting-period-translations.create'));

        $response
            ->assertOk()
            ->assertViewIs('app.reporting_period_translations.create');
    }

    /**
     * @test
     */
    public function it_stores_the_reporting_period_translation(): void
    {
        $data = ReportingPeriodTranslation::factory()
            ->make()
            ->toArray();

        $response = $this->post(
            route('reporting-period-translations.store'),
            $data
        );

        $this->assertDatabaseHas('reporting_period_translations', $data);

        $reportingPeriodTranslation = ReportingPeriodTranslation::latest(
            'id'
        )->first();

        $response->assertRedirect(
            route(
                'reporting-period-translations.edit',
                $reportingPeriodTranslation
            )
        );
    }

    /**
     * @test
     */
    public function it_displays_show_view_for_reporting_period_translation(): void
    {
        $reportingPeriodTranslation = ReportingPeriodTranslation::factory()->create();

        $response = $this->get(
            route(
                'reporting-period-translations.show',
                $reportingPeriodTranslation
            )
        );

        $response
            ->assertOk()
            ->assertViewIs('app.reporting_period_translations.show')
            ->assertViewHas('reportingPeriodTranslation');
    }

    /**
     * @test
     */
    public function it_displays_edit_view_for_reporting_period_translation(): void
    {
        $reportingPeriodTranslation = ReportingPeriodTranslation::factory()->create();

        $response = $this->get(
            route(
                'reporting-period-translations.edit',
                $reportingPeriodTranslation
            )
        );

        $response
            ->assertOk()
            ->assertViewIs('app.reporting_period_translations.edit')
            ->assertViewHas('reportingPeriodTranslation');
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

        $response = $this->put(
            route(
                'reporting-period-translations.update',
                $reportingPeriodTranslation
            ),
            $data
        );

        $data['id'] = $reportingPeriodTranslation->id;

        $this->assertDatabaseHas('reporting_period_translations', $data);

        $response->assertRedirect(
            route(
                'reporting-period-translations.edit',
                $reportingPeriodTranslation
            )
        );
    }

    /**
     * @test
     */
    public function it_deletes_the_reporting_period_translation(): void
    {
        $reportingPeriodTranslation = ReportingPeriodTranslation::factory()->create();

        $response = $this->delete(
            route(
                'reporting-period-translations.destroy',
                $reportingPeriodTranslation
            )
        );

        $response->assertRedirect(route('reporting-period-translations.index'));

        $this->assertModelMissing($reportingPeriodTranslation);
    }
}
