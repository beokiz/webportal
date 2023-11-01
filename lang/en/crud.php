<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

return [

    /*
    |--------------------------------------------------------------------------
    | Common CRUD Lines
    |--------------------------------------------------------------------------
    */

    'common' => [
        'create_success' => "Item has been created successfully.",
        'create_error'   => "Error! Something went wrong while creating a item. Please try again later.",
        'update_success' => "Item has been updated successfully.",
        'update_error'   => "Error! Something went wrong while updating a item. Please try again later.",
        'update_denied'  => "Error! Selected item cannot be updated.",
        'delete_success' => "Item has been deleted successfully.",
        'delete_error'   => "Error! Something went wrong while deleting a item. Please try again later.",
        'delete_denied'  => "Error! Selected item cannot be deleted.",
    ],

    /*
    |--------------------------------------------------------------------------
    | Users CRUD Lines
    |--------------------------------------------------------------------------
    */

    'users' => [
        'create_success'  => "User has been created successfully.",
        'create_error'    => "Error! Something went wrong while creating a user. Please try again later.",
        'update_success'  => "User has been updated successfully.",
        'update_error'    => "Error! Something went wrong while updating a user. Please try again later.",
        'update_denied'   => "Error! Selected user cannot be updated.",
        'delete_success'  => "User has been deleted successfully.",
        'delete_error'    => "Error! Something went wrong while deleting a user. Please try again later.",
        'delete_denied'   => "Error! Selected user cannot be deleted.",
        'restore_success' => "User has been restored successfully.",
        'restore_error'   => "Error! Something went wrong while restoring a user. Please try again later.",
        'restore_denied'  => "Error! Selected user cannot be restored.",
    ],

    /*
    |--------------------------------------------------------------------------
    | Domains CRUD Lines
    |--------------------------------------------------------------------------
    */

    'domains' => [
        'create_success'  => "Domain has been created successfully.",
        'create_error'    => "Error! Something went wrong while creating a domain. Please try again later.",
        'update_success'  => "Domain has been updated successfully.",
        'update_error'    => "Error! Something went wrong while updating a domain. Please try again later.",
        'update_denied'   => "Error! Selected domain cannot be updated.",
        'delete_success'  => "Domain has been deleted successfully.",
        'delete_error'    => "Error! Something went wrong while deleting a domain. Please try again later.",
        'delete_denied'   => "Error! Selected domain cannot be deleted.",
        'restore_success' => "Domain has been restored successfully.",
        'restore_error'   => "Error! Something went wrong while restoring a domain. Please try again later.",
        'restore_denied'  => "Error! Selected domain cannot be restored.",
        'reorder_success' => "Domains has been reordered successfully.",
        'reorder_error'   => "Error! Something went wrong while reordering domains. Please try again later.",
    ],

    /*
    |--------------------------------------------------------------------------
    | Subdomains CRUD Lines
    |--------------------------------------------------------------------------
    */

    'subdomains' => [
        'create_success'  => "Subdomain has been created successfully.",
        'create_error'    => "Error! Something went wrong while creating a subdomain. Please try again later.",
        'update_success'  => "Subdomain has been updated successfully.",
        'update_error'    => "Error! Something went wrong while updating a subdomain. Please try again later.",
        'update_denied'   => "Error! Selected subdomain cannot be updated.",
        'delete_success'  => "Subdomain has been deleted successfully.",
        'delete_error'    => "Error! Something went wrong while deleting a subdomain. Please try again later.",
        'delete_denied'   => "Error! Selected subdomain cannot be deleted.",
        'restore_success' => "Subdomain has been restored successfully.",
        'restore_error'   => "Error! Something went wrong while restoring a subdomain. Please try again later.",
        'restore_denied'  => "Error! Selected subdomain cannot be restored.",
        'reorder_success' => "Subdomains has been reordered successfully.",
        'reorder_error'   => "Error! Something went wrong while reordering subdomains. Please try again later.",
    ],

    /*
    |--------------------------------------------------------------------------
    | Milestones CRUD Lines
    |--------------------------------------------------------------------------
    */

    'milestones' => [
        'create_success'  => "Milestone has been created successfully.",
        'create_error'    => "Error! Something went wrong while creating a milestone. Please try again later.",
        'update_success'  => "Milestone has been updated successfully.",
        'update_error'    => "Error! Something went wrong while updating a milestone. Please try again later.",
        'update_denied'   => "Error! Selected milestone cannot be updated.",
        'delete_success'  => "Milestone has been deleted successfully.",
        'delete_error'    => "Error! Something went wrong while deleting a milestone. Please try again later.",
        'delete_denied'   => "Error! Selected milestone cannot be deleted.",
        'restore_success' => "Milestone has been restored successfully.",
        'restore_error'   => "Error! Something went wrong while restoring a milestone. Please try again later.",
        'restore_denied'  => "Error! Selected milestone cannot be restored.",
        'reorder_success' => "Milestones has been reordered successfully.",
        'reorder_error'   => "Error! Something went wrong while reordering milestones. Please try again later.",
    ],

    /*
    |--------------------------------------------------------------------------
    | Kitas CRUD Lines
    |--------------------------------------------------------------------------
    */

    'kitas' => [
        'create_success'      => "Kita has been created successfully.",
        'create_error'        => "Error! Something went wrong while creating a kita. Please try again later.",
        'update_success'      => "Kita has been updated successfully.",
        'update_error'        => "Error! Something went wrong while updating a kita. Please try again later.",
        'update_denied'       => "Error! Selected kita cannot be updated.",
        'delete_success'      => "Kita has been deleted successfully.",
        'delete_error'        => "Error! Something went wrong while deleting a kita. Please try again later.",
        'delete_denied'       => "Error! Selected kita cannot be deleted.",
        'delete_users_denied' => "Error! The selected kita has associated users, so it cannot be deleted.",
        'restore_success'     => "Kita has been restored successfully.",
        'restore_error'       => "Error! Something went wrong while restoring a kita. Please try again later.",
        'restore_denied'      => "Error! Selected kita cannot be restored.",
        'reorder_success'     => "Kitas has been reordered successfully.",
        'reorder_error'       => "Error! Something went wrong while reordering kitas. Please try again later.",
    ],

    /*
    |--------------------------------------------------------------------------
    | Evaluation CRUD Lines
    |--------------------------------------------------------------------------
    */

    'evaluations' => [
        'create_success'      => "Evaluation has been created successfully.",
        'create_error'        => "Error! Something went wrong while creating a evaluation. Please try again later.",
        'update_success'      => "Evaluation has been updated successfully.",
        'update_error'        => "Error! Something went wrong while updating a evaluation. Please try again later.",
        'update_denied'       => "Error! Selected evaluation cannot be updated.",
        'save_success'        => "Evaluation has been saved successfully.",
        'save_error'          => "Error! Something went wrong while saving a evaluation. Please try again later.",
        'save_denied'         => "Error! Selected evaluation cannot be saved.",
        'check_success'       => "Evaluation has been checked successfully.",
        'check_error'         => "Error! Something went wrong while checking a evaluation. Please try again later.",
        'delete_success'      => "Evaluation has been deleted successfully.",
        'delete_error'        => "Error! Something went wrong while deleting a evaluation. Please try again later.",
        'delete_denied'       => "Error! Selected evaluation cannot be deleted.",
        'delete_users_denied' => "Error! The selected evaluation has associated users, so it cannot be deleted.",
        'restore_success'     => "Evaluation has been restored successfully.",
        'restore_error'       => "Error! Something went wrong while restoring a evaluation. Please try again later.",
        'restore_denied'      => "Error! Selected evaluation cannot be restored.",
    ],

];
