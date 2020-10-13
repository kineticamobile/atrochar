<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Kineticamobile\Atrochar\Models\Menu;
use Tests\TestCase;

class MenuIndexTest extends TestCase
{

    use RefreshDatabase;

    private $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function testloggedUserCanAccessIndexOfMenus()
    {
        $response = $this->actingAs($this->user)->get('atrochar/menus');

        $response->assertStatus(200);
    }

    public function testCanSeeParentMenu()
    {
        Menu::factory()->create(["name" => "tierra de barros"]);

        $response = $this->actingAs($this->user)->get('atrochar/menus');

        $response->assertStatus(200)
            ->assertSee("tierra de barros");
    }

    public function testCanNotSeeChildMenu()
    {
        $parent = Menu::factory()->create(["name" => "tierra de barros"]);
        Menu::factory()->create([
            "name" => "almendralejo",
            "menu_id" => $parent
        ]);

        $response = $this->actingAs($this->user)->get('atrochar/menus');

        $response->assertStatus(200)
            ->assertSee("tierra de barros")
            ->assertDontSee("almendralejo")
        ;
    }

    public function testCanSeeListOfMenus()
    {
        Menu::factory()->create(["name" => "tierra de barros"]);
        Menu::factory()->create(["name" => "la serena"]);
        Menu::factory()->create(["name" => "valle del jerte"]);


        $response = $this->actingAs($this->user)->get('atrochar/menus');

        $response->assertStatus(200)
            ->assertSee("tierra de barros")
            ->assertSee("la serena")
            ->assertSee("valle del jerte")
        ;
    }
}
