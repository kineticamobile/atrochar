<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Kineticamobile\Atrochar\Models\Menu;
use Tests\TestCase;

class MenuItemUpdateTest extends TestCase
{

    use RefreshDatabase;
    use WithFaker;

    private $parentMenu;
    private $itemMenu;

    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAs(User::factory()->create());
        $this->parentMenu = Menu::factory()->create();
        $this->createMenu(3);
        $this->itemMenu = $this->parentMenu->menus->last();
        $this->createMenu(3);
    }

    public function testCheckMenuItemIs_3()
    {
        $this->assertEquals($this->itemMenu->order, 3);
    }

    public function xtestChangeMenuItemToPosition_2()
    {
        $updateData = $this->updateMenu();
        $updateData['order'] = 2;
        $response = $this->put('atrochar/menuitems/' . $this->itemMenu->id, $updateData);

        $response->assertStatus(302);

        $this->itemMenu->refresh();
        $this->assertEquals($this->itemMenu->order, 2);
    }

    protected function updateMenu()
    {
        return [
            "name" => $this->faker->name,
            "description" => $this->faker->sentence,
            "href" => $this->faker->url,
            "newwindow" => $this->faker->boolean,
        ];
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

    protected function createMenu($times = 1)
    {
        for($i = $times - 1; $i > 0; $i--)
        {
            $this->post('atrochar/menuitems?parent=' . $this->parentMenu->id, $this->makeMenu());
        }
        return $this->post('atrochar/menuitems?parent=' . $this->parentMenu->id, $this->makeMenu());
    }

}
