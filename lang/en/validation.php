<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted'                  => "The \":attribute\" must be accepted.",
    'accepted_if'               => "The \":attribute\" must be accepted when :other is :value.",
    'active_url'                => "The \":attribute\" is not a valid URL.",
    'after'                     => "The \":attribute\" must be a date after :date.",
    'after_or_equal'            => "The \":attribute\" must be a date after or equal to :date.",
    'alpha'                     => "The \":attribute\" must only contain letters.",
    'alpha_dash'                => "The \":attribute\" must only contain letters, numbers, dashes and underscores.",
    'alpha_num'                 => "The \":attribute\" must only contain letters and numbers.",
    'array'                     => "The \":attribute\" must be an array.",
    'before'                    => "The \":attribute\" must be a date before :date.",
    'before_or_equal'           => "The \":attribute\" must be a date before or equal to :date.",
    'between'                   => [
        'numeric' => "The \":attribute\" must be between :min and :max.",
        'file'    => "The \":attribute\" must be between :min and :max kilobytes.",
        'string'  => "The \":attribute\" must be between :min and :max characters.",
        'array'   => "The \":attribute\" must have between :min and :max items.",
    ],
    'boolean'                   => "The \":attribute\" field must be true or false.",
    'confirmed'                 => "The \":attribute\" confirmation does not match.",
    'current_password'          => "The password is incorrect.",
    'date'                      => "The \":attribute\" is not a valid date.",
    'date_equals'               => "The \":attribute\" must be a date equal to :date.",
    'date_format'               => "The \":attribute\" does not match the format :format.",
    'declined'                  => "The \":attribute\" must be declined.",
    'declined_if'               => "The \":attribute\" must be declined when :other is :value.",
    'different'                 => "The \":attribute\" and :other must be different.",
    'digits'                    => "The \":attribute\" must be :digits digits.",
    'digits_between'            => "The \":attribute\" must be between :min and :max digits.",
    'dimensions'                => "The \":attribute\" has invalid image dimensions.",
    'distinct'                  => "The \":attribute\" field has a duplicate value.",
    'email'                     => "The \":attribute\" must be a valid email address.",
    'ends_with'                 => "The \":attribute\" must end with one of the following: :values.",
    'enum'                      => "The selected \":attribute\" is invalid.",
    'exists'                    => "The selected \":attribute\" is invalid.",
    'file'                      => "The \":attribute\" must be a file.",
    'filled'                    => "The \":attribute\" field must have a value.",
    'gt'                        => [
        'numeric' => "The \":attribute\" must be greater than :value.",
        'file'    => "The \":attribute\" must be greater than :value kilobytes.",
        'string'  => "The \":attribute\" must be greater than :value characters.",
        'array'   => "The \":attribute\" must have more than :value items.",
    ],
    'gte'                       => [
        'numeric' => "The \":attribute\" must be greater than or equal to :value.",
        'file'    => "The \":attribute\" must be greater than or equal to :value kilobytes.",
        'string'  => "The \":attribute\" must be greater than or equal to :value characters.",
        'array'   => "The \":attribute\" must have :value items or more.",
    ],
    'image'                     => "The \":attribute\" must be an image.",
    'in'                        => "The selected \":attribute\" is invalid.",
    'in_array'                  => "The \":attribute\" field does not exist in :other.",
    'integer'                   => "The \":attribute\" must be an integer.",
    'ip'                        => "The \":attribute\" must be a valid IP address.",
    'ipv4'                      => "The \":attribute\" must be a valid IPv4 address.",
    'ipv6'                      => "The \":attribute\" must be a valid IPv6 address.",
    'json'                      => "The \":attribute\" must be a valid JSON string.",
    'lt'                        => [
        'numeric' => "The \":attribute\" must be less than :value.",
        'file'    => "The \":attribute\" must be less than :value kilobytes.",
        'string'  => "The \":attribute\" must be less than :value characters.",
        'array'   => "The \":attribute\" must have less than :value items.",
    ],
    'lte'                       => [
        'numeric' => "The \":attribute\" must be less than or equal to :value.",
        'file'    => "The \":attribute\" must be less than or equal to :value kilobytes.",
        'string'  => "The \":attribute\" must be less than or equal to :value characters.",
        'array'   => "The \":attribute\" must not have more than :value items.",
    ],
    'mac_address'               => "The \":attribute\" must be a valid MAC address.",
    'max'                       => [
        'numeric' => "The \":attribute\" must not be greater than :max.",
        'file'    => "The \":attribute\" must not be greater than :max kilobytes.",
        'string'  => "The \":attribute\" must not be greater than :max characters.",
        'array'   => "The \":attribute\" must not have more than :max items.",
    ],
    'mimes'                     => "The \":attribute\" must be a file of type: :values.",
    'mimetypes'                 => "The \":attribute\" must be a file of type: :values.",
    'min'                       => [
        'numeric' => "The \":attribute\" must be at least :min.",
        'file'    => "The \":attribute\" must be at least :min kilobytes.",
        'string'  => "The \":attribute\" must be at least :min characters.",
        'array'   => "The \":attribute\" must have at least :min items.",
    ],
    'multiple_of'               => "The \":attribute\" must be a multiple of :value.",
    'not_in'                    => "The selected \":attribute\" is invalid.",
    'not_regex'                 => "The \":attribute\" format is invalid.",
    'numeric'                   => "The \":attribute\" must be a number.",
    'password'                  => "The password is incorrect.",
    'present'                   => "The \":attribute\" field must be present.",
    'prohibited'                => "The \":attribute\" field is prohibited.",
    'prohibited_if'             => "The \":attribute\" field is prohibited when :other is :value.",
    'prohibited_unless'         => "The \":attribute\" field is prohibited unless :other is in :values.",
    'prohibits'                 => "The \":attribute\" field prohibits :other from being present.",
    'regex'                     => "The \":attribute\" format is invalid.",
    'required'                  => "The \":attribute\" field is required.",
    'required_array_keys'       => "The \":attribute\" field must contain entries for: :values.",
    'required_if'               => "The \":attribute\" field is required when :other is :value.",
    'required_unless'           => "The \":attribute\" field is required unless :other is in :values.",
    'required_with'             => "The \":attribute\" field is required when :values is present.",
    'required_with_all'         => "The \":attribute\" field is required when :values are present.",
    'required_without'          => "The \":attribute\" field is required when :values is not present.",
    'required_without_all'      => "The \":attribute\" field is required when none of :values are present.",
    'same'                      => "The \":attribute\" and :other must match.",
    'size'                      => [
        'numeric' => "The \":attribute\" must be :size.",
        'file'    => "The \":attribute\" must be :size kilobytes.",
        'string'  => "The \":attribute\" must be :size characters.",
        'array'   => "The \":attribute\" must contain :size items.",
    ],
    'starts_with'               => "The \":attribute\" must start with one of the following: :values.",
    'string'                    => "The \":attribute\" must be a string.",
    'timezone'                  => "The \":attribute\" must be a valid timezone.",
    'unique'                    => "The \":attribute\" has already been taken.",
    'uploaded'                  => "The \":attribute\" failed to upload.",
    'url'                       => "The \":attribute\" must be a valid URL.",
    'uuid'                      => "The \":attribute\" must be a valid UUID.",

    /*
     * Custom
     */
    'integer_or_float'          => "The \":attribute\" must be a valid integer or float with the :before digits before decimal and :after decimal digits.",
    'max_decimal_digits'        => "The \":attribute\" must be a valid integer or float with :max decimal digits.",
    'phone'                     => "The \":attribute\" must be a valid telephone number.",
    'timestamp'                 => "The \":attribute\" must be a valid timestamp.",
    'unix_path'                 => "The \":attribute\" must be a valid Unix path.",
    'windows_disk'              => "The \":attribute\" must be a valid Windows Disk Driver.",
    'unix_path_or_windows_disk' => "The \":attribute\" must be a valid Unix path or Windows Disk Driver.",
    'url_or_ipv4'               => "The \":attribute\" must be a valid URL or IPv4 address.",
    'fqdn'                      => "The \":attribute\" must be a valid FQDN.",
    'fqdn_or_url'               => "The \":attribute\" must be a valid FQDN or URL.",
    'fqdn_or_ipv4'              => "The \":attribute\" must be a valid FQDN or IPv4 address.",
    'url_or_fqdn_or_ipv4'       => "The \":attribute\" must be a valid URL, FQDN or IPv4 address.",
    'port_number'               => "The \":attribute\" must be a valid TCP/IP Port number.",
    'url_path'                  => "The \":attribute\" must be a valid URL Path.",
    'not_present'               => "The \":attribute\" must not be present",
    'not_present_with'          => "The \":attribute\" must not be present if \":value\" is present.",
    'file_name'                 => "The \":attribute\" must be a file name with one of following type: :values.",
    'not_match_old_password'    => "The \":attribute\" should not be same as old password.",
    'date_difference_greater'   => "The \":attribute\" must be at least :days days greater than the :other_field date.",
    'date_difference_less'      => "The \":attribute\" must be at most :days days less than the :other_field date.",

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'throttle' => "Too many attempts. Please try again in :seconds seconds.",

        'invalid' => "The selected \":attribute\" is invalid.",

        'password' => [
//            'regex' => "The password must contain: at least one uppercase letter, at least one lowercase letter, at least one digit, 8 characters in length.",
            'regex' => "The password does not meet the minimum requirements.",
            'min'   => "Your password must be at least :min characters long.",
        ],

        'email' => [
            'unique' => "The email address is already assigned to another user.",
        ],

        'attribute-name' => [
            'rule-name' => "custom-message",
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [
        'user'                   => "User",
        'users'                  => "Users",
        'user_id'                => "User",
        'user_ids'               => "Users",
        'role'                   => "Role",
        'roles'                  => "Roles",
        'role_id'                => "Role",
        'role_ids'               => "Roles",
        'domain'                 => "Domain",
        'domains'                => "Domains",
        'domain_id'              => "Domain",
        'domain_ids'             => "Domains",
        'subdomain'              => "Subdomain",
        'subdomains'             => "Subdomains",
        'subdomain_id'           => "Subdomain",
        'subdomains_id'          => "Subdomains",
        'kita'                   => "Kita",
        'kitas'                  => "Kitas",
        'kita_id'                => "Kita",
        'kita_ids'               => "Kitas",
        'milestone'              => "Milestone",
        'milestones'             => "Milestones",
        'milestone_id'           => "Milestone",
        'milestone_ids'          => "Milestones",
        'evaluation'             => "Evaluation",
        'evaluations'            => "Evaluations",
        'evaluation_id'          => "Evaluation",
        'evaluation_ids'         => "Evaluations",
        'survey_time_period'     => "Survey time period",
        'survey_time_periods'    => "Survey time periods",
        'survey_time_period_id'  => "Survey time period",
        'survey_time_period_ids' => "Survey time periods",
        'operator_id'            => "Operator",
        'operator_ids'           => "Operators",
        'training_id'            => "Training",
        'training_ids'           => "Trainings",
        'training_proposal_id'   => "Training proposal",
        'training_proposal_ids'  => "Training proposals",

        'id'                                       => "ID",
        'uuid'                                     => "UUID",
        'email'                                    => "Email",
        'phone_number'                             => "Phone number",
        'password'                                 => "Password",
        'token'                                    => "Token",
        'first_name'                               => "First name",
        'last_name'                                => "Last name",
        'two_factor_code'                          => "2FA verification code",
        'current_password'                         => "Current password",
        'two_factor_auth_enabled'                  => "2FA Enabled",
        'name'                                     => "Name",
        'abbreviation'                             => "Abbreviation",
        'order'                                    => "Order",
        'age_2_red_threshold'                      => "Age2 Red Threshold",
        'age_2_red_threshold_daz'                  => "Age2 Red Threshold Daz",
        'age_2_yellow_threshold'                   => "Age 2 Yellow Threshold",
        'age_2_yellow_threshold_daz'               => "Age 2 Yellow Threshold Daz",
        'age_4_red_threshold'                      => "Age 4 Red Threshold",
        'age_4_red_threshold_daz'                  => "Age 4 Red Threshold Daz",
        'age_4_yellow_threshold'                   => "Age 4 Yellow Threshold",
        'age_4_yellow_threshold_daz'               => "Age 4 Yellow Threshold Daz",
        'title'                                    => "Title",
        'emphasis'                                 => "Emphasis",
        'emphasis_daz'                             => "Emphasis with Daz",
        'age'                                      => "Age",
        'zip_code'                                 => "ZIP code",
        'additional_info'                          => "Additional info",
        'data'                                     => "Data",
        'value'                                    => "Value",
        'ratings'                                  => "Ratings",
        'child_duration_in_kita'                   => "Child duration in kita",
        'integration_status'                       => "Integration status",
        'speech_therapy_status'                    => "Speech therapy status",
        'year'                                     => "Year",
        'age_year'                                 => "Age group in years",
        'survey_start_date'                        => "Survey start date",
        'survey_end_date'                          => "Survey end date",
        'provider_of_the_kita'                     => "Provider of the kita",
        'city'                                     => "City",
        'kita_number'                              => "Kita number",
        'district'                                 => "District",
        'street'                                   => "Street",
        'house_number'                             => "House number",
        'kita_additional_info'                     => "Additional information",
        'year_of_evaluations'                      => "Year of evaluations",
        'evaluations_without_daz_2_total_per_year' => "Assessments submitted so far",
        'evaluations_without_daz_4_total_per_year' => "Assessments submitted so far",
        'evaluations_with_daz_2_total_per_year'    => "Assessments submitted so far",
        'evaluations_with_daz_4_total_per_year'    => "Assessments submitted so far",
        'children_2_born_per_year'                 => "Number of kids born in selected year",
        'children_4_born_per_year'                 => "Number of kids born in selected year",
        'children_2_with_german_lang'              => "Children with German as their heritage language",
        'children_4_with_german_lang'              => "Children with German as their heritage language",
        'children_2_with_foreign_lang'             => "Children with a non-German heritage language",
        'children_4_with_foreign_lang'             => "Children with a non-German heritage language",
        'file'                                     => "File",
        'self_training'                            => "Self training",
        'small'                                    => "Small",
        'large'                                    => "Large",
        'type'                                     => "Type",
        'approved'                                 => "Approved",
        'num_pedagogical_staff'                    => "Number of pedagogical staff",
        'combined'                                 => "Combined",
        'in-house'                                 => "In-house",
        'email_not_confirmed'                      => "Email not confirmed",
        'planned'                                  => "Planned",
        'confirmed'                                => "Confirmed",
        'completed'                                => "Completed",
        'open'                                     => "Open",
        'obsolete'                                 => "Obsolete",
        'reserved'                                 => "Reserved",
        'confirmation_pending'                     => "Confirmation pending",
        'cancelled'                                => "Cancelled",
        'multi_id'                                 => "Multiplier",
        'first_date'                               => "First date",
        'first_date_start_and_end_time'            => "First date start and end time",
        'second_date'                              => "Second date",
        'second_date_start_and_end_time'           => "Second date start and end time",
        'location'                                 => "Location",
        'max_participant_count'                    => "Max participant count",
        'participant_count'                        => "Participant count",
        'status'                                   => "Status",
        'notes'                                    => "Notes",
        'other_operator'                           => "Other operator",

        // 'parent_field.*.child_field' => "Parent field #:counter child field",
    ],

];
