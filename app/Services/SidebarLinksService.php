<?php

namespace App\Services;

class SidebarLinksService
{
    public static function getLinks($active = '')
    {
        return collect([
            ['path' => '/admin/agents', 'name' => 'Agents'],
            ['path' => '/admin/attribute_groups', 'name' => 'Attribute groups'],
            ['path' => '/admin/attributes', 'name' => 'Attributes'],
            ['path' => '/admin/brands', 'name' => 'Brands'],
            ['path' => '/admin/categories', 'name' => 'Categories'],
            ['path' => '/admin/countries', 'name' => 'Countries'],
            ['path' => '/admin/currencies', 'name' => 'Currencies'],
            ['path' => '/admin/measures', 'name' => 'Measure units'],
            ['path' => '/admin/products', 'name' => 'Products'],
            ['path' => '/admin/roles', 'name' => 'Roles'],
            ['path' => '/admin/users', 'name' => 'Users'],
            ['path' => '/admin/websites', 'name' => 'Websites']
        ])->map(function($item) use ($active) {
            $item = (object)$item;
            $item->active = $item->path == $active;
            return $item;
        });
    }
}
