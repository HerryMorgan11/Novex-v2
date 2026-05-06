<?php declare(strict_types = 1);

// osfsl-/Users/davidjacobocastillo/Documents/TFG/novex-v2/vendor/composer/../stancl/tenancy/src/Database/Concerns/TenantRun.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Stancl\Tenancy\Database\Concerns\TenantRun
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-0834721bf50e30eb91094dc895f0f0a1e3786120bd2042f8c6553b85b74950ca-8.4.1-6.70.0.0',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Stancl\\Tenancy\\Database\\Concerns\\TenantRun',
        'filename' => '/Users/davidjacobocastillo/Documents/TFG/novex-v2/vendor/composer/../stancl/tenancy/src/Database/Concerns/TenantRun.php',
      ),
    ),
    'namespace' => 'Stancl\\Tenancy\\Database\\Concerns',
    'name' => 'Stancl\\Tenancy\\Database\\Concerns\\TenantRun',
    'shortName' => 'TenantRun',
    'isInterface' => false,
    'isTrait' => true,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => NULL,
    'attributes' => 
    array (
    ),
    'startLine' => 9,
    'endLine' => 34,
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
            'startLine' => 18,
            'endLine' => 18,
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
        'docComment' => '/**
 * Run a callback in this tenant\'s context.
 * Atomic, safely reverts to previous context.
 *
 * @param callable $callback
 * @return mixed
 */',
        'startLine' => 18,
        'endLine' => 33,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Stancl\\Tenancy\\Database\\Concerns',
        'declaringClassName' => 'Stancl\\Tenancy\\Database\\Concerns\\TenantRun',
        'implementingClassName' => 'Stancl\\Tenancy\\Database\\Concerns\\TenantRun',
        'currentClassName' => 'Stancl\\Tenancy\\Database\\Concerns\\TenantRun',
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