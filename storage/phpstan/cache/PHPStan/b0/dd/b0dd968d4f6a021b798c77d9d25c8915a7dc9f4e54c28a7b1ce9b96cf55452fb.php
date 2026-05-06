<?php declare(strict_types = 1);

// osfsl-/Users/davidjacobocastillo/Documents/TFG/novex-v2/vendor/composer/../stancl/tenancy/src/Contracts/Tenant.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Stancl\Tenancy\Contracts\Tenant
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-741263637113d30c4869b8a6e7de10e16eb1ba752c9b7a2a02b39e847af9323e-8.4.1-6.70.0.0',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Stancl\\Tenancy\\Contracts\\Tenant',
        'filename' => '/Users/davidjacobocastillo/Documents/TFG/novex-v2/vendor/composer/../stancl/tenancy/src/Contracts/Tenant.php',
      ),
    ),
    'namespace' => 'Stancl\\Tenancy\\Contracts',
    'name' => 'Stancl\\Tenancy\\Contracts\\Tenant',
    'shortName' => 'Tenant',
    'isInterface' => true,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * @see \\Stancl\\Tenancy\\Database\\Models\\Tenant
 *
 * @method __call(string $method, array $parameters) IDE support. This will be a model.
 * @method static __callStatic(string $method, array $parameters) IDE support. This will be a model.
 * @mixin \\Illuminate\\Database\\Eloquent\\Model
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 14,
    'endLine' => 30,
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
        'docComment' => '/** Get the name of the key used for identifying the tenant. */',
        'startLine' => 17,
        'endLine' => 17,
        'startColumn' => 5,
        'endColumn' => 47,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Stancl\\Tenancy\\Contracts',
        'declaringClassName' => 'Stancl\\Tenancy\\Contracts\\Tenant',
        'implementingClassName' => 'Stancl\\Tenancy\\Contracts\\Tenant',
        'currentClassName' => 'Stancl\\Tenancy\\Contracts\\Tenant',
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
        'docComment' => '/** Get the value of the key used for identifying the tenant. */',
        'startLine' => 20,
        'endLine' => 20,
        'startColumn' => 5,
        'endColumn' => 35,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Stancl\\Tenancy\\Contracts',
        'declaringClassName' => 'Stancl\\Tenancy\\Contracts\\Tenant',
        'implementingClassName' => 'Stancl\\Tenancy\\Contracts\\Tenant',
        'currentClassName' => 'Stancl\\Tenancy\\Contracts\\Tenant',
        'aliasName' => NULL,
      ),
      'getInternal' => 
      array (
        'name' => 'getInternal',
        'parameters' => 
        array (
          'key' => 
          array (
            'name' => 'key',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'string',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 23,
            'endLine' => 23,
            'startColumn' => 33,
            'endColumn' => 43,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/** Get the value of an internal key. */',
        'startLine' => 23,
        'endLine' => 23,
        'startColumn' => 5,
        'endColumn' => 45,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Stancl\\Tenancy\\Contracts',
        'declaringClassName' => 'Stancl\\Tenancy\\Contracts\\Tenant',
        'implementingClassName' => 'Stancl\\Tenancy\\Contracts\\Tenant',
        'currentClassName' => 'Stancl\\Tenancy\\Contracts\\Tenant',
        'aliasName' => NULL,
      ),
      'setInternal' => 
      array (
        'name' => 'setInternal',
        'parameters' => 
        array (
          'key' => 
          array (
            'name' => 'key',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'string',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 26,
            'endLine' => 26,
            'startColumn' => 33,
            'endColumn' => 43,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'value' => 
          array (
            'name' => 'value',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 26,
            'endLine' => 26,
            'startColumn' => 46,
            'endColumn' => 51,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/** Set the value of an internal key. */',
        'startLine' => 26,
        'endLine' => 26,
        'startColumn' => 5,
        'endColumn' => 53,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Stancl\\Tenancy\\Contracts',
        'declaringClassName' => 'Stancl\\Tenancy\\Contracts\\Tenant',
        'implementingClassName' => 'Stancl\\Tenancy\\Contracts\\Tenant',
        'currentClassName' => 'Stancl\\Tenancy\\Contracts\\Tenant',
        'aliasName' => NULL,
      ),
      'run' => 
      array (
        'name' => 'run',
        'parameters' => 
        array (
          'callback' => 
          array (
            'name' => 'callback',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'callable',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 29,
            'endLine' => 29,
            'startColumn' => 25,
            'endColumn' => 42,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/** Run a callback in this tenant\'s environment. */',
        'startLine' => 29,
        'endLine' => 29,
        'startColumn' => 5,
        'endColumn' => 44,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Stancl\\Tenancy\\Contracts',
        'declaringClassName' => 'Stancl\\Tenancy\\Contracts\\Tenant',
        'implementingClassName' => 'Stancl\\Tenancy\\Contracts\\Tenant',
        'currentClassName' => 'Stancl\\Tenancy\\Contracts\\Tenant',
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