<?php

/*
|--------------------------------------------------------------------------
| Golf Course API integration
|--------------------------------------------------------------------------
|
| Mapped ENV variables for the Golf Course API
| Not 100% sure I will fully get it integrated but it would be pretty 
| awesome to have course data available
|
*/
return [
    'base_url' => env('GOLF_COURSE_API_BASE_URL'),
    'auth_key' => env('GOLF_COURSE_API_AUTHORIZATION_KEY'),
    'auth_value' => env('GOLF_COURSE_API_AUTHORIZATION_VALUE'),
];
