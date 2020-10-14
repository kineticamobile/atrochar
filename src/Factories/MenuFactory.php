<?php

namespace Kineticamobile\Atrochar\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Kineticamobile\Atrochar\Models\Menu;

class MenuFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Menu::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->word,
            'description' => $this->faker->word,
            'href' => $this->faker->url,
            'newwindow' => $this->faker->boolean,
            'iframe' => $this->faker->boolean,
            'menu_id' => null,
            'order' => 1
        ];
    }
}
