<?php

namespace Tests\Feature\Controllers;

use App\Models\User;
use App\Models\Perspective;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PerspectiveControllerTest extends TestCase
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
    public function it_displays_index_view_with_perspectives(): void
    {
        $perspectives = Perspective::factory()
            ->count(5)
            ->create();

        $response = $this->get(route('perspectives.index'));

        $response
            ->assertOk()
            ->assertViewIs('app.perspectives.index')
            ->assertViewHas('perspectives');
    }

    /**
     * @test
     */
    public function it_displays_create_view_for_perspective(): void
    {
        $response = $this->get(route('perspectives.create'));

        $response->assertOk()->assertViewIs('app.perspectives.create');
    }

    /**
     * @test
     */
    public function it_stores_the_perspective(): void
    {
        $data = Perspective::factory()
            ->make()
            ->toArray();

        $response = $this->post(route('perspectives.store'), $data);

        $this->assertDatabaseHas('perspectives', $data);

        $perspective = Perspective::latest('id')->first();

        $response->assertRedirect(route('perspectives.edit', $perspective));
    }

    /**
     * @test
     */
    public function it_displays_show_view_for_perspective(): void
    {
        $perspective = Perspective::factory()->create();

        $response = $this->get(route('perspectives.show', $perspective));

        $response
            ->assertOk()
            ->assertViewIs('app.perspectives.show')
            ->assertViewHas('perspective');
    }

    /**
     * @test
     */
    public function it_displays_edit_view_for_perspective(): void
    {
        $perspective = Perspective::factory()->create();

        $response = $this->get(route('perspectives.edit', $perspective));

        $response
            ->assertOk()
            ->assertViewIs('app.perspectives.edit')
            ->assertViewHas('perspective');
    }

    /**
     * @test
     */
    public function it_updates_the_perspective(): void
    {
        $perspective = Perspective::factory()->create();

        $user = User::factory()->create();
        $user = User::factory()->create();

        $data = [
            'created_by_id' => $user->id,
            'updated_by_id' => $user->id,
        ];

        $response = $this->put(
            route('perspectives.update', $perspective),
            $data
        );

        $data['id'] = $perspective->id;

        $this->assertDatabaseHas('perspectives', $data);

        $response->assertRedirect(route('perspectives.edit', $perspective));
    }

    /**
     * @test
     */
    public function it_deletes_the_perspective(): void
    {
        $perspective = Perspective::factory()->create();

        $response = $this->delete(route('perspectives.destroy', $perspective));

        $response->assertRedirect(route('perspectives.index'));

        $this->assertModelMissing($perspective);
    }
}
