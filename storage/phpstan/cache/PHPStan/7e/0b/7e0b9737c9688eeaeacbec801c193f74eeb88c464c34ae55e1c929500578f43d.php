<?php declare(strict_types = 1);

// osfsl-/Users/davidjacobocastillo/Documents/TFG/novex-v2/vendor/composer/../stancl/tenancy/src/Database/Models/Tenant.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Stancl\Tenancy\Database\Models\Tenant
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-9ae958cf54da46e769dfd29226091134e273d79f2a974166e8e88fb77f8929c3-8.4.1-6.70.0.0',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Stancl\\Tenancy\\Database\\Models\\Tenant',
        'filename' => '/Users/davidjacobocastillo/Documents/TFG/novex-v2/vendor/composer/../stancl/tenancy/src/Database/Models/Tenant.php',
      ),
    ),
    'namespace' => 'Stancl\\Tenancy\\Database\\Models',
    'name' => 'Stancl\\Tenancy\\Database\\Models\\Tenant',
    'shortName' => 'Tenant',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * @property string|int $id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property array $data
 *
 * @method static TenantCollection all($columns = [\'*\'])
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 22,
    'endLine' => 62,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
    'implementsClassNames' => 
    array (
      0 => 'Stancl\\Tenancy\\Contracts\\Tenant',
    ),
    'traitClassNames' => 
    array (
      0 => 'Stancl\\Tenancy\\Database\\Concerns\\CentralConnection',
      1 => 'Stancl\\Tenancy\\Database\\Concerns\\GeneratesIds',
      2 => 'Stancl\\Tenancy\\Database\\Concerns\\HasDataColumn',
      3 => 'Stancl\\Tenancy\\Database\\Concerns\\HasInternalKeys',
      4 => 'Stancl\\Tenancy\\Database\\Concerns\\TenantRun',
      5 => 'Stancl\\Tenancy\\Database\\Concerns\\InvalidatesResolverCache',
    ),
    'immediateConstants' => 
    array (
    ),
    'immediateProperties' => 
    array (
      'modelsShouldPreventAccessingMissingAttributes' => 
      array (
        'declaringClassName' => 'Stancl\\Tenancy\\Database\\Models\\Tenant',
        'implementingClassName' => 'Stancl\\Tenancy\\Database\\Models\\Tenant',
        'name' => 'modelsShouldPreventAccessingMissingAttributes',
        'modifiers' => 18,
        'type' => NULL,
        'default' => 
        array (
          'code' => 'false',
          'attributes' => 
          array (
            'startLine' => 31,
            'endLine' => 31,
            'startTokenPos' => 89,
            'startFilePos' => 794,
            'endTokenPos' => 89,
            'endFilePos' => 798,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 31,
        'endLine' => 31,
        'startColumn' => 5,
        'endColumn' => 76,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'table' => 
      array (
        'declaringClassName' => 'Stancl\\Tenancy\\Database\\Models\\Tenant',
        'implementingClassName' => 'Stancl\\Tenancy\\Database\\Models\\Tenant',
        'name' => 'table',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\'tenants\'',
          'attributes' => 
          array (
            'startLine' => 33,
            'endLine' => 33,
            'startTokenPos' => 98,
            'startFilePos' => 825,
            'endTokenPos' => 98,
            'endFilePos' => 833,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 33,
        'endLine' => 33,
        'startColumn' => 5,
        'endColumn' => 33,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'primaryKey' => 
      array (
        'declaringClassName' => 'Stancl\\Tenancy\\Database\\Models\\Tenant',
        'implementingClassName' => 'Stancl\\Tenancy\\Database\\Models\\Tenant',
        'name' => 'primaryKey',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\'id\'',
          'attributes' => 
          array (
            'startLine' => 34,
            'endLine' => 34,
            'startTokenPos' => 107,
            'startFilePos' => 864,
            'endTokenPos' => 107,
            'endFilePos' => 867,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 34,
        'endLine' => 34,
        'startColumn' => 5,
        'endColumn' => 33,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'guarded' => 
      array (
        'declaringClassName' => 'Stancl\\Tenancy\\Database\\Models\\Tenant',
        'implementingClassName' => 'Stancl\\Tenancy\\Database\\Models\\Tenant',
        'name' => 'guarded',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[]',
          'attributes' => 
          array (
            'startLine' => 35,
            'endLine' => 35,
            'startTokenPos' => 116,
            'startFilePos' => 895,
            'endTokenPos' => 117,
            'endFilePos' => 896,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 35,
        'endLine' => 35,
        'startColumn' => 5,
        'endColumn' => 28,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'dispatchesEvents' => 
      array (
        'declaringClassName' => 'Stancl\\Tenancy\\Database\\Models\\Tenant',
        'implementingClassName' => 'Stancl\\Tenancy\\Database\\Models\\Tenant',
        'name' => 'dispatchesEvents',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'saving\' => \\Stancl\\Tenancy\\Events\\SavingTenant::class, \'saved\' => \\Stancl\\Tenancy\\Events\\TenantSaved::class, \'creating\' => \\Stancl\\Tenancy\\Events\\CreatingTenant::class, \'created\' => \\Stancl\\Tenancy\\Events\\TenantCreated::class, \'updating\' => \\Stancl\\Tenancy\\Events\\UpdatingTenant::class, \'updated\' => \\Stancl\\Tenancy\\Events\\TenantUpdated::class, \'deleting\' => \\Stancl\\Tenancy\\Events\\DeletingTenant::class, \'deleted\' => \\Stancl\\Tenancy\\Events\\TenantDeleted::class]',
          'attributes' => 
          array (
            'startLine' => 52,
            'endLine' => 61,
            'startTokenPos' => 205,
            'startFilePos' => 1257,
            'endTokenPos' => 279,
            'endFilePos' => 1663,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 52,
        'endLine' => 61,
        'startColumn' => 5,
        'endColumn' => 6,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
    ),
    'immediateMethods' => 
    array (
      'getTenantKeyName' => 
      array (
        'name' => 'getTenantKeyName',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'string',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 37,
        'endLine' => 40,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Stancl\\Tenancy\\Database\\Models',
        'declaringClassName' => 'Stancl\\Tenancy\\Database\\Models\\Tenant',
        'implementingClassName' => 'Stancl\\Tenancy\\Database\\Models\\Tenant',
        'currentClassName' => 'Stancl\\Tenancy\\Database\\Models\\Tenant',
        'aliasName' => NULL,
      ),
      'getTenantKey' => 
      array (
        'name' => 'getTenantKey',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 42,
        'endLine' => 45,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Stancl\\Tenancy\\Database\\Models',
        'declaringClassName' => 'Stancl\\Tenancy\\Database\\Models\\Tenant',
        'implementingClassName' => 'Stancl\\Tenancy\\Database\\Models\\Tenant',
        'currentClassName' => 'Stancl\\Tenancy\\Database\\Models\\Tenant',
        'aliasName' => NULL,
      ),
      'newCollection' => 
      array (
        'name' => 'newCollection',
        'parameters' => 
        array (
          'models' => 
          array (
            'name' => 'models',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 47,
                'endLine' => 47,
                'startTokenPos' => 178,
                'startFilePos' => 1142,
                'endTokenPos' => 179,
                'endFilePos' => 1143,
              ),
            ),
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
            'startLine' => 47,
            'endLine' => 47,
            'startColumn' => 35,
            'endColumn' => 52,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Stancl\\Tenancy\\Database\\TenantCollection',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 47,
        'endLine' => 50,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Stancl\\Tenancy\\Database\\Models',
        'declaringClassName' => 'Stancl\\Tenancy\\Database\\Models\\Tenant',
        'implementingClassName' => 'Stancl\\Tenancy\\Database\\Models\\Tenant',
        'currentClassName' => 'Stancl\\Tenancy\\Database\\Models\\Tenant',
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