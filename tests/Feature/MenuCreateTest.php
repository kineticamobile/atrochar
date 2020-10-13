<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Kineticamobile\Atrochar\Models\Menu;
use Tests\TestCase;

class MenuCreateTest extends TestCase
{

    use RefreshDatabase;
    use WithFaker;

    private $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAs(User::factory()->create());
    }

    public function testCreatePageIsAccesible()
    {
        $this->get('atrochar/menus/create')
            ->assertStatus(200)
        ;
    }

    // TODO - Pass list of named routes

    public function testSuccessOnCreateMenuInDatabaseAndRedirectMenuIndex()
    {
        $this->assertEquals(Menu::count(), 0);

        $response = $this->post('atrochar/menus/', [
            "name" => $this->faker->name,
            "description" => $this->faker->sentence,
            "href" => $this->faker->url,
            "newwindow" => $this->faker->boolean,
        ]);

        $response->assertStatus(302)->assertRedirect('atrochar/menus');

        $this->assertEquals(Menu::count(), 1);
    }

    public function testErrorOnCreateIfNoName()
    {
        $response = $this->post('atrochar/menus/', []);

        $response->assertStatus(302)
                 ->assertSessionHasErrors("name","The name field is required.")
        ;
    }

    public function testErrorOnCreateIfNoDescription()
    {
        $response = $this->post('atrochar/menus/', [
            "name" => $this->faker->name
        ]);

        $response->assertStatus(302)
                 ->assertSessionHasErrors("description","The description field is required.")
        ;
    }

    public function testErrorOnCreateIfNoHref()
    {
        $response = $this->post('atrochar/menus/', [
            "name" => $this->faker->name,
            "description" => $this->faker->sentence
        ]);

        $response->assertStatus(302)
                 ->assertSessionHasErrors("href","The description field is required.")
        ;
    }

    public function testErrorOnCreateIfNoNewwindow()
    {
        $response = $this->post('atrochar/menus/', [
            "name" => $this->faker->name,
            "description" => $this->faker->sentence,
            "href" => $this->faker->url
        ]);

        $response->assertStatus(302)
                 ->assertSessionHasErrors("newwindow","The description field is required.")
        ;
    }
}
