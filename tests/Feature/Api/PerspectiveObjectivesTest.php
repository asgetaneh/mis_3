<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Objective;
use App\Models\Perspective;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PerspectiveObjectivesTest extends TestCase
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
    public function it_gets_perspective_objectives(): void
    {
        $perspective = Perspective::factory()->create();
        $objectives = Objective::factory()
            ->count(2)
            ->create([
                'perspective_id' => $perspective->id,
            ]);

        $response = $this->getJson(
            route('api.perspectives.objectives.index', $perspective)
        );

        $response->assertOk()->assertSee($objectives[0]->id);
    }

    /**
     * @test
     */
    public function it_stores_the_perspective_objectives(): void
    {
        $perspective = Perspective::factory()->create();
        $data = Objective::factory()
            ->make([
                'perspective_id' => $perspective->id,
            ])
            ->toArray();

        $response = $this->postJson(
            route('api.perspectives.objectives.store', $perspective),
            $data
        );

        $this->assertDatabaseHas('objectives', $data);

        $response->assertStatus(201)->assertJsonFragment($data);

        $objective = Objective::latest('id')->first();

        $this->assertEquals($perspective->id, $objective->perspective_id);
    }
}
