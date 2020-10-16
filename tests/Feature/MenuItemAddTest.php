<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Kineticamobile\Atrochar\Models\Menu;
use Tests\TestCase;

class MenuItemAddTest extends TestCase
{

    use RefreshDatabase;
    use WithFaker;

    private $parentMenu;

    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAs(User::factory()->create());
        $this->parentMenu = Menu::factory()->create();
    }

    public function testCreatePageIsAccesible()
    {
        $response = $this->get('atrochar/menuitems/create?parent=' . $this->parentMenu->id);

        $response->assertStatus(200)
            ->assertSee($this->parentMenu->name);
    }

    public function testCreatePageRedirectIfNoParent()
    {
        $response = $this->get('atrochar/menuitems/create');

        $response->assertStatus(302)
                ->assertSessionHasErrors("parent","The parent field is required.");
    }

    public function testSuccessOnCreateMenuWithRandomDataInDatabaseAndRedirectMenuIndex()
    {
        $this->assertEquals($this->parentMenu->menus->count(), 0);

        $response = $this->post('atrochar/menuitems?parent=' . $this->parentMenu->id, [
            "name" => $this->faker->name,
            "description" => $this->faker->sentence,
            "href" => $this->faker->url,
            "newwindow" => $this->faker->boolean,
            "item" => $this->faker->boolean,
        ]);

        $response->assertStatus(302)->assertRedirect('atrochar/menus/1');

        $this->assertEquals($this->parentMenu->refresh()->menus->count(), 1);
    }

    public function testSuccessOnCreateMenuWithCustomDataInDatabaseAndRedirectMenuIndex()
    {
        $this->assertEquals($this->parentMenu->menus->count(), 0);

        $createData = [
            "name" => "Test Name",
            "description" => "Test Description",
            "href" => "Test href",
            "icon" => "Test Icon",
            "newwindow" => "on", // checkbox value
            "iframe" => "on",
            "menu_id" => $this->parentMenu->id,
            "order" => 42,
            "permission" => "enter mordor"
        ];

        $response = $this->post('atrochar/menuitems?parent=' . $this->parentMenu->id, $createData);

        $response->assertStatus(302)->assertRedirect('atrochar/menus/1');

        $this->assertEquals($this->parentMenu->refresh()->menus->count(), 1);

        $menu = $this->parentMenu->menus->first();
        $this->assertEquals(12, count($menu->getAttributes()));
        // dd($menu);
        $this->assertIsInt($menu->id);
        $this->assertEquals($createData["name"], $menu->name);
        $this->assertEquals($createData["description"], $menu->description);
        $this->assertEquals($createData["href"], $menu->href);
        $this->assertEquals($createData["icon"], $menu->icon);
        $this->assertEquals(1, $menu->newwindow);
        $this->assertEquals(1, $menu->iframe);
        $this->assertEquals($this->parentMenu->id, $menu->menu->id);
        $this->assertEquals(1, $menu->order);
        $this->assertEquals($createData["permission"], $menu->permission);
    }

    public function testErrorOnCreateIfNoParent()
    {
        $response = $this->post('atrochar/menuitems');

        $response->assertStatus(302)
                 ->assertSessionHasErrors("parent","The parent field is required.")
        ;
    }

    public function testErrorOnCreateIfNoName()
    {
        $response = $this->post('atrochar/menuitems?parent=' . $this->parentMenu->id, []);

        $response->assertStatus(302)
                 ->assertSessionHasErrors("name","The name field is required.")
        ;
    }

    public function testErrorOnCreateIfNoHref()
    {
        $response = $this->post('atrochar/menuitems?parent=' . $this->parentMenu->id, [
            "name" => $this->faker->name,
            "description" => $this->faker->sentence
        ]);

        $response->assertStatus(302)
                 ->assertSessionHasErrors("href","The description field is required.")
        ;
    }

    public function testSuccessOnCreateIfNoDescription()
    {
        $response = $this->post('atrochar/menuitems?parent=' . $this->parentMenu->id, [
            "name" => $this->faker->name,
            "href" => $this->faker->url
        ]);

        $response->assertStatus(302)->assertRedirect('atrochar/menus/1');
    }

    public function testSuccessOnCreateIfNoNewwindow()
    {
        $response = $this->post('atrochar/menuitems?parent=' . $this->parentMenu->id, [
            "name" => $this->faker->name,
            "href" => $this->faker->url
        ]);

        $response->assertStatus(302)->assertRedirect('atrochar/menus/1');
    }

    public function testNewMenuItemHasOrder_1()
    {
        $response = $this->createMenu();

        $response->assertStatus(302)->assertRedirect('atrochar/menus/1');

        $this->assertEquals($this->parentMenu->menus->last()->order, 1);
    }

    public function testNewMenuItemHasOrder_3If_2MenusAddedBefore()
    {
        $this->createMenu();
        $this->createMenu();
        $response = $this->createMenu();

        $response->assertStatus(302)->assertRedirect('atrochar/menus/1');

        $this->assertEquals($this->parentMenu->menus->last()->order, 3);
    }

    protected function makeMenu()
    {
        return [
            "name" => $this->faker->name,
            "description" => $this->faker->sentence,
            "href" => $this->faker->url,
            "newwindow" => $this->faker->boolean,
        ];
    }

    protected function createMenu()
    {
        return $this->post('atrochar/menuitems?parent=' . $this->parentMenu->id, $this->makeMenu());
    }

}
