<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Blade;
use Kineticamobile\Atrochar\Models\Menu;
use Tests\TestCase;

class MenuCheckTest extends TestCase
{

    use WithFaker;
    use RefreshDatabase;

    public function testMenuCheckReturnFalseIfNoMenu()
    {
        $this->assertFalse(Menu::check($this->faker->word));
    }

    public function testMenuCheckReturnTrueIfMenuObjectPassed()
    {
        $menu = Menu::factory()->create();

        $this->assertTrue(Menu::check($menu));
    }

    public function testMenuCheckReturnTrueIfMenuIdPassed()
    {
        $menu = Menu::factory()->create();

        $this->assertTrue(Menu::check($menu->id));
    }

    public function testMenuCheckReturnTrueIfMenuNamePassed()
    {
        $menu = Menu::factory()->create();

        $this->assertTrue(Menu::check($menu->name));
    }

    public function testMenuCheckReturnFalseIfMenuDescriptionPassed()
    {
        $menu = Menu::factory()->create();

        $this->assertFalse(Menu::check($menu->description));
    }





}
