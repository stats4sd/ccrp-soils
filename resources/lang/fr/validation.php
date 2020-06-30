<?php
return [
    'accepted'             => 'Le :attribute doit être accepté.',
    'active_url'           => ':attribute n\'est pas une URL valide.',
    'after'                => ':attribute doit être une date après :date.',
    'after_or_equal'       => ':attribute doit être une date après :date.',
    'alpha'                => ':attribute ne peut contenir que des lettres.',
    'alpha_dash'           => ':attribute ne peut contenir que des lettres, des chiffres, des tirets haut et des tirets bas.',
    'alpha_num'            => ':attribute ne peut contenir que des lettres et des chiffres.',
    'array'                => ':attribute doit être un tableau.',
    'before'               => ':attribute doit être une date antérieure à :date.',
    'before_or_equal'      => ':attribute doit être une date antérieure ou égale à :date.',
    'between'              => [
        'numeric' => 'Le :attribute doit être compris entre :min et :max.',
        'file'    => ':attribute doit faire entre :min et :max kilo-octets.',
        'string'  => ':attribute doit contenir entre :min et :max caractères.',
        'array'   => ':attribute doit avoir entre :min et :max éléments.'
    ],
    'boolean'              => 'Le champ :attribute doit être vrai ou faux.',
    'confirmed'            => 'La confirmation :attribute ne correspond pas.',
    'date'                 => 'Le :attribute n\'est pas une date valide.',
    'date_equals'          => 'Le :attribute doit être la même date que :date.',
    'date_format'          => ':attribute ne correspond pas au format :format.',
    'different'            => ':attribute et :other doivent être différents.',
    'digits'               => ':attribute doit être composé de :digits chiffres.',
    'digits_between'       => 'Le :attribute doit être compris entre les chiffres :min et :max.',
    'dimensions'           => ':attribute a des dimensions d\'image non valides.',
    'distinct'             => 'Le champ :attribute a deux fois la même valeur.',
    'email'                => ':attribute doit être une adresse électronique valide.',
    'exists'               => 'Le :attribute sélectionné n\'est pas valide.',
    'file'                 => ':attribute doit être un fichier.',
    'filled'               => 'Le champ :attribute doit avoir une valeur.',
    'gt'                   => [
        'numeric' => ':attribute doit être supérieur à :value.',
        'file'    => ':attribute doit être supérieur à :value kilo-octets.',
        'string'  => ':attribute doit être supérieur à :value caractères.',
        'array'   => ':attribute doit avoir plus de :value éléments.'
    ],
    'gte'                  => [
        'numeric' => ':attribute doit être supérieur ou égal à :value.',
        'file'    => ':attribute doit être supérieur ou égal à :value kilo-octets.',
        'string'  => ':attribute doit faire au moins :value caractères.',
        'array'   => ':attribute doit avoir au moins :value éléments.'
    ],
    'image'                => ':attribute doit être une image.',
    'in'                   => 'Le :attribute sélectionné n\'est pas valide.',
    'in_array'             => 'Le champ :attribute n\'existe pas dans :other.',
    'integer'              => ':attribute doit être un entier.',
    'ip'                   => ':attribute doit être une adresse IP valide.',
    'ipv4'                 => ':attribute doit être une adresse IPv4 valide.',
    'ipv6'                 => ':attribute doit être une adresse IPv6 valide.',
    'json'                 => ':attribute doit être une chaîne JSON valide.',
    'lt'                   => [
        'numeric' => ':attribute doit être inférieur à :value.',
        'file'    => 'Le :attribute doit faire moins de :value kilo-octets.',
        'string'  => ':attribute doit comporter moins de :value caractères.',
        'array'   => ':attribute doit avoir moins de :value éléments.'
    ],
    'lte'                  => [
        'numeric' => ':attribute doit être inférieur ou égal à :value.',
        'file'    => ':attribute doit faire moins de :value kilo-octets.',
        'string'  => ':attribute doit avoir moins de :value caractères.',
        'array'   => ':attribute ne doit pas contenir plus de :value éléments.'
    ],
    'max'                  => [
        'numeric' => 'Le :attribute ne peut pas être supérieur à :max.',
        'file'    => ':attribute ne peut pas faire plus de :max kilo-octets.',
        'string'  => ':attribute ne peut pas être supérieur à :max caractères.',
        'array'   => ':attribute ne peut contenir plus de :max éléments.'
    ],
    'mimes'                => ':attribute doit être un fichier de type: :values.',
    'mimetypes'            => ':attribute doit être un fichier de type: :values.',
    'min'                  => [
        'numeric' => ':attribute doit être au moins :min.',
        'file'    => ':attribute doit faire au moins :min kilo-octets.',
        'string'  => ':attribute doit comporter au moins :min caractères.',
        'array'   => ':attribute doit avoir au moins :min éléments.'
    ],
    'not_in'               => 'Le :attribute sélectionné n\'est pas valide.',
    'not_regex'            => 'Le format :attribute n\'est pas valide.',
    'numeric'              => ':attribute doit être un nombre.',
    'present'              => 'Le champ :attribute doit être présent.',
    'regex'                => 'Le format :attribute n\'est pas valide.',
    'required'             => 'Le champ :attribute est obligatoire.',
    'required_if'          => 'Le champ :attribute est requis lorsque :other est :value.',
    'required_unless'      => 'Le champ :attribute est obligatoire sauf si :other est inclu dans :values.',
    'required_with'        => 'Le champ :attribute est requis lorsque :values est présent.',
    'required_with_all'    => 'Le champ :attribute est requis lorsque :values est présent.',
    'required_without'     => 'Le champ :attribute est requis lorsque :values n\'est pas présent.',
    'required_without_all' => 'Le champ :attribute est obligatoire lorsqu\'aucun des éléments :values n\'est présent.',
    'same'                 => ':attribute et :other doivent correspondre.',
    'size'                 => [
        'numeric' => ':attribute doit être :size.',
        'file'    => ':attribute doit faire :size kilo-octets.',
        'string'  => ':attribute doit faire :size caractères.',
        'array'   => ':attribute doit contenir :size éléments.'
    ],
    'starts_with'          => ':attribute doit commencer par l’un des éléments suivants: :values',
    'string'               => ':attribute doit être une chaîne de caractères.',
    'timezone'             => ':attribute doit être une zone valide.',
    'unique'               => 'Le :attribute a déjà été pris.',
    'uploaded'             => ':attribute n\'a pas pu être chargé.',
    'url'                  => 'Le format :attribute n\'est pas valide.',
    'uuid'                 => ':attribute doit être un UUID valide.',
    'custom'               => [
        'attribute-name' => [
            'rule-name' => 'message personnalisé'
        ]
    ]
];