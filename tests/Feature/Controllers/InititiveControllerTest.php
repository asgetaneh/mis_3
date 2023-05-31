<?php

namespace Tests\Feature\Controllers;

use App\Models\User;
use App\Models\Inititive;

use App\Models\KeyPeformanceIndicator;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class InititiveControllerTest extends TestCase
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
    public function it_displays_index_view_with_inititives(): void
    {
        $inititives = Inititive::factory()
            ->count(5)
            ->create();

        $response = $this->get(route('inititives.index'));

        $response
            ->assertOk()
            ->assertViewIs('app.inititives.index')
            ->assertViewHas('inititives');
    }

    /**
     * @test
     */
    public function it_displays_create_view_for_inititive(): void
    {
        $response = $this->get(route('inititives.create'));

        $response->assertOk()->assertViewIs('app.inititives.create');
    }

    /**
     * @test
     */
    public function it_stores_the_inititive(): void
    {
        $data = Inititive::factory()
            ->make()
            ->toArray();

        $response = $this->post(route('inititives.store'), $data);

        $this->assertDatabaseHas('inititives', $data);

        $inititive = Inititive::latest('id')->first();

        $response->assertRedirect(route('inititives.edit', $inititive));
    }

    /**
     * @test
     */
    public function it_displays_show_view_for_inititive(): void
    {
        $inititive = Inititive::factory()->create();

        $response = $this->get(route('inititives.show', $inititive));

        $response
            ->assertOk()
            ->assertViewIs('app.inititives.show')
            ->assertViewHas('inititive');
    }

    /**
     * @test
     */
    public function it_displays_edit_view_for_inititive(): void
    {
        $inititive = Inititive::factory()->create();

        $response = $this->get(route('inititives.edit', $inititive));

        $response
            ->assertOk()
            ->assertViewIs('app.inititives.edit')
            ->assertViewHas('inititive');
    }

    /**
     * @test
     */
    public function it_updates_the_inititive(): void
    {
        $inititive = Inititive::factory()->create();

        $keyPeformanceIndicator = KeyPeformanceIndicator::factory()->create();

        $data = [
            'key_peformance_indicator_id' => $keyPeformanceIndicator->id,
        ];

        $response = $this->put(route('inititives.update', $inititive), $data);

        $data['id'] = $inititive->id;

        $this->assertDatabaseHas('inititives', $data);

        $response->assertRedirect(route('inititives.edit', $inititive));
    }

    /**
     * @test
     */
    public function it_deletes_the_inititive(): void
    {
        $inititive = Inititive::factory()->create();

        $response = $this->delete(route('inititives.destroy', $inititive));

        $response->assertRedirect(route('inititives.index'));

        $this->assertModelMissing($inititive);
    }
}
