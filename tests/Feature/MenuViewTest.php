<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Kineticamobile\Atrochar\Models\Menu;
use Tests\TestCase;

class MenuViewTest extends TestCase
{

    use RefreshDatabase;

    private $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function testloggedUserMenuNotFoundIfWrongId()
    {
        $response = $this->actingAs($this->user)->get('atrochar/menus/10');

        $response->assertStatus(404);
    }


    public function testCanSeeNameOfMenu()
    {
        $menu = Menu::factory()->create(["name" => "tierra de barros"]);

        $response = $this->actingAs($this->user)->get('atrochar/menus/' . $menu->id);

        $response->assertStatus(200)
            ->assertSee("tierra de barros");
    }

    public function testCanSeeChildMenu()
    {
        $parent = Menu::factory()->create(["name" => "tierra de barros"]);
        Menu::factory()->create([
            "name" => "almendralejo",
            "menu_id" => $parent
        ]);
        $response = $this->actingAs($this->user)->get('atrochar/menus/'. $parent->id);

        $response->assertStatus(200)
            ->assertSee("tierra de barros")
            ->assertSee("almendralejo")
        ;
    }

    public function testCanSeeListOfChildren()
    {
        $menu = Menu::factory()->create(["name" => "tierra de barros"]);
        Menu::factory()->create([
            "name" => "almendralejo",
            "menu_id" => $menu
        ]);
        Menu::factory()->create([
            "name" => "aceuchal",
            "menu_id" => $menu
        ]);
        Menu::factory()->create([
            "name" => "villafranca",
            "menu_id" => $menu
        ]);


        $response = $this->actingAs($this->user)->get('atrochar/menus/' . $menu->id);

        $response->assertStatus(200)
            ->assertSee("almendralejo")
            ->assertSee("aceuchal")
            ->assertSee("villafranca")
        ;
    }
}
