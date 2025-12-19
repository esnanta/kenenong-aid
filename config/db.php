<?php

return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=localhost;dbname=kenenong-aid',
    'username' => 'root',
    'password' => 'myroot',
    'charset' => 'utf8mb4',
    'tablePrefix' => 't_',
    // Schema cache options (for production environment)
    'enableSchemaCache' => true,
    'schemaCacheDuration' => 60,
    'schemaCache' => 'cache',
];
