<?php

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

    'accepted'        => 'The field must be accepted.',
    'accepted_if'     => 'The field must be accepted when :other is :value.',
    'active_url'      => 'The field must be a valid URL.',
    'after'           => 'The field must be a date after :date.',
    'after_or_equal'  => 'The field must be a date after or equal to :date.',
    'alpha'           => 'The field must only contain letters.',
    'alpha_dash'      => 'The field must only contain letters, numbers, dashes, and underscores.',
    'alpha_num'       => 'The field must only contain letters and numbers.',
    'array'           => 'The field must be an array.',
    'ascii'           => 'The field must only contain single-byte alphanumeric characters and symbols.',
    'before'          => 'The field must be a date before :date.',
    'before_or_equal' => 'The field must be a date before or equal to :date.',

    'between' => [
        'array'   => 'The field must have between :min and :max items.',
        'file'    => 'The field must be between :min and :max kilobytes.',
        'numeric' => 'The field must be between :min and :max.',
        'string'  => 'The field must be between :min and :max characters.',
    ],

    'boolean'           => 'The field must be true or false.',
    'can'               => 'The field contains an unauthorized value.',
    'confirmed'         => 'The field confirmation does not match.',
    'current_password'  => 'The password is incorrect.',
    'date'              => 'The field must be a valid date.',
    'date_equals'       => 'The field must be a date equal to :date.',
    'date_format'       => 'The field must match the format :format.',
    'decimal'           => 'The field must have :decimal decimal places.',
    'declined'          => 'The field must be declined.',
    'declined_if'       => 'The field must be declined when :other is :value.',
    'different'         => 'The field and :other must be different.',
    'digits'            => 'The field must be :digits digits.',
    'digits_between'    => 'The field must be between :min and :max digits.',
    'dimensions'        => 'The field has invalid image dimensions.',
    'distinct'          => 'The field has a duplicate value.',
    'doesnt_end_with'   => 'The field must not end with one of the following: :values.',
    'doesnt_start_with' => 'The field must not start with one of the following: :values.',
    'email'             => 'The field must be a valid email address.',
    'ends_with'         => 'The field must end with one of the following: :values.',
    'enum'              => 'The selected is invalid.',
    'exists'            => 'The selected is invalid.',
    'extensions'        => 'The field must have one of the following extensions: :values.',
    'file'              => 'The field must be a file.',
    'filled'            => 'The field must have a value.',

    'gt' => [
        'array'   => 'The field must have more than :value items.',
        'file'    => 'The field must be greater than :value kilobytes.',
        'numeric' => 'The field must be greater than :value.',
        'string'  => 'The field must be greater than :value characters.',
    ],

    'gte' => [
        'array'   => 'The field must have :value items or more.',
        'file'    => 'The field must be greater than or equal to :value kilobytes.',
        'numeric' => 'The field must be greater than or equal to :value.',
        'string'  => 'The field must be greater than or equal to :value characters.',
    ],

    'hex_color' => 'The field must be a valid hexadecimal color.',
    'image'     => 'The field must be an image.',
    'in'        => 'The selected is invalid.',
    'in_array'  => 'The field must exist in :other.',
    'integer'   => 'The field must be an integer.',
    'ip'        => 'The field must be a valid IP address.',
    'ipv4'      => 'The field must be a valid IPv4 address.',
    'ipv6'      => 'The field must be a valid IPv6 address.',
    'json'      => 'The field must be a valid JSON string.',
    'lowercase' => 'The field must be lowercase.',

    'lt' => [
        'array'   => 'The field must have less than :value items.',
        'file'    => 'The field must be less than :value kilobytes.',
        'numeric' => 'The field must be less than :value.',
        'string'  => 'The field must be less than :value characters.',
    ],

    'lte' => [
        'array'   => 'The field must not have more than :value items.',
        'file'    => 'The field must be less than or equal to :value kilobytes.',
        'numeric' => 'The field must be less than or equal to :value.',
        'string'  => 'The field must be less than or equal to :value characters.',
    ],

    'mac_address' => 'The field must be a valid MAC address.',

    'max' => [
        'array'   => 'The field must not have more than :max items.',
        'file'    => 'The field must not be greater than :max kilobytes.',
        'numeric' => 'The field must not be greater than :max.',
        'string'  => 'The field must not be greater than :max characters.',
    ],

    'max_digits' => 'The field must not have more than :max digits.',
    'mimes'      => 'The field must be a file of type: :values.',
    'mimetypes'  => 'The field must be a file of type: :values.',

    'min' => [
        'array'   => 'The field must have at least :min items.',
        'file'    => 'The field must be at least :min kilobytes.',
        'numeric' => 'The field must be at least :min.',
        'string'  => 'The field must be at least :min characters.',
    ],

    'min_digits'       => 'The field must have at least :min digits.',
    'missing'          => 'The field must be missing.',
    'missing_if'       => 'The field must be missing when :other is :value.',
    'missing_unless'   => 'The field must be missing unless :other is :value.',
    'missing_with'     => 'The field must be missing when :values is present.',
    'missing_with_all' => 'The field must be missing when :values are present.',
    'multiple_of'      => 'The field must be a multiple of :value.',
    'not_in'           => 'The selected is invalid.',
    'not_regex'        => 'The field format is invalid.',
    'numeric'          => 'The field must be a number.',

    'password' => [
        'letters'       => 'The field must contain at least one letter.',
        'mixed'         => 'The field must contain at least one uppercase and one lowercase letter.',
        'numbers'       => 'The field must contain at least one number.',
        'symbols'       => 'The field must contain at least one symbol.',
        'uncompromised' => 'The given has appeared in a data leak. Please choose a different.',
    ],

    'present'              => 'The field must be present.',
    'present_if'           => 'The field must be present when :other is :value.',
    'present_unless'       => 'The field must be present unless :other is :value.',
    'present_with'         => 'The field must be present when :values is present.',
    'present_with_all'     => 'The field must be present when :values are present.',
    'prohibited'           => 'The field is prohibited.',
    'prohibited_if'        => 'The field is prohibited when :other is :value.',
    'prohibited_unless'    => 'The field is prohibited unless :other is in :values.',
    'prohibits'            => 'The field prohibits :other from being present.',
    'regex'                => 'The field format is invalid.',
    'required'             => 'The field is required.',
    'required_array_keys'  => 'The field must contain entries for: :values.',
    'required_if'          => 'The field is required when :other is :value.',
    'required_if_accepted' => 'The field is required when :other is accepted.',
    'required_unless'      => 'The field is required unless :other is in :values.',
    'required_with'        => 'The field is required when :values is present.',
    'required_with_all'    => 'The field is required when :values are present.',
    'required_without'     => 'The field is required when :values is not present.',
    'required_without_all' => 'The field is required when none of :values are present.',
    'same'                 => 'The field must match :other.',

    'size' => [
        'array'   => 'The field must contain :size items.',
        'file'    => 'The field must be :size kilobytes.',
        'numeric' => 'The field must be :size.',
        'string'  => 'The field must be :size characters.',
    ],

    'starts_with' => 'The field must start with one of the following: :values.',
    'string'      => 'The field must be a string.',
    'timezone'    => 'The field must be a valid timezone.',
    'unique'      => 'The has already been taken.',
    'uploaded'    => 'The failed to upload.',
    'uppercase'   => 'The field must be uppercase.',
    'url'         => 'The field must be a valid URL.',
    'ulid'        => 'The field must be a valid ULID.',
    'uuid'        => 'The field must be a valid UUID.',

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
        'attribute-name' => [
            'rule-name' => 'custom-message',
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

    'attributes' => [],

];
