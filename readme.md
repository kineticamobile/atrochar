# Atrochar

Menu Generator for Laravel Apps - Populate menus in your views using Database backed Menus with @menu Blade component

## Installation

1. Via Composer

``` bash
$ composer require kineticamobile/atrochar
```

2. Add trait to your **User** model.

``` php

use Kineticamobile\Atrochar\Traits\MenuAbilities;

class User extends Authenticatable
{

    use MenuAbilities;
```

3. Run migrations

``` bash
$ php artisan migrate
```

## Simple Usage

### Create Menus and its items

You can access the Menu Management in your app "APP_URL/atrochar/menus"

or add link wherever you want

``` php
<a href="{{ route('atrochar.menus.index') }}"> {{ __('Menus') }} </a>
```

### Add a created menu in your views with blade component

- Using menu name
```php
    @menu("Dasboard")
```
- Using menu id
```php
    @menu(1)
```
- Using menu object
```php
    $menu = Menu::find(1);
    [...]
    @menu($menu)
```

## Advanced Usage

### Restrict access to menu management

By default all users can **manage** menus.  
You need to add the method `canManageMenus()` to your user model:

```php
    /**
     * @return bool
     */
    public function canManageMenus()
    {
        // For example
        return $this->hasRole('Admin');
    }
```

### Use of the links' permission field

By default all items can be **viewed**. But you can add permission in your links, if not empty this ability is checked against the User method `canViewMenuItem($ability)`  
You need to add the method `canViewMenuItem($ability)` to your user model to extend this behavior:

```php
    /**
     * @return bool
     */
    public function canViewMenuItem($ability)
    {
        // For example
        return $this->getAllPermissions()->pluck('name')->contains($ability);
    }
```

### Style your menus using themes or overriding default values

Using themes. Publish the config file in your app. In config/atrochar.php you can modify default values or add new themes

```bash
$ php artisan vendor:publish --tag=atrochar.config
```

```php
return [
    "defaultTheme" => [
        "linkTag" => "a",
        "class" => "",
        "activeClass" => "",
        "listStartTag" => "<ul>",
        "listEndTag" => "</ul>",
        "itemStartTag" => "<li>",
        "itemEndTag" => "</li>",
    ],
    "themes" => [
        "jetstream" => [
            "linkTag" => "a",
            "class" =>       "inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out",
            "activeClass" => "inline-flex items-center px-1 pt-1 border-b-2 border-indigo-400 text-sm font-medium leading-5 text-gray-900 focus:outline-none focus:border-indigo-700 transition duration-150 ease-in-out",
            "listStartTag" => "",
            "listEndTag" => "",
            "itemStartTag" => "",
            "itemEndTag" => "",
        ]
    ]
];
```

Once you've created a theme you can pass a string as a second argument in the @menu component the name of the theme

```php
    @menu("Dasboard", "jetstream")
```

If you pass an array as second argument the values override the defaultTheme values

```php
    @menu($menu, ["class" => "bg-gray-500 font-mono"])
```

## Security

If you discover any security related issues, please email author email instead of using the issue tracker.

## Credits

- [Raul Tierno](https://github.com/raultm)
- [Emilio Ortiz](https://github.com/branigan)
- [Daniel Macías](https://github.com/dmaciasr)

## Licence

MIT

[link-packagist]: https://packagist.org/packages/kineticamobile/atrochar
[link-downloads]: https://packagist.org/packages/kineticamobile/atrochar
[link-travis]: https://travis-ci.org/kineticamobile/atrochar
[link-styleci]: https://styleci.io/repos/12345678
[link-author]: https://github.com/kineticamobile
[link-contributors]: ../../contributors
