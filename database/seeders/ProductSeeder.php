<?php

namespace Iquesters\Product\Database\Seeders;

use Iquesters\Foundation\Database\Seeders\BaseSeeder;

class ProductSeeder extends BaseSeeder
{
    protected string $moduleName = 'product';
    protected string $description = 'product module';
    protected array $metas = [
        'module_icon' => 'fa-solid fa-box',
        'module_sidebar_menu' => [
            [
                "icon" => "fa-solid fa-box",
                "label" => "Products",
                "route" => "products.index",
                "params" => ["organisationUid" => null]
            ]
        ]
    ];

    /**
     * Implement abstract method from BaseSeeder
     */
    protected function seedCustom(): void
    {
        // Add custom seeding logic here if needed
        // Leave empty if none
    }
}