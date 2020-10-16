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

    public function testSuccessOnCreateMenuWithRandomDataInDatabaseAndRedirectMenuIndex()
    {
        $this->assertEquals(Menu::count(), 0);

        $response = $this->post('atrochar/menus/', [
            "name" => $this->faker->name,
            "description" => $this->faker->sentence,
            "href" => $this->faker->url,
            "newwindow" => $this->faker->boolean,
            "iframe" => $this->faker->boolean,
        ]);

        $response->assertStatus(302)->assertRedirect('atrochar/menus');

        $this->assertEquals(Menu::count(), 1);
    }

    public function testSuccessOnCreateMenuWithCustomDataInDatabaseAndRedirectMenuIndex()
    {
        $this->assertEquals(Menu::count(), 0);

        $createData = [
            "name" => "Test Name",
            "description" => "Test Description",
            "href" => "Test href",
            "icon" => "Test Icon",
            "newwindow" => "on", // checkbox value
            "iframe" => "on",
            "menu_id" => 42,
            "order" => 42,
            "permission" => "view ufo"
        ];

        $response = $this->post('atrochar/menus/', $createData);

        $response->assertStatus(302)->assertRedirect('atrochar/menus');

        $menu = Menu::first();
        $this->assertEquals(12, count($menu->getAttributes()));
        //dd($menu);
        $this->assertIsInt($menu->id);
        $this->assertEquals($createData["name"], $menu->name);
        $this->assertEquals($createData["description"], $menu->description);
        $this->assertEquals("", $menu->href);
        $this->assertEquals("", $menu->icon);
        $this->assertEquals(0, $menu->newwindow);
        $this->assertEquals(0, $menu->iframe);
        $this->assertNull($menu->menu);
        $this->assertEquals(0, $menu->order);
        $this->assertEquals("", $menu->permission);
        // 2 fields of timestamp
    }

    public function testErrorOnCreateIfNoName()
    {
        $response = $this->post('atrochar/menus/', []);

        $response->assertStatus(302)
                 ->assertSessionHasErrors("name","The name field is required.")
        ;
    }

    public function testSuccessOnCreateIfNoDescription()
    {
        $response = $this->post('atrochar/menus/', [
            "name" => $this->faker->name
        ]);

        $response->assertStatus(302)->assertRedirect('atrochar/menus');
    }

    public function testSuccessOnCreateIfNoHref()
    {
        $response = $this->post('atrochar/menus/', [
            "name" => $this->faker->name,
            "description" => $this->faker->sentence
        ]);

        $response->assertStatus(302)->assertRedirect('atrochar/menus');
    }

    public function testSuccessOnCreateIfNoNewwindow()
    {
        $response = $this->post('atrochar/menus/', [
            "name" => $this->faker->name,
            "description" => $this->faker->sentence,
            "href" => $this->faker->url
        ]);

        $response->assertStatus(302)->assertRedirect('atrochar/menus');
    }

    public function xtestSuccessOnCreateIfNoIframe()
    {
        $response = $this->post('atrochar/menus/', [
            "name" => $this->faker->name,
            "description" => $this->faker->sentence,
            "href" => $this->faker->url,
            "newwindow" => $this->faker->boolean
        ]);

        $response->assertStatus(302)->assertRedirect('atrochar/menus');
    }
}
