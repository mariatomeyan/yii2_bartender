<?php

return [
//
//     'class' => 'yii\db\Connection',
//     'dsn' => 'sqlite:@app/data/sqlite.db', // SQLite database file
//     'charset' => 'utf8',

    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=db;dbname=yii_db',
    'username' => 'yii_user',
    'password' => 'yii_password',
    'charset' => 'utf8',

    // Schema cache options (for production environment)
    //'enableSchemaCache' => true,
    //'schemaCacheDuration' => 60,
    //'schemaCache' => 'cache',
];
