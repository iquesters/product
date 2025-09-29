<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Layout Configuration
    |--------------------------------------------------------------------------
    |
    | Specify the base layout that should be used for package views.
    | Default: Uses the package's built-in layout (product::layouts.package)
    |
    | Options:
    | - 'product::layouts.package' (default package layout)
    | - 'layouts.app' (use your application's layout)
    | - 'admin.layouts.master' (custom layout path)
    |
    */
    'layout' => env('PRODUCT_LAYOUT', 'product::layouts.package'),
];