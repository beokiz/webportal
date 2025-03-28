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
        'create_success'                 => "User has been created successfully.",
        'create_error'                   => "Error! Something went wrong while creating a user. Please try again later.",
        'update_success'                 => "User has been updated successfully.",
        'update_error'                   => "Error! Something went wrong while updating a user. Please try again later.",
        'update_denied'                  => "Error! Selected user cannot be updated.",
        'send_verification_link_success' => "Verification link has been sent successfully.",
        'send_verification_link_error'   => "Error! Failed to send verification link. Please try again later.",
        'send_verification_link_denied'  => "Error! The email has already been verified.",
        'welcome_notification_success'   => "Welcome notification has been sent successfully.",
        'welcome_notification_error'     => "Error! Failed to send welcome notification. Please try again later.",
        'delete_success'                 => "User has been deleted successfully.",
        'delete_error'                   => "Error! Something went wrong while deleting a user. Please try again later.",
        'delete_denied'                  => "Error! Selected user cannot be deleted.",
        'restore_success'                => "User has been restored successfully.",
        'restore_error'                  => "Error! Something went wrong while restoring a user. Please try again later.",
        'restore_denied'                 => "Error! Selected user cannot be restored.",
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
        'create_success'           => "KiTa has been created successfully.",
        'create_error'             => "Error! Something went wrong while creating a KiTa. Please try again later.",
        'update_success'           => "KiTa has been updated successfully.",
        'update_error'             => "Error! Something went wrong while updating a KiTa. Please try again later.",
        'update_denied'            => "Error! Selected KiTa cannot be updated.",
        'send_certificate_success' => "Certificate has been sent successfully.",
        'send_certificate_error'   => "Error! Failed to certificate link. Please try again later.",
        'delete_success'           => "KiTa has been deleted successfully.",
        'delete_error'             => "Error! Something went wrong while deleting a KiTa. Please try again later.",
        'delete_denied'            => "Error! Selected KiTa cannot be deleted.",
        'delete_users_denied'      => "Error! The selected KiTa has associated users, so it cannot be deleted.",
        'restore_success'          => "KiTa has been restored successfully.",
        'restore_error'            => "Error! Something went wrong while restoring a KiTa. Please try again later.",
        'restore_denied'           => "Error! Selected KiTa cannot be restored.",
        'reorder_success'          => "KiTas has been reordered successfully.",
        'reorder_error'            => "Error! Something went wrong while reordering KiTas. Please try again later.",
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
        'pdf_error'           => "Error! Something went wrong while creating the file, please try again later.",
        'export_error'        => "Error! Something went wrong while creating the file, please try again later.",
    ],

    /*
    |--------------------------------------------------------------------------
    | Survey Time Period CRUD Lines
    |--------------------------------------------------------------------------
    */

    'survey_time_periods' => [
        'create_success'  => "Survey time period has been created successfully.",
        'create_error'    => "Error! Something went wrong while creating a survey time period. Please try again later.",
        'update_success'  => "Survey time period has been updated successfully.",
        'update_error'    => "Error! Something went wrong while updating a survey time period. Please try again later.",
        'update_denied'   => "Error! Selected survey time period cannot be updated.",
        'delete_success'  => "Survey time period has been deleted successfully.",
        'delete_error'    => "Error! Something went wrong while deleting a survey time period. Please try again later.",
        'delete_denied'   => "Error! Selected survey time period cannot be deleted.",
        'restore_success' => "Survey time period has been restored successfully.",
        'restore_error'   => "Error! Something went wrong while restoring a survey time period. Please try again later.",
        'restore_denied'  => "Error! Selected survey time period cannot be restored.",
    ],

    /*
    |--------------------------------------------------------------------------
    | Yearly Evaluation CRUD Lines
    |--------------------------------------------------------------------------
    */

    'yearly_evaluations' => [
        'create_success'  => "Yearly evaluation has been created successfully.",
        'create_error'    => "Error! Something went wrong while creating a yearly evaluation. Please try again later.",
        'update_success'  => "Yearly evaluation has been updated successfully.",
        'update_error'    => "Error! Something went wrong while updating a yearly evaluation. Please try again later.",
        'update_denied'   => "Error! Selected yearly evaluation cannot be updated.",
        'delete_success'  => "Yearly evaluation has been deleted successfully.",
        'delete_error'    => "Error! Something went wrong while deleting a yearly evaluation. Please try again later.",
        'delete_denied'   => "Error! Selected yearly evaluation cannot be deleted.",
        'restore_success' => "Yearly evaluation has been restored successfully.",
        'restore_error'   => "Error! Something went wrong while restoring a yearly evaluation. Please try again later.",
        'restore_denied'  => "Error! Selected yearly evaluation cannot be restored.",
    ],

    /*
    |--------------------------------------------------------------------------
    | Setting CRUD Lines
    |--------------------------------------------------------------------------
    */

    'settings' => [
        'update_success' => "Setting has been updated successfully.",
        'update_error'   => "Error! Something went wrong while updating a setting. Please try again later.",
        'update_denied'  => "Error! Selected setting cannot be updated.",
    ],

    /*
    |--------------------------------------------------------------------------
    | Downloadable Files CRUD Lines
    |--------------------------------------------------------------------------
    */

    'downloadable_files' => [
        'create_success'  => "Downloadable file has been created successfully.",
        'create_error'    => "Error! Something went wrong while creating a downloadable file. Please try again later.",
        'update_success'  => "Downloadable file has been updated successfully.",
        'update_error'    => "Error! Something went wrong while updating a downloadable file. Please try again later.",
        'update_denied'   => "Error! Selected downloadable file cannot be updated.",
        'delete_success'  => "Downloadable file has been deleted successfully.",
        'delete_error'    => "Error! Something went wrong while deleting a downloadable file. Please try again later.",
        'delete_denied'   => "Error! Selected downloadable file cannot be deleted.",
        'restore_success' => "Downloadable file has been restored successfully.",
        'restore_error'   => "Error! Something went wrong while restoring a downloadable file. Please try again later.",
        'restore_denied'  => "Error! Selected downloadable file cannot be restored.",
    ],

    /*
    |--------------------------------------------------------------------------
    | Operators CRUD Lines
    |--------------------------------------------------------------------------
    */

    'operators' => [
        'create_success'  => "Operator has been created successfully.",
        'create_error'    => "Error! Something went wrong while creating a operator. Please try again later.",
        'update_success'  => "Operator has been updated successfully.",
        'update_error'    => "Error! Something went wrong while updating a operator. Please try again later.",
        'update_denied'   => "Error! Selected operator cannot be updated.",
        'delete_success'  => "Operator has been deleted successfully.",
        'delete_error'    => "Error! Something went wrong while deleting a operator. Please try again later.",
        'delete_denied'   => "Error! Selected operator cannot be deleted.",
        'restore_success' => "Operator has been restored successfully.",
        'restore_error'   => "Error! Something went wrong while restoring a operator. Please try again later.",
        'restore_denied'  => "Error! Selected operator cannot be restored.",
    ],

    /*
    |--------------------------------------------------------------------------
    | Trainings CRUD Lines
    |--------------------------------------------------------------------------
    */

    'trainings' => [
        'create_success'  => "The training has been created successfully.",
        'create_error'    => "Error! Something went wrong while creating the training. Please try again later.",
        'update_success'  => "The training has been updated successfully.",
        'update_error'    => "Error! Something went wrong while updating the training. Please try again later.",
        'update_denied'   => "Error! The selected training cannot be updated.",
        'delete_success'  => "The training has been deleted successfully.",
        'delete_error'    => "Error! Something went wrong while deleting the training. Please try again later.",
        'delete_denied'   => "Error! The selected training cannot be deleted.",
        'restore_success' => "The training has been restored successfully.",
        'restore_error'   => "Error! Something went wrong while restoring the training. Please try again later.",
        'restore_denied'  => "Error! The selected training cannot be restored.",

        'choose_location' => "[Ask for location at one of the daycare centers!]",
    ],

    /*
    |--------------------------------------------------------------------------
    | Training Proposals CRUD Lines
    |--------------------------------------------------------------------------
    */

    'training_proposals' => [
        'create_success'  => "The training proposal has been created successfully.",
        'create_error'    => "Error! Something went wrong while creating the training proposal. Please try again later.",
        'update_success'  => "The training proposal has been updated successfully.",
        'update_error'    => "Error! Something went wrong while updating the training proposal. Please try again later.",
        'update_denied'   => "Error! The selected training proposal cannot be updated.",
        'confirm_success' => "The training proposal has been confirmed successfully.",
        'confirm_error'   => "Error! Something went wrong while confirming the training proposal. Please try again later.",
        'delete_success'  => "The training proposal has been deleted successfully.",
        'delete_error'    => "Error! Something went wrong while deleting the training proposal. Please try again later.",
        'delete_denied'   => "Error! The selected training proposal cannot be deleted.",
        'restore_success' => "The training proposal has been restored successfully.",
        'restore_error'   => "Error! Something went wrong while restoring the training proposal. Please try again later.",
        'restore_denied'  => "Error! The selected training proposal cannot be restored.",
    ],


];
