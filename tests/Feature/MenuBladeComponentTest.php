<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Blade;
use Kineticamobile\Atrochar\Models\Menu;
use Tests\TestCase;

class MenuBladeComponentTest extends TestCase
{

    use WithFaker;

    public function testCompiledStringWhenMenuCalledWithNoParamenters()
    {
        $compiled = Blade::compileString("@menu()");

        $this->assertEquals("<?php echo Atrochar::generateMenu() ?>", $compiled);
    }

    public function testCompiledStringWhenMenuCalledWithRandomName()
    {
        $name = $this->faker->word;
        $compiled = Blade::compileString("@menu('$name')");

        $this->assertEquals("<?php echo Atrochar::generateMenu('$name') ?>", $compiled);
    }

    public function testCompiledStringWhenMenuCalledWithRandomNameAndRandomTheme()
    {
        $name = $this->faker->word;
        $theme = $this->faker->word;
        $compiled = Blade::compileString("@menu('$name', '$theme')");

        $this->assertEquals("<?php echo Atrochar::generateMenu('$name', '$theme') ?>", $compiled);
    }

    public function testCompiledStringWhenMenuCalledWithRandomNameAndCustomArray()
    {
        $name = $this->faker->word;
        $compiled = Blade::compileString("@menu('$name', ['class' => 'test'])");

        $this->assertEquals("<?php echo Atrochar::generateMenu('$name', ['class' => 'test']) ?>", $compiled);
    }



}
