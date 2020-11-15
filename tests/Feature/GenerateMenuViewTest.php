<?php

namespace Tests\Feature;

use ArgumentCountError;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Kineticamobile\Atrochar\Facades\Atrochar;
use Kineticamobile\Atrochar\Models\Menu;
use Tests\TestCase;

class GenerateMenuViewTest extends TestCase
{

    use RefreshDatabase;
    use WithFaker;

    public function renderView($view){
        if(!is_string($view)){
            $view = $view->render();
        }
        $viewWithNoBreakLines = preg_replace( "/\r|\n/", "", $view);
        $viewWithNoSpacesBetweenTags = preg_replace("/>\s*</", ">$2<", $viewWithNoBreakLines);
        return  preg_replace("/\s+/", " ", $viewWithNoSpacesBetweenTags);
    }

    public function testGenerateMenuviewWithNoParametersThrowsArgumentCountError()
    {
        $this->expectException(ArgumentCountError::class);
        Atrochar::generateMenu();
    }

    public function testGenerateMenuviewOnNonExistingMenuReturnsNotFoundMessage()
    {
        $view = Atrochar::generateMenuView($this->faker->word);

        $this->assertEquals("Menu Not Found!", $this->renderView($view));
    }

    public function testGenerateMenuviewOnExistingMenuWithNoLinks()
    {
        $menu = Menu::create(["name" => $this->faker->word]);

        $view = Atrochar::generateMenuView($menu->name);

        $this->assertEquals("<ul></ul>", $this->renderView($view));
    }

    public function testGenerateMenuviewOnExistingMenuUsingName()
    {
        $menu = Menu::create(["name" => $this->faker->word]);

        $view = Atrochar::generateMenuView($menu->name);

        $this->assertEquals("<ul></ul>", $this->renderView($view));
    }

    public function testGenerateMenuviewOnExistingMenuUsingId()
    {
        $menu = Menu::create(["name" => $this->faker->word]);

        $view = Atrochar::generateMenuView($menu->id);

        $this->assertEquals("<ul></ul>", $this->renderView($view));
    }

    public function testGenerateMenuviewOnExistingMenuUsingObject()
    {
        $menu = Menu::create(["name" => $this->faker->word]);

        $view = Atrochar::generateMenuView($menu->id);

        $this->assertEquals("<ul></ul>", $this->renderView($view));
    }

    public function testGenerateMenuviewOnExistingMenuWithOneLink()
    {
        $menu = Menu::create(["name" => $this->faker->word]);
        $menuItem = Menu::create([
            "name" => $this->faker->word,
            "href" => $this->faker->url,
            "menu_id" => $menu->id
        ]);

        $view = Atrochar::generateMenuView($menu->name);

        $this->assertEquals("<ul><li><a href='{$menuItem->href}' class=''> {$menuItem->name} </a></li></ul>", $this->renderView($view));
    }

    public function testGenerateMenuviewOnExistingMenuWithOneLinkHavingIframeOption()
    {
        $menu = Menu::create(["name" => $this->faker->word]);
        $menuItem = Menu::create([
            "name" => $this->faker->word,
            "href" => $this->faker->url,
            "menu_id" => $menu->id,
            "iframe" => true
        ]);

        $view = Atrochar::generateMenuView($menu->name);

        $this->assertStringContainsString("/atrochar/i/2", $this->renderView($view));
    }

    public function testGenerateMenuviewOnExistingMenuWithOneLinkHavingNewwindowOption()
    {
        $menu = Menu::create(["name" => $this->faker->word]);
        $menuItem = Menu::create([
            "name" => $this->faker->word,
            "href" => $this->faker->url,
            "menu_id" => $menu->id,
            "newwindow" => true
        ]);

        $view = Atrochar::generateMenuView($menu->name);

        $this->assertStringContainsString(" target='_blank' ", $this->renderView($view));
    }


    public function testGenerateMenuviewOnExistingMenuWithMultipleLinks()
    {
        $menu = Menu::create(["name" => $this->faker->word]);
        $menuItems = Menu::factory()->count(rand(1,4))->create(["menu_id" => $menu->id]);

        $view = Atrochar::generateMenuView($menu->name);

        foreach($menuItems as $item){
            $this->assertStringContainsString($item->name, $this->renderView($view));
        }

    }

    public function testGenerateMenuviewWithNoLinksAndJetstreamTheme()
    {
        $menu = Menu::create(["name" => $this->faker->word]);

        $view = Atrochar::generateMenuView($menu->name,"jetstream");

        $this->assertEquals("", $this->renderView($view));
    }

    public function testGenerateMenuviewCheckJetstreamClassIsPresent()
    {
        $menu = Menu::create(["name" => $this->faker->word]);
        $menuItem = Menu::create([
            "name" => $this->faker->word,
            "href" => $this->faker->url,
            "menu_id" => $menu->id,
            "newwindow" => true
        ]);

        $view = Atrochar::generateMenuView($menu->name,"jetstream");

        $this->assertStringContainsString(config("atrochar.themes.jetstream.class"), $this->renderView($view));
    }

    public function testGenerateMenuviewWithSubmenuOneLevelDeep()
    {
        $menu = Menu::create(["name" => $this->faker->word]);
        $menuItem = Menu::create([
            "name" => $this->faker->word,
            "href" => $this->faker->url,
            "menu_id" => $menu->id
        ]);

        $submenuItem = Menu::create([
            "name" => $this->faker->word,
            "href" => $this->faker->url,
            "menu_id" => $menuItem->id
        ]);

        $view = Atrochar::generateMenuView($menu->name);

        $this->assertEquals(
              "<ul>"
                . "<li>"
                    . "<a href='{$menuItem->href}' class=''> {$menuItem->name} </a>"
                    . "<div>"
                        . "<a href='{$submenuItem->href}' class=''> {$submenuItem->name} </a>"
                    . "</div>"
                . "</li>"
            . "</ul>"
            , $this->renderView($view));
    }

    public function testGenerateMenuviewWithMultipleSubmenusOneLevelDeep()
    {
        $menu = Menu::create(["name" => $this->faker->word]);
        $menuItem1 = Menu::create(["name" => $this->faker->word, "href" => $this->faker->url, "menu_id" => $menu->id]);
        $menuItem2 = Menu::create(["name" => $this->faker->word, "href" => $this->faker->url, "menu_id" => $menu->id]);

        $submenuItem1 = Menu::create(["name" => $this->faker->word,"href" => $this->faker->url,"menu_id" => $menuItem1->id]);
        $submenuItem2 = Menu::create(["name" => $this->faker->word,"href" => $this->faker->url,"menu_id" => $menuItem1->id]);
        $submenuItem3 = Menu::create(["name" => $this->faker->word,"href" => $this->faker->url,"menu_id" => $menuItem1->id]);

        $view = Atrochar::generateMenuView($menu->name);

        $this->assertEquals(
              "<ul>"
                . "<li>"
                    . "<a href='{$menuItem1->href}' class=''> {$menuItem1->name} </a>"
                    . "<div>"
                        . "<a href='{$submenuItem1->href}' class=''> {$submenuItem1->name} </a>"
                        . "<a href='{$submenuItem2->href}' class=''> {$submenuItem2->name} </a>"
                        . "<a href='{$submenuItem3->href}' class=''> {$submenuItem3->name} </a>"
                    . "</div>"
                . "</li>"
                . "<li>"
                    . "<a href='{$menuItem2->href}' class=''> {$menuItem2->name} </a>"
                . "</li>"
            . "</ul>"
            , $this->renderView($view));
    }

}
