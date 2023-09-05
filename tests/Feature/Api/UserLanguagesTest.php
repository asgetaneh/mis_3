<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Language;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserLanguagesTest extends TestCase
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
    public function it_gets_user_languages(): void
    {
        $user = User::factory()->create();
        $languages = Language::factory()
            ->count(2)
            ->create([
                'created_by_id' => $user->id,
            ]);

        $response = $this->getJson(route('api.users.languages.index', $user));

        $response->assertOk()->assertSee($languages[0]->name);
    }

    /**
     * @test
     */
    public function it_stores_the_user_languages(): void
    {
        $user = User::factory()->create();
        $data = Language::factory()
            ->make([
                'created_by_id' => $user->id,
            ])
            ->toArray();

        $response = $this->postJson(
            route('api.users.languages.store', $user),
            $data
        );

        $this->assertDatabaseHas('languages', $data);

        $response->assertStatus(201)->assertJsonFragment($data);

        $language = Language::latest('id')->first();

        $this->assertEquals($user->id, $language->created_by_id);
    }
}
