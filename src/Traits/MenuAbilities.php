<?php

namespace Kineticamobile\Atrochar\Traits;

trait MenuAbilities
{
    /**
     * Return true or false if the user manage menus.
     *
     * @param void
     * @return  bool
     */
    public function canManageMenus()
    {
        return true;
    }

    /**
     * Return true or false if the user can view item associate with ability/permission.
     *
     * @param void
     * @return  bool
     */
    public function canViewMenuItem($ability)
    {
        return false;
    }
}
