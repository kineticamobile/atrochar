<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Kineticamobile\Atrochar\Models\Menu;
use Tests\TestCase;

class CollectionMoveTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function testCollectionCanMoveOnePosition()
    {
        $arr = ["a", "b", "c", "d", "e", "f", "g"];

        $collection = collect($arr)->move(1,2);

        $this->assertEquals($collection->get(1), "c");
        $this->assertEquals($collection->get(2), "b");
    }

    public function testCollectionCanMoveSomePositions()
    {
        $arr = ["a", "b", "c", "d", "e", "f", "g"];

        $collection = collect($arr)->move(2,5);

        $this->assertEquals($collection->get(0), "a");
        $this->assertEquals($collection->get(1), "b");
        $this->assertEquals($collection->get(2), "d");
        $this->assertEquals($collection->get(3), "e");
        $this->assertEquals($collection->get(4), "f");
        $this->assertEquals($collection->get(5), "c");
        $this->assertEquals($collection->get(6), "g");
    }

    public function testCollectionCanMoveMenuModels()
    {
        $collection = $ordered = $this->createWholeMenu()->move(1,3);

        $this->assertEquals($collection->get(0)->order, 1);
        $this->assertEquals($collection->get(1)->order, 3);
        $this->assertEquals($collection->get(2)->order, 4);
        $this->assertEquals($collection->get(3)->order, 2);
        $this->assertEquals($collection->get(4)->order, 5);
        $this->assertEquals($collection->get(5)->order, 6);
    }

    public function testCollectionCanOrderMenuModels()
    {
        $ordered = $this->createWholeMenu()->move(1,3)->reorder();

        $this->assertEquals($ordered->get(0)->order, 1);
        $this->assertEquals($ordered->get(1)->order, 2);
        $this->assertEquals($ordered->get(2)->order, 3);
        $this->assertEquals($ordered->get(3)->order, 4);
        $this->assertEquals($ordered->get(4)->order, 5);
        $this->assertEquals($ordered->get(5)->order, 6);
    }

    public function testCollectionCanMoveAndReorderMenuModels()
    {
        $collection = $ordered = $this->createWholeMenu()->moveAndReorder(1,3);

        // After move, ids are changed but position have been reordered
        $this->assertEquals($collection->get(0)->id, 2);
        $this->assertEquals($collection->get(0)->order, 1);
        $this->assertEquals($collection->get(1)->id, 4);
        $this->assertEquals($collection->get(1)->order, 2);
        $this->assertEquals($collection->get(2)->id, 5);
        $this->assertEquals($collection->get(2)->order, 3);
        $this->assertEquals($collection->get(3)->id, 3);
        $this->assertEquals($collection->get(3)->order, 4);
        $this->assertEquals($collection->get(4)->id, 6);
        $this->assertEquals($collection->get(4)->order, 5);
        $this->assertEquals($collection->get(5)->id, 7);
        $this->assertEquals($collection->get(5)->order, 6);
    }

    private function createWholeMenu()
    {
        $parentMenu = Menu::factory()->create(["name" => "main"]);

        Menu::factory()->create(["order" => 1, "menu_id" => $parentMenu->id]);
        Menu::factory()->create(["order" => 2, "menu_id" => $parentMenu->id]);
        Menu::factory()->create(["order" => 3, "menu_id" => $parentMenu->id]);
        Menu::factory()->create(["order" => 4, "menu_id" => $parentMenu->id]);
        Menu::factory()->create(["order" => 5, "menu_id" => $parentMenu->id]);
        Menu::factory()->create(["order" => 6, "menu_id" => $parentMenu->id]);

        return Menu::with('menus')->first()->menus;
    }
}
