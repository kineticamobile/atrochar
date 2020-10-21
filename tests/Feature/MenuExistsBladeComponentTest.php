<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Blade;
use Kineticamobile\Atrochar\Models\Menu;
use Tests\TestCase;

class MenuExistsBladeComponentTest extends TestCase
{

    use WithFaker;
    use RefreshDatabase;

    public function testCompiledStringWhenMenuExistsCalledWithNoParamenters()
    {
        $compiled = Blade::compileString("@menu_exists()");

        $this->assertEquals("<?php if (\Illuminate\Support\Facades\Blade::check('menu_exists')): ?>", $compiled);
    }

    public function testCompiledStringWhenElseMenuExistsCalledWithNoParamenters()
    {
        $compiled = Blade::compileString("@elsemenu_exists()");

        $this->assertEquals("<?php elseif (\Illuminate\Support\Facades\Blade::check('menu_exists')): ?>", $compiled);
    }

    public function testCompiledStringWhenEndMenuExistsCalledWithNoParamenters()
    {
        $compiled = Blade::compileString("@endmenu_exists");

        $this->assertEquals("<?php endif; ?>", $compiled);
    }

    public function testCompiledStringWhenMenuExistsCalledWithStringParamenters()
    {
        $randomWord = $this->faker->word;

        $compiled = Blade::compileString("@menu_exists('$randomWord')");

        $this->assertEquals("<?php if (\Illuminate\Support\Facades\Blade::check('menu_exists', '$randomWord')): ?>", $compiled);
    }

    public function testCompiledStringWhenMenuExistsCalledWithIntParamenters()
    {
        $randomNumber = rand(1,100);

        $compiled = Blade::compileString("@menu_exists($randomNumber)");

        $this->assertEquals("<?php if (\Illuminate\Support\Facades\Blade::check('menu_exists', $randomNumber)): ?>", $compiled);
    }

    public function testCompiledStringWhenMenuExistsCalledWithObjectParamenters()
    {
        $menu = Menu::factory()->create();

        $compiled = Blade::compileString("@menu_exists($menu)");

        $this->assertEquals("<?php if (\Illuminate\Support\Facades\Blade::check('menu_exists', $menu)): ?>", $compiled);
    }





}
