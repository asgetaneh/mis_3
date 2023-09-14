<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Gender;
use App\Models\Inititive;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GenderInititivesTest extends TestCase
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
    public function it_gets_gender_inititives(): void
    {
        $gender = Gender::factory()->create();
        $inititive = Inititive::factory()->create();

        $gender->inititives()->attach($inititive);

        $response = $this->getJson(
            route('api.genders.inititives.index', $gender)
        );

        $response->assertOk()->assertSee($inititive->id);
    }

    /**
     * @test
     */
    public function it_can_attach_inititives_to_gender(): void
    {
        $gender = Gender::factory()->create();
        $inititive = Inititive::factory()->create();

        $response = $this->postJson(
            route('api.genders.inititives.store', [$gender, $inititive])
        );

        $response->assertNoContent();

        $this->assertTrue(
            $gender
                ->inititives()
                ->where('inititives.id', $inititive->id)
                ->exists()
        );
    }

    /**
     * @test
     */
    public function it_can_detach_inititives_from_gender(): void
    {
        $gender = Gender::factory()->create();
        $inititive = Inititive::factory()->create();

        $response = $this->deleteJson(
            route('api.genders.inititives.store', [$gender, $inititive])
        );

        $response->assertNoContent();

        $this->assertFalse(
            $gender
                ->inititives()
                ->where('inititives.id', $inititive->id)
                ->exists()
        );
    }
}
