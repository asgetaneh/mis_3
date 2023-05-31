<?php

namespace Tests\Feature\Controllers;

use App\Models\User;
use App\Models\Gender;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GenderControllerTest extends TestCase
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
    public function it_displays_index_view_with_genders(): void
    {
        $genders = Gender::factory()
            ->count(5)
            ->create();

        $response = $this->get(route('genders.index'));

        $response
            ->assertOk()
            ->assertViewIs('app.genders.index')
            ->assertViewHas('genders');
    }

    /**
     * @test
     */
    public function it_displays_create_view_for_gender(): void
    {
        $response = $this->get(route('genders.create'));

        $response->assertOk()->assertViewIs('app.genders.create');
    }

    /**
     * @test
     */
    public function it_stores_the_gender(): void
    {
        $data = Gender::factory()
            ->make()
            ->toArray();

        $response = $this->post(route('genders.store'), $data);

        $this->assertDatabaseHas('genders', $data);

        $gender = Gender::latest('id')->first();

        $response->assertRedirect(route('genders.edit', $gender));
    }

    /**
     * @test
     */
    public function it_displays_show_view_for_gender(): void
    {
        $gender = Gender::factory()->create();

        $response = $this->get(route('genders.show', $gender));

        $response
            ->assertOk()
            ->assertViewIs('app.genders.show')
            ->assertViewHas('gender');
    }

    /**
     * @test
     */
    public function it_displays_edit_view_for_gender(): void
    {
        $gender = Gender::factory()->create();

        $response = $this->get(route('genders.edit', $gender));

        $response
            ->assertOk()
            ->assertViewIs('app.genders.edit')
            ->assertViewHas('gender');
    }

    /**
     * @test
     */
    public function it_updates_the_gender(): void
    {
        $gender = Gender::factory()->create();

        $data = [];

        $response = $this->put(route('genders.update', $gender), $data);

        $data['id'] = $gender->id;

        $this->assertDatabaseHas('genders', $data);

        $response->assertRedirect(route('genders.edit', $gender));
    }

    /**
     * @test
     */
    public function it_deletes_the_gender(): void
    {
        $gender = Gender::factory()->create();

        $response = $this->delete(route('genders.destroy', $gender));

        $response->assertRedirect(route('genders.index'));

        $this->assertModelMissing($gender);
    }
}
