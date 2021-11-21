<?php

use App\Domain\Entity\ExtendedTypecast;
use Cycle\Database;
use Cycle\ORM\Mapper\Mapper;
use Cycle\ORM\Parser\Typecast;
use Cycle\ORM\SchemaInterface as Schema;
use Cycle\ORM\Select\Source;

return [
    'schema' => [
        'sync' => env('DB_SCHEMA_SYNC', false),

        'cache' => [
            'storage' => env('DB_SCHEMA_CACHE_DRIVER', 'file'),
            'enabled' => (bool) env('DB_SCHEMA_CACHE', false),
        ],

        /*
        |--------------------------------------------------------------------------
        | Migration Repository Table
        |--------------------------------------------------------------------------
        |
        | This table keeps track of all the migrations that have already run for
        | your application. Using this information, we can determine which of
        | the migrations on disk haven't actually been run in the database.
        |
        */
        'migrations' => [
            'directory' => database_path('cycle/migrations'),
            'table' => env('DB_MIGRATIONS_TABLE', 'migrations'),
        ],

        'defaults' => [
            Schema::MAPPER => Mapper::class,
            Schema::REPOSITORY => \Infrastructure\CycleOrm\Repository::class,
            Schema::SOURCE => Source::class,
            Schema::SCOPE => null,
            Schema::TYPECAST_HANDLER => [
                Typecast::class,
                ExtendedTypecast::class,
            ],
        ],

        'tokenizer' => [
            'directories' => [
                base_path('app/Modules'),
                base_path('app/Application'),
                base_path('app/Infrastructure'),
            ],
            'exclude' => ['vendor', 'tests'],
        ],
    ],

    'database' => [

        'logger' => env('DB_LOGGER', 'null'),

        /*
        |--------------------------------------------------------------------------
        | Default Database Connection Name
        |--------------------------------------------------------------------------
        |
        | Here you may specify which of the database connections below you wish
        | to use as your default connection for all database work. Of course
        | you may use many connections at once using the Database library.
        |
        */
        'default' => 'default',

        'databases' => [
            'default' => [
                'driver' => env('DB_CONNECTION', 'sqlite'),
            ],
        ],

        /*
        |--------------------------------------------------------------------------
        | Database Connections
        |--------------------------------------------------------------------------
        |
        | Here are each of the database connections setup for your application.
        | Of course, examples of configuring each database platform that is
        | supported by Laravel is shown below to make development simple.
        |
        |
        | All database work in Laravel is done through the PHP PDO facilities
        | so make sure you have the driver for your particular database of
        | choice installed on your machine before you begin development.
        |
        */
        'connections' => [
            'sqlite' => new Database\Config\SQLiteDriverConfig(
                connection: new Database\Config\SQLite\FileConnectionConfig(
                    database: env('DB_DATABASE', database_path('database.sqlite'))
                ),
                queryCache: true,
            ),
            'mysql' => new Database\Config\MySQLDriverConfig(
                connection: new Database\Config\MySQL\TcpConnectionConfig(
                    database: env('DB_DATABASE', 'forge'),
                    host: env('DB_HOST', '127.0.0.1'),
                    port: env('DB_PORT', '3306'),
                    user: env('DB_USERNAME', 'forge'),
                    password: env('DB_PASSWORD', ''),
                ),
                queryCache: true
            ),
            'pgsql' => new Database\Config\PostgresDriverConfig(
                connection: new Database\Config\Postgres\TcpConnectionConfig(
                    database: env('DB_DATABASE', 'forge'),
                    host: env('DB_HOST', '127.0.0.1'),
                    port: env('DB_PORT', '5432'),
                    user: env('DB_USERNAME', 'forge'),
                    password: env('DB_PASSWORD', ''),
                ),
                schema: 'public',
                queryCache: true,
            ),
        ],
    ],
];
