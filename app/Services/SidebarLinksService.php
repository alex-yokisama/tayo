<?php

namespace App\Services;

class SidebarLinksService
{
    public static function getLinks($active = '')
    {
        return collect([
            [
                'name' => 'Products',
                'items' => collect([
                    ['path' => '/admin/attribute_groups', 'name' => 'Attribute groups'],
                    ['path' => '/admin/attributes', 'name' => 'Attributes'],
                    ['path' => '/admin/categories', 'name' => 'Categories'],
                    ['path' => '/admin/products', 'name' => 'Products'],
                ])
            ],
            [
                'name' => 'Films',
                'items' => collect([
                    ['path' => '/admin/film_genres', 'name' => 'Film genres'],
                    ['path' => '/admin/age_ratings', 'name' => 'Age ratings'],
                    ['path' => '/admin/films', 'name' => 'Films']
                ])
            ],
            [
                'name' => 'OS',
                'items' => collect([
                    ['path' => '/admin/licenses', 'name' => 'Licenses'],
                    ['path' => '/admin/oss', 'name' => 'OS'],
                ])
            ],
            [
                'name' => 'Users',
                'items' => collect([
                    ['path' => '/admin/roles', 'name' => 'Roles'],
                    ['path' => '/admin/users', 'name' => 'Users'],
                ])
            ],
            [
                'name' => 'Other',
                'items' => collect([
                    ['path' => '/admin/agents', 'name' => 'Agents'],
                    ['path' => '/admin/brands', 'name' => 'Brands'],
                    ['path' => '/admin/countries', 'name' => 'Countries'],
                    ['path' => '/admin/currencies', 'name' => 'Currencies'],
                    ['path' => '/admin/measures', 'name' => 'Measure units'],
                    ['path' => '/admin/websites', 'name' => 'Websites'],
                ])
            ]
        ])->map(function($item) use ($active) {
            $item = (object)$item;
            $item->items = $item->items->map(function($item) use ($active) {
                $item = (object)$item;
                $item->active = $item->path == $active;
                return $item;
            });
            $item->active = $item->items->some(function($item) {
                return $item->active;
            });
            return $item;
        });
    }
}
