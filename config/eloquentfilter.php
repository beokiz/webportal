<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

return [

    /*
     |--------------------------------------------------------------------------
     | Eloquent Filter Settings
     |--------------------------------------------------------------------------
     |
     | This is the namespace all you Eloquent Model Filters will reside
     |
     */

    'namespace' => 'App\\ModelFilters\\',

    /*
    |--------------------------------------------------------------------------
    | Custom generator stub
    |--------------------------------------------------------------------------
    |
    | If you want to override the default stub this package provides
    | you can enter the path to your own at this point
    |
    */

    'generator' => [
        'stub' => app_path('stubs/model-filter.stub'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Default Paginator Limit For `paginateFilter` and `simplePaginateFilter`
    |--------------------------------------------------------------------------
    |
    | Set paginate limit
    |
    */

    'paginate_limit' => env('PAGINATION_LIMIT_DEFAULT', 15),

];
