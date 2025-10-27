<?php

namespace Iquesters\Product\Config;

use Iquesters\Foundation\Support\BaseConf;
use Iquesters\Foundation\Enums\Module;

class ProductConf extends BaseConf
{
    // Inherited property of BaseConf, must initialize
    protected ?string $identifier = Module::PRODUCT;

    // properties of this class
    protected string $product_layout;

    protected function prepareDefault(BaseConf $default_values)
    {
        $default_values->product_layout = 'product::layouts.package';
    }
}