<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

return [

    /*
    |--------------------------------------------------------------------------
    | HTTP Exceptions Lines
    |--------------------------------------------------------------------------
    */

    'http_204_name'      => "No Content",
    'http_400_name'      => "Bad Request",
    'http_401_name'      => "Unauthorized",
    'http_403_name'      => "Forbidden",
    'http_404_name'      => "Not Found",
    'http_404_item_name' => "Item Not Found",
    'http_404_page_name' => "Page Not Found",
    'http_405_name'      => "Method Not Allowed",
    'http_413_name'      => "Payload Too Large",
    'http_419_name'      => "Page Expired",
    'http_422_name'      => "Unprocessable Entity",
    'http_429_name'      => "Too Many Requests",
    'http_500_name'      => "Internal Server Error",
    'http_503_name'      => "Service Unavailable",

    'http_204_msg'      => "Error! No content found.",
    'http_400_msg'      => "Error! Bad request.",
    'http_401_msg'      => "Error! You must be logged in to make this request.",
    'http_403_msg'      => "Error! Access denied.",
    'http_404_msg'      => "Error! Method not found.",
    'http_404_item_msg' => "Error! Item not found.",
    'http_404_page_msg' => "Error! Page not found.",
    'http_405_msg'      => "Error! The :method method is not supported for this route. Supported methods: :methods.",
    'http_413_msg'      => "Error! Payload too large.",
    'http_419_msg'      => "Error! Page expired.",
    'http_422_msg'      => "Error! The given data was invalid.",
    'http_429_msg'      => "Error! Too many requests, try again later.",
    'http_500_msg'      => "Internal server error, try again later.",
    'http_503_msg'      => "Error! Service unavailable.",

    /*
    |--------------------------------------------------------------------------
    | Custom Exceptions Lines
    |--------------------------------------------------------------------------
    */

    '2fa_default_error' => 'Fehler! Bitte versuchen Sie es später noch einmal.',

    'cannot_update_selected_item' => "Error! You cannot update selected item.",
    'cannot_delete_selected_item' => "Error! You cannot delete selected item.",
    'cannot_update_selected_user' => "Error! You cannot update selected user.",
    'cannot_delete_selected_user' => "Error! You cannot delete selected user.",

    'user_does_not_have_access' => "Error! You do not have access to this page or action.",

    'user_does_not_have_the_required_roles_exception_msg' => "To view the selected page, you must have one of the roles: :roles.",

    'service_not_configured_exception_msg' => "Error! \":name\" service is not configured.",

];
