<?php declare(strict_types = 1);

// osfsl-/Users/davidjacobocastillo/Documents/TFG/novex-v2/app/Actions/Tenancy/CreateTenantAction.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Actions\Tenancy\CreateTenantAction
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-80b9b175b09391fa0b088a43a1b439c9328627ded524e8dc81e7931826147733-8.4.1-6.70.0.0',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Actions\\Tenancy\\CreateTenantAction',
        'filename' => '/Users/davidjacobocastillo/Documents/TFG/novex-v2/app/Actions/Tenancy/CreateTenantAction.php',
      ),
    ),
    'namespace' => 'App\\Actions\\Tenancy',
    'name' => 'App\\Actions\\Tenancy\\CreateTenantAction',
    'shortName' => 'CreateTenantAction',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Crea una nueva empresa (tenant) para un usuario y provisiona su base de datos
 * en modo síncrono.
 *
 * Responsabilidades:
 *  1. Persistencia transaccional de Tenant + TenantMembership + asignación al usuario.
 *  2. Provisioning síncrono: CreateDatabase + MigrateDatabase.
 *  3. Marcar el tenant como \'active\' sólo si el provisioning ha sido correcto.
 *
 * Se usa `withoutEvents` al crear el Tenant para evitar que el pipeline queued
 * de Stancl Tenancy se dispare en paralelo y duplique el provisioning.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 27,
    'endLine' => 83,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => NULL,
    'implementsClassNames' => 
    array (
    ),
    'traitClassNames' => 
    array (
    ),
    'immediateConstants' => 
    array (
    ),
    'immediateProperties' => 
    array (
    ),
    'immediateMethods' => 
    array (
      'execute' => 
      array (
        'name' => 'execute',
        'parameters' => 
        array (
          'user' => 
          array (
            'name' => 'user',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'App\\Models\\User',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 34,
            'endLine' => 34,
            'startColumn' => 29,
            'endColumn' => 38,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'data' => 
          array (
            'name' => 'data',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'array',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 34,
            'endLine' => 34,
            'startColumn' => 41,
            'endColumn' => 51,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'App\\Models\\Tenant',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @param  array{company_name:string, industry:string, country:string}  $data
 *
 * @throws RuntimeException si la migración de la BD del tenant falla.
 */',
        'startLine' => 34,
        'endLine' => 64,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Actions\\Tenancy',
        'declaringClassName' => 'App\\Actions\\Tenancy\\CreateTenantAction',
        'implementingClassName' => 'App\\Actions\\Tenancy\\CreateTenantAction',
        'currentClassName' => 'App\\Actions\\Tenancy\\CreateTenantAction',
        'aliasName' => NULL,
      ),
      'provisionDatabase' => 
      array (
        'name' => 'provisionDatabase',
        'parameters' => 
        array (
          'tenant' => 
          array (
            'name' => 'tenant',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'App\\Models\\Tenant',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 66,
            'endLine' => 66,
            'startColumn' => 40,
            'endColumn' => 53,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'void',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 66,
        'endLine' => 82,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => true,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'App\\Actions\\Tenancy',
        'declaringClassName' => 'App\\Actions\\Tenancy\\CreateTenantAction',
        'implementingClassName' => 'App\\Actions\\Tenancy\\CreateTenantAction',
        'currentClassName' => 'App\\Actions\\Tenancy\\CreateTenantAction',
        'aliasName' => NULL,
      ),
    ),
    'traitsData' => 
    array (
      'aliases' => 
      array (
      ),
      'modifiers' => 
      array (
      ),
      'precedences' => 
      array (
      ),
      'hashes' => 
      array (
      ),
    ),
  ),
));