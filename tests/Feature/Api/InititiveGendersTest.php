<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Gender;
use App\Models\Inititive;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class InititiveGendersTest extends TestCase
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
    public function it_gets_inititive_genders(): void
    {
        $inititive = Inititive::factory()->create();
        $gender = Gender::factory()->create();

        $inititive->genders()->attach($gender);

        $response = $this->getJson(
            route('api.inititives.genders.index', $inititive)
        );

        $response->assertOk()->assertSee($gender->id);
    }

    /**
     * @test
     */
    public function it_can_attach_genders_to_inititive(): void
    {
        $inititive = Inititive::factory()->create();
        $gender = Gender::factory()->create();

        $response = $this->postJson(
            route('api.inititives.genders.store', [$inititive, $gender])
        );

        $response->assertNoContent();

        $this->assertTrue(
            $inititive
                ->genders()
                ->where('genders.id', $gender->id)
                ->exists()
        );
    }

    /**
     * @test
     */
    public function it_can_detach_genders_from_inititive(): void
    {
        $inititive = Inititive::factory()->create();
        $gender = Gender::factory()->create();

        $response = $this->deleteJson(
            route('api.inititives.genders.store', [$inititive, $gender])
        );

        $response->assertNoContent();

        $this->assertFalse(
            $inititive
                ->genders()
                ->where('genders.id', $gender->id)
                ->exists()
        );
    }
}
