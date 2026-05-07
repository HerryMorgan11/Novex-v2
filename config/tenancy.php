<?php

declare(strict_types=1);

use App\Models\Tenant as TenantModel;
use Stancl\Tenancy\Bootstrappers\CacheTenancyBootstrapper;
use Stancl\Tenancy\Bootstrappers\DatabaseTenancyBootstrapper;
use Stancl\Tenancy\Bootstrappers\FilesystemTenancyBootstrapper;
use Stancl\Tenancy\Bootstrappers\QueueTenancyBootstrapper;
use Stancl\Tenancy\Database\Models\Domain;
use Stancl\Tenancy\TenantDatabaseManagers\MySQLDatabaseManager;
use Stancl\Tenancy\TenantDatabaseManagers\PostgreSQLDatabaseManager;
use Stancl\Tenancy\TenantDatabaseManagers\SQLiteDatabaseManager;
use Stancl\Tenancy\UUIDGenerator;

return [

    /*
    |--------------------------------------------------------------------------
    | Models
    |--------------------------------------------------------------------------
    */
    'tenant_model' => TenantModel::class,

    // ✅ Si tu tenants.id lo genera el paquete, deja UUIDGenerator.
    // ✅ Si tú asignas id manual (recomendado: usar el id del paquete), también sirve.
    'id_generator' => UUIDGenerator::class,

    'domain_model' => Domain::class,

    /*
    |--------------------------------------------------------------------------
    | Central domains
    |--------------------------------------------------------------------------
    | Dominios que NO inicializan tenancy (zona central).
    | Añade tu dominio real aquí vía env.
    */
    'central_domains' => [
        env('APP_DOMAIN', 'tuapp.com'),
        '127.0.0.1',
        'localhost',
    ],

    /*
    |--------------------------------------------------------------------------
    | Bootstrappers
    |--------------------------------------------------------------------------
    */
    'bootstrappers' => [
        DatabaseTenancyBootstrapper::class,
        CacheTenancyBootstrapper::class,
        FilesystemTenancyBootstrapper::class,
        QueueTenancyBootstrapper::class,
        // Stancl\Tenancy\Bootstrappers\RedisTenancyBootstrapper::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Database tenancy
    |--------------------------------------------------------------------------
    */
    'database' => [
        // ✅ Tu conexión central (la que has mostrado en config/database.php)
        'central_connection' => env('DB_CONNECTION', 'sqlite'),

        /*
         * Connection "template" para crear la conexión tenant dinámica.
         * - null => el paquete usará la conexión central como plantilla.
         * - recomendado: null si tenants usan mismas credenciales/host.
         * - si quieres separar credenciales: crea conexión "tenant_template" y ponla aquí.
         */
        'template_tenant_connection' => env('TENANT_TEMPLATE_CONNECTION', null),

        /*
         * Si NO usas tu columna db_name, el paquete construye nombres:
         * prefix + tenant_id + suffix
         * Como tú quieres nombre por empresa, normalmente pondrás Tenant->db_name.
         * Aún así dejamos un prefix seguro.
         */
        'prefix' => env('TENANT_DB_PREFIX', 't_'),
        'suffix' => env('TENANT_DB_SUFFIX', ''),

        /*
         * Managers para crear/eliminar bases de datos
         */
        'managers' => [
            'mysql' => MySQLDatabaseManager::class,
            'sqlite' => SQLiteDatabaseManager::class,
            'pgsql' => PostgreSQLDatabaseManager::class,
            // 'pgsql' => Stancl\Tenancy\TenantDatabaseManagers\PostgreSQLSchemaManager::class,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Cache tenancy
    |--------------------------------------------------------------------------
    */
    'cache' => [
        'tag_base' => 'tenant',
    ],

    /*
    |--------------------------------------------------------------------------
    | Filesystem tenancy
    |--------------------------------------------------------------------------
    */
    'filesystem' => [
        'suffix_base' => 'tenant',
        'disks' => [
            'local',
            'public',
        ],
        'root_override' => [
            'local' => '%storage_path%/app/',
            'public' => '%storage_path%/app/public/',
        ],
        'suffix_storage_path' => true,
        'asset_helper_tenancy' => false,
    ],

    /*
    |--------------------------------------------------------------------------
    | Redis tenancy (si usas phpredis + Redis directo)
    |--------------------------------------------------------------------------
    */
    'redis' => [
        'prefix_base' => 'tenant',
        'prefixed_connections' => [
            // 'default',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Features (desactivadas por defecto)
    |--------------------------------------------------------------------------
    */
    'features' => [
        // Stancl\Tenancy\Features\TenantConfig::class,
        // Stancl\Tenancy\Features\CrossDomainRedirect::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Routes
    |--------------------------------------------------------------------------
    */
    'routes' => true,

    /*
    |--------------------------------------------------------------------------
    | tenants:migrate / tenants:seed
    |--------------------------------------------------------------------------
    */
    'migration_parameters' => [
        '--force' => true,
        '--path' => [database_path('migrations/tenant')],
        '--realpath' => true,
    ],

    'seeder_parameters' => [
        '--class' => 'DatabaseSeeder',
    ],
];
