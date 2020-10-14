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

    public function testSuccessOnCreateMenuInDatabaseAndRedirectMenuIndex()
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

    public function testSuccessOnCreateMenuAndIconColumnIsFilled()
    {
        $this->assertEquals($this->parentMenu->menus->count(), 0);

        $response = $this->post('atrochar/menuitems?parent=' . $this->parentMenu->id, [
            "name" => $this->faker->name,
            "description" => $this->faker->sentence,
            "href" => $this->faker->url,
            "newwindow" => $this->faker->boolean,
            "item" => $this->faker->boolean,
            "icon" => "laptop"
        ]);

        $response->assertStatus(302)->assertRedirect('atrochar/menus/1');

        $this->assertEquals($this->parentMenu->refresh()->menus->first()->icon, "laptop");
    }

    public function testErrorOnCreateIfNoParent()
    {
        $response = $this->post('atrochar/menuitems');

        $response->assertStatus(302)
                 ->assertSessionHasErrors("name","The name field is required.")
        ;
    }

    public function testErrorOnCreateIfNoName()
    {
        $response = $this->post('atrochar/menuitems?parent=' . $this->parentMenu->id, []);

        $response->assertStatus(302)
                 ->assertSessionHasErrors("name","The name field is required.")
        ;
    }

    public function testErrorOnCreateIfNoDescription()
    {
        $response = $this->post('atrochar/menuitems?parent=' . $this->parentMenu->id, [
            "name" => $this->faker->name
        ]);

        $response->assertStatus(302)
                 ->assertSessionHasErrors("description","The description field is required.")
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

    public function testErrorOnCreateIfNoNewwindow()
    {
        $response = $this->post('atrochar/menuitems?parent=' . $this->parentMenu->id, [
            "name" => $this->faker->name,
            "description" => $this->faker->sentence,
            "href" => $this->faker->url
        ]);

        $response->assertStatus(302)
                 ->assertSessionHasErrors("newwindow","The description field is required.")
        ;
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
