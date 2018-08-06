<?php

/**
 * Retornando configurações pré-definidas para as bases de dados
 */
return [
    /**
     * Opções (mysql, sqlite)
     */
    'driver' => 'mysql',
    'sqlite' => ['database' => 'homolog_ourbrazil.db'
    ],
    'mysql' => ['host' => 'localhost',
                'database' => 'homolog_ourbrazil',
                'user' => 'root',
                'pass' => '',
                'charset' => 'utf8',
                'collation' => 'utf8_unicode_ci'
    ],
    'mysqlProducao' => ['host' => 'host',
                'database' => 'nomeBanco',
                'user' => 'usuario',
                'pass' => 'senha',
                'charset' => 'utf8',
                'collation' => 'utf8_unicode_ci'
    ]
];
