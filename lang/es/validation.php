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

    'accepted'        => 'El campo debe ser aceptado.',
    'accepted_if'     => 'El campo debe ser aceptado cuando :other es :value.',
    'active_url'      => 'El campo debe ser una URL válida.',
    'after'           => 'El campo debe ser una fecha posterior a :date.',
    'after_or_equal'  => 'El campo debe ser una fecha posterior o igual a :date.',
    'alpha'           => 'El campo solo debe contener letras.',
    'alpha_dash'      => 'El campo solo debe contener letras, números, guiones y guiones bajos.',
    'alpha_num'       => 'El campo solo debe contener letras y números.',
    'array'           => 'El campo debe ser un array.',
    'ascii'           => 'El campo solo debe contener caracteres alfanuméricos y símbolos de un solo byte.',
    'before'          => 'El campo debe ser una fecha anterior a :date.',
    'before_or_equal' => 'El campo debe ser una fecha anterior o igual a :date.',

    'between' => [
        'array'   => 'El campo debe tener entre :min y :max elementos.',
        'file'    => 'El campo debe tener entre :min y :max kilobytes.',
        'numeric' => 'El campo debe estar entre :min y :max.',
        'string'  => 'El campo debe tener entre :min y :max caracteres.',
    ],

    'boolean'           => 'El campo debe ser verdadero o falso.',
    'can'               => 'El campo contiene un valor no autorizado.',
    'confirmed'         => 'La confirmación del campo no coincide.',
    'current_password'  => 'La contraseña es incorrecta.',
    'date'              => 'El campo debe ser una fecha válida.',
    'date_equals'       => 'El campo debe ser una fecha igual a :date.',
    'date_format'       => 'El campo debe coincidir con el formato :format.',
    'decimal'           => 'El campo debe tener :decimal lugares decimales.',
    'declined'          => 'El campo debe ser rechazado.',
    'declined_if'       => 'El campo debe ser rechazado cuando :other es :value.',
    'different'         => 'El campo y :other deben ser diferentes.',
    'digits'            => 'El campo debe tener :digits dígitos.',
    'digits_between'    => 'El campo debe tener entre :min y :max dígitos.',
    'dimensions'        => 'El campo tiene dimensiones de imagen no válidas.',
    'distinct'          => 'El campo tiene un valor duplicado.',
    'doesnt_end_with'   => 'El campo no debe terminar con ninguno de los siguientes: :values.',
    'doesnt_start_with' => 'El campo no debe comenzar con ninguno de los siguientes: :values.',
    'email'             => 'El campo debe ser una dirección de correo electrónico válida.',
    'ends_with'         => 'El campo debe terminar con uno de los siguientes: :values.',
    'enum'              => 'El seleccionado es inválido.',
    'exists'            => 'El seleccionado es inválido.',
    'extensions'        => 'El campo debe tener una de las siguientes extensiones: :values.',
    'file'              => 'El campo debe ser un archivo.',
    'filled'            => 'El campo debe tener un valor.',

    'gt' => [
        'array'   => 'El campo debe tener más de :value elementos.',
        'file'    => 'El campo debe ser mayor que :value kilobytes.',
        'numeric' => 'El campo debe ser mayor que :value.',
        'string'  => 'El campo debe tener más de :value caracteres.',
    ],

    'gte' => [
        'array'   => 'El campo debe tener :value elementos o más.',
        'file'    => 'El campo debe ser mayor o igual que :value kilobytes.',
        'numeric' => 'El campo debe ser mayor o igual que :value.',
        'string'  => 'El campo debe tener :value caracteres o más.',
    ],

    'hex_color' => 'El campo debe ser un color hexadecimal válido.',
    'image'     => 'El campo debe ser una imagen.',
    'in'        => 'El seleccionado es inválido.',
    'in_array'  => 'El campo debe existir en :other.',
    'integer'   => 'El campo debe ser un número entero.',
    'ip'        => 'El campo debe ser una dirección IP válida.',
    'ipv4'      => 'El campo debe ser una dirección IPv4 válida.',
    'ipv6'      => 'El campo debe ser una dirección IPv6 válida.',
    'json'      => 'El campo debe ser una cadena JSON válida.',
    'lowercase' => 'El campo debe estar en minúsculas.',

    'lt' => [
        'array'   => 'El campo debe tener menos de :value elementos.',
        'file'    => 'El campo debe ser menor que :value kilobytes.',
        'numeric' => 'El campo debe ser menor que :value.',
        'string'  => 'El campo debe tener menos de :value caracteres.',
    ],

    'lte' => [
        'array'   => 'El campo no debe tener más de :value elementos.',
        'file'    => 'El campo debe ser menor o igual que :value kilobytes.',
        'numeric' => 'El campo debe ser menor o igual que :value.',
        'string'  => 'El campo debe ser menor o igual que :value caracteres.',
    ],

    'mac_address' => 'El campo debe ser una dirección MAC válida.',

    'max' => [
        'array'   => 'El campo no debe tener más de :max elementos.',
        'file'    => 'El campo no debe ser mayor que :max kilobytes.',
        'numeric' => 'El campo no debe ser mayor que :max.',
        'string'  => 'El campo no debe ser mayor que :max caracteres.',
    ],

    'max_digits'       => 'El campo no debe tener más de :max dígitos.',
    'mimes'            => 'El campo debe ser un archivo de tipo: :values.',
    'mimetypes'        => 'El campo debe ser un archivo de tipo: :values.',

    'min' => [
        'array'   => 'El campo debe tener al menos :min elementos.',
        'file'    => 'El campo debe ser de al menos :min kilobytes.',
        'numeric' => 'El campo debe ser de al menos :min.',
        'string'  => 'El campo debe tener al menos :min caracteres.',
    ],

    'min_digits'       => 'El campo debe tener al menos :min dígitos.',
    'missing'          => 'El campo debe faltar.',
    'missing_if'       => 'El campo debe faltar cuando :other es :value.',
    'missing_unless'   => 'El campo debe faltar a menos que :other sea :value.',
    'missing_with'     => 'El campo debe faltar cuando :values está presente.',
    'missing_with_all' => 'El campo debe faltar cuando :values están presentes.',
    'multiple_of'      => 'El campo debe ser un múltiplo de :value.',
    'not_in'           => 'El seleccionado es inválido.',
    'not_regex'        => 'El formato del campo es inválido.',
    'numeric'          => 'El campo debe ser un número.',

    'password' => [
        'letters'       => 'El campo debe contener al menos una letra.',
        'mixed'         => 'El campo debe contener al menos una letra mayúscula y una minúscula.',
        'numbers'       => 'El campo debe contener al menos un número.',
        'symbols'       => 'El campo debe contener al menos un símbolo.',
        'uncompromised' => 'El dado ha aparecido en una filtración de datos. Por favor, elija un diferente.',
    ],

    'present'              => 'El campo debe estar presente.',
    'present_if'           => 'El campo debe estar presente cuando :other es :value.',
    'present_unless'       => 'El campo debe estar presente a menos que :other sea :value.',
    'present_with'         => 'El campo debe estar presente cuando :values está presente.',
    'present_with_all'     => 'El campo debe estar presente cuando :values están presentes.',
    'prohibited'           => 'El campo está prohibido.',
    'prohibited_if'        => 'El campo está prohibido cuando :other es :value.',
    'prohibited_unless'    => 'El campo está prohibido a menos que :other esté en :values.',
    'prohibits'            => 'El campo prohíbe que :other esté presente.',
    'regex'                => 'El formato del campo es inválido.',
    'required'             => 'El campo es obligatorio.',
    'required_array_keys'  => 'El campo debe contener entradas para: :values.',
    'required_if'          => 'El campo es obligatorio cuando :other es :value.',
    'required_if_accepted' => 'El campo es obligatorio cuando :other es aceptado.',
    'required_unless'      => 'El campo es obligatorio a menos que :other esté en :values.',
    'required_with'        => 'El campo es obligatorio cuando :values está presente.',
    'required_with_all'    => 'El campo es obligatorio cuando :values están presentes.',
    'required_without'     => 'El campo es obligatorio cuando :values no está presente.',
    'required_without_all' => 'El campo es obligatorio cuando ninguno de los :values está presente.',
    'same'                 => 'El campo debe coincidir con :other.',

    'size' => [
        'array'   => 'El campo debe contener :size elementos.',
        'file'    => 'El campo debe tener :size kilobytes.',
        'numeric' => 'El campo debe ser :size.',
        'string'  => 'El campo debe tener :size caracteres.',
    ],

    'starts_with' => 'El campo debe empezar con uno de los siguientes valores: :values.',
    'string'      => 'El campo debe ser una cadena de texto.',
    'timezone'    => 'El campo debe ser una zona horaria válida.',
    'unique'      => 'El ya ha sido tomado.',
    'uploaded'    => 'La carga del falló.',
    'uppercase'   => 'El campo debe estar en mayúsculas.',
    'url'         => 'El campo debe ser una URL válida.',
    'ulid'        => 'El campo debe ser un ULID válido.',
    'uuid'        => 'El campo debe ser un UUID válido.',

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
            'rule-name' => 'mensaje personalizado',
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
