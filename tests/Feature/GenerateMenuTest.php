<?php

namespace Tests\Feature;

use ArgumentCountError;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Kineticamobile\Atrochar\Facades\Atrochar;
use Kineticamobile\Atrochar\Models\Menu;
use Tests\TestCase;

class GenerateMenuTest extends TestCase
{

    use RefreshDatabase;
    use WithFaker;

    public function testGenerateMenuWithNoParametersThrowsArgumentCountError()
    {
        $this->expectException(ArgumentCountError::class);
        Atrochar::generateMenu();
    }

    public function testGenerateMenuOnNonExistingMenuReturnsNotFoundMessage()
    {
        $menuHtml = Atrochar::generateMenu($this->faker->word);

        $this->assertEquals("Menu Not Found!", $menuHtml);
    }

    public function testGenerateMenuOnExistingMenuWithNoLinks()
    {
        $menu = Menu::create(["name" => $this->faker->word]);

        $menuHtml = Atrochar::generateMenu($menu->name);

        $this->assertEquals("<ul></ul>", $menuHtml);
    }

    public function testGenerateMenuOnExistingMenuUsingName()
    {
        $menu = Menu::create(["name" => $this->faker->word]);

        $menuHtml = Atrochar::generateMenu($menu->name);

        $this->assertEquals("<ul></ul>", $menuHtml);
    }

    public function testGenerateMenuOnExistingMenuUsingId()
    {
        $menu = Menu::create(["name" => $this->faker->word]);

        $menuHtml = Atrochar::generateMenu($menu->id);

        $this->assertEquals("<ul></ul>", $menuHtml);
    }

    public function testGenerateMenuOnExistingMenuUsingObject()
    {
        $menu = Menu::create(["name" => $this->faker->word]);

        $menuHtml = Atrochar::generateMenu($menu->id);

        $this->assertEquals("<ul></ul>", $menuHtml);
    }

    public function testGenerateMenuOnExistingMenuWithOneLink()
    {
        $menu = Menu::create(["name" => $this->faker->word]);
        $menuItem = Menu::create([
            "name" => $this->faker->word,
            "href" => $this->faker->url,
            "menu_id" => $menu->id
        ]);

        $menuHtml = Atrochar::generateMenu($menu->name);

        $this->assertEquals("<ul><li><a href='{$menuItem->href}'  class=''>{$menuItem->name}</a></li></ul>", $menuHtml);
    }

    public function testGenerateMenuOnExistingMenuWithOneLinkHavingIframeOption()
    {
        $menu = Menu::create(["name" => $this->faker->word]);
        $menuItem = Menu::create([
            "name" => $this->faker->word,
            "href" => $this->faker->url,
            "menu_id" => $menu->id,
            "iframe" => true
        ]);

        $menuHtml = Atrochar::generateMenu($menu->name);

        $this->assertStringContainsString("/atrochar/i/2", $menuHtml);
    }

    public function testGenerateMenuOnExistingMenuWithOneLinkHavingNewwindowOption()
    {
        $menu = Menu::create(["name" => $this->faker->word]);
        $menuItem = Menu::create([
            "name" => $this->faker->word,
            "href" => $this->faker->url,
            "menu_id" => $menu->id,
            "newwindow" => true
        ]);

        $menuHtml = Atrochar::generateMenu($menu->name);

        $this->assertStringContainsString(" target='_blank' ", $menuHtml);
    }


    public function testGenerateMenuOnExistingMenuWithMultipleLinks()
    {
        $menu = Menu::create(["name" => $this->faker->word]);
        $menuItems = Menu::factory()->count(rand(1,4))->create(["menu_id" => $menu->id]);

        $menuHtml = Atrochar::generateMenu($menu->name);

        foreach($menuItems as $item){
            $this->assertStringContainsString($item->name, $menuHtml);
        }

    }

    public function testGenerateMenuOverrideClassField()
    {
        $menu = Menu::create(["name" => $this->faker->word]);
        $menuItems = Menu::factory()->create(["menu_id" => $menu->id]);
        $randomClass = $this->faker->word;

        $menuHtml = Atrochar::generateMenu($menu->name, ["class" => $randomClass]);

        foreach($menuItems as $item){
            $this->assertStringContainsString(" class='$randomClass'", $menuHtml);
        }
    }

    public function testGenerateMenuOverrideListTags()
    {
        $menu = Menu::create(["name" => $this->faker->word]);

        $menuHtml = Atrochar::generateMenu($menu->name,[
            "listStartTag" => "<div>",
            "listEndTag" => "</div>"
        ]);

        $this->assertEquals("<div></div>", $menuHtml);
    }

    public function testGenerateMenuOverrideItemTags()
    {
        $menu = Menu::create(["name" => $this->faker->word]);
        $menuItem = Menu::create([
            "name" => $this->faker->word,
            "href" => $this->faker->url,
            "menu_id" => $menu->id,
            "newwindow" => true
        ]);

        $menuHtml = Atrochar::generateMenu($menu->name,[
            "itemStartTag" => "<div>",
            "itemEndTag" => "</div>"
        ]);

        $this->assertStringContainsString("<div>", $menuHtml);
        $this->assertStringContainsString("</div>", $menuHtml);
    }

    public function testGenerateMenuOverrideLinkTag()
    {
        $menu = Menu::create(["name" => $this->faker->word]);
        $menuItem = Menu::create([
            "name" => $this->faker->word,
            "href" => $this->faker->url,
            "menu_id" => $menu->id,
            "newwindow" => true
        ]);

        $menuHtml = Atrochar::generateMenu($menu->name,[
            "linkTag" => "link",
        ]);

        $this->assertStringContainsString("<link", $menuHtml);
        $this->assertStringContainsString("</link>", $menuHtml);
    }

    public function testGenerateMenuWithNoLinksAndJetstreamTheme()
    {
        $menu = Menu::create(["name" => $this->faker->word]);

        $menuHtml = Atrochar::generateMenu($menu->name,"jetstream");

        $this->assertEquals("", $menuHtml);
    }

    public function testGenerateMenuCheckJetstreamClassIsPresent()
    {
        $menu = Menu::create(["name" => $this->faker->word]);
        $menuItem = Menu::create([
            "name" => $this->faker->word,
            "href" => $this->faker->url,
            "menu_id" => $menu->id,
            "newwindow" => true
        ]);

        $menuHtml = Atrochar::generateMenu($menu->name,"jetstream");

        $this->assertStringContainsString(config("atrochar.themes.jetstream.class"), $menuHtml);
    }
    // $menuItem = Menu::factory()->count(3)->create(["menu_id" => $menu->id]);

}
