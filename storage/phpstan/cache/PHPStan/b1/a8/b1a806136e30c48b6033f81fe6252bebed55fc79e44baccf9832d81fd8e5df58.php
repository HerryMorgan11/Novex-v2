<?php declare(strict_types = 1);

// osfsl-/Users/davidjacobocastillo/Documents/TFG/novex-v2/vendor/composer/../stancl/virtualcolumn/src/VirtualColumn.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Stancl\VirtualColumn\VirtualColumn
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-a1cb3857fe4a3f8011e34f2e6bb392b6828ff3c7536df05e0f86cf5c9026b201-8.4.1-6.70.0.0',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Stancl\\VirtualColumn\\VirtualColumn',
        'filename' => '/Users/davidjacobocastillo/Documents/TFG/novex-v2/vendor/composer/../stancl/virtualcolumn/src/VirtualColumn.php',
      ),
    ),
    'namespace' => 'Stancl\\VirtualColumn',
    'name' => 'Stancl\\VirtualColumn\\VirtualColumn',
    'shortName' => 'VirtualColumn',
    'isInterface' => false,
    'isTrait' => true,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * This trait lets you add a "data" column functionality to any Eloquent model.
 * It serializes attributes which don\'t exist as columns on the model\'s table
 * into a JSON column named data (customizable by overriding getDataColumn).
 *
 * @mixin \\Illuminate\\Database\\Eloquent\\Model
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 17,
    'endLine' => 201,
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
      'customEncryptedCastables' => 
      array (
        'declaringClassName' => 'Stancl\\VirtualColumn\\VirtualColumn',
        'implementingClassName' => 'Stancl\\VirtualColumn\\VirtualColumn',
        'name' => 'customEncryptedCastables',
        'modifiers' => 17,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'array',
            'isIdentifier' => true,
          ),
        ),
        'default' => 
        array (
          'code' => '[]',
          'attributes' => 
          array (
            'startLine' => 25,
            'endLine' => 25,
            'startTokenPos' => 45,
            'startFilePos' => 902,
            'endTokenPos' => 46,
            'endFilePos' => 903,
          ),
        ),
        'docComment' => '/**
 * Encrypted castables have to be handled using a special approach that prevents the data from getting encrypted repeatedly.
 *
 * The default encrypted castables (\'encrypted\', \'encrypted:array\', \'encrypted:collection\', \'encrypted:json\', \'encrypted:object\')
 * are already handled, so you can use this array to add your own encrypted castables.
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 25,
        'endLine' => 25,
        'startColumn' => 5,
        'endColumn' => 55,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'dataEncoded' => 
      array (
        'declaringClassName' => 'Stancl\\VirtualColumn\\VirtualColumn',
        'implementingClassName' => 'Stancl\\VirtualColumn\\VirtualColumn',
        'name' => 'dataEncoded',
        'modifiers' => 1,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'bool',
            'isIdentifier' => true,
          ),
        ),
        'default' => 
        array (
          'code' => 'false',
          'attributes' => 
          array (
            'startLine' => 32,
            'endLine' => 32,
            'startTokenPos' => 59,
            'startFilePos' => 1168,
            'endTokenPos' => 59,
            'endFilePos' => 1172,
          ),
        ),
        'docComment' => '/**
 * We need this property, because both created & saved event listeners
 * decode the data (to take precedence before other created & saved)
 * listeners, but we don\'t want the data to be decoded twice.
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 32,
        'endLine' => 32,
        'startColumn' => 5,
        'endColumn' => 37,
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
      'decodeVirtualColumn' => 
      array (
        'name' => 'decodeVirtualColumn',
        'parameters' => 
        array (
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
        'startLine' => 34,
        'endLine' => 60,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Stancl\\VirtualColumn',
        'declaringClassName' => 'Stancl\\VirtualColumn\\VirtualColumn',
        'implementingClassName' => 'Stancl\\VirtualColumn\\VirtualColumn',
        'currentClassName' => 'Stancl\\VirtualColumn\\VirtualColumn',
        'aliasName' => NULL,
      ),
      'encodeAttributes' => 
      array (
        'name' => 'encodeAttributes',
        'parameters' => 
        array (
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
        'startLine' => 62,
        'endLine' => 85,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Stancl\\VirtualColumn',
        'declaringClassName' => 'Stancl\\VirtualColumn\\VirtualColumn',
        'implementingClassName' => 'Stancl\\VirtualColumn\\VirtualColumn',
        'currentClassName' => 'Stancl\\VirtualColumn\\VirtualColumn',
        'aliasName' => NULL,
      ),
      'valueEncrypted' => 
      array (
        'name' => 'valueEncrypted',
        'parameters' => 
        array (
          'value' => 
          array (
            'name' => 'value',
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
            'startLine' => 87,
            'endLine' => 87,
            'startColumn' => 36,
            'endColumn' => 48,
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
            'name' => 'bool',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 87,
        'endLine' => 96,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Stancl\\VirtualColumn',
        'declaringClassName' => 'Stancl\\VirtualColumn\\VirtualColumn',
        'implementingClassName' => 'Stancl\\VirtualColumn\\VirtualColumn',
        'currentClassName' => 'Stancl\\VirtualColumn\\VirtualColumn',
        'aliasName' => NULL,
      ),
      'decodeAttributes' => 
      array (
        'name' => 'decodeAttributes',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 98,
        'endLine' => 103,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Stancl\\VirtualColumn',
        'declaringClassName' => 'Stancl\\VirtualColumn\\VirtualColumn',
        'implementingClassName' => 'Stancl\\VirtualColumn\\VirtualColumn',
        'currentClassName' => 'Stancl\\VirtualColumn\\VirtualColumn',
        'aliasName' => NULL,
      ),
      'getAfterListeners' => 
      array (
        'name' => 'getAfterListeners',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'array',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 105,
        'endLine' => 126,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Stancl\\VirtualColumn',
        'declaringClassName' => 'Stancl\\VirtualColumn\\VirtualColumn',
        'implementingClassName' => 'Stancl\\VirtualColumn\\VirtualColumn',
        'currentClassName' => 'Stancl\\VirtualColumn\\VirtualColumn',
        'aliasName' => NULL,
      ),
      'decodeIfEncoded' => 
      array (
        'name' => 'decodeIfEncoded',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 128,
        'endLine' => 133,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Stancl\\VirtualColumn',
        'declaringClassName' => 'Stancl\\VirtualColumn\\VirtualColumn',
        'implementingClassName' => 'Stancl\\VirtualColumn\\VirtualColumn',
        'currentClassName' => 'Stancl\\VirtualColumn\\VirtualColumn',
        'aliasName' => NULL,
      ),
      'fireModelEvent' => 
      array (
        'name' => 'fireModelEvent',
        'parameters' => 
        array (
          'event' => 
          array (
            'name' => 'event',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 135,
            'endLine' => 135,
            'startColumn' => 39,
            'endColumn' => 44,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'halt' => 
          array (
            'name' => 'halt',
            'default' => 
            array (
              'code' => 'true',
              'attributes' => 
              array (
                'startLine' => 135,
                'endLine' => 135,
                'startTokenPos' => 664,
                'startFilePos' => 4062,
                'endTokenPos' => 664,
                'endFilePos' => 4065,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 135,
            'endLine' => 135,
            'startColumn' => 47,
            'endColumn' => 58,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 135,
        'endLine' => 144,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Stancl\\VirtualColumn',
        'declaringClassName' => 'Stancl\\VirtualColumn\\VirtualColumn',
        'implementingClassName' => 'Stancl\\VirtualColumn\\VirtualColumn',
        'currentClassName' => 'Stancl\\VirtualColumn\\VirtualColumn',
        'aliasName' => NULL,
      ),
      'runAfterListeners' => 
      array (
        'name' => 'runAfterListeners',
        'parameters' => 
        array (
          'event' => 
          array (
            'name' => 'event',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 146,
            'endLine' => 146,
            'startColumn' => 39,
            'endColumn' => 44,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'halt' => 
          array (
            'name' => 'halt',
            'default' => 
            array (
              'code' => 'true',
              'attributes' => 
              array (
                'startLine' => 146,
                'endLine' => 146,
                'startTokenPos' => 722,
                'startFilePos' => 4302,
                'endTokenPos' => 722,
                'endFilePos' => 4305,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 146,
            'endLine' => 146,
            'startColumn' => 47,
            'endColumn' => 58,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 146,
        'endLine' => 164,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Stancl\\VirtualColumn',
        'declaringClassName' => 'Stancl\\VirtualColumn\\VirtualColumn',
        'implementingClassName' => 'Stancl\\VirtualColumn\\VirtualColumn',
        'currentClassName' => 'Stancl\\VirtualColumn\\VirtualColumn',
        'aliasName' => NULL,
      ),
      'getCasts' => 
      array (
        'name' => 'getCasts',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 166,
        'endLine' => 171,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Stancl\\VirtualColumn',
        'declaringClassName' => 'Stancl\\VirtualColumn\\VirtualColumn',
        'implementingClassName' => 'Stancl\\VirtualColumn\\VirtualColumn',
        'currentClassName' => 'Stancl\\VirtualColumn\\VirtualColumn',
        'aliasName' => NULL,
      ),
      'getDataColumn' => 
      array (
        'name' => 'getDataColumn',
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
        'docComment' => '/**
 * Get the name of the column that stores additional data.
 */',
        'startLine' => 176,
        'endLine' => 179,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Stancl\\VirtualColumn',
        'declaringClassName' => 'Stancl\\VirtualColumn\\VirtualColumn',
        'implementingClassName' => 'Stancl\\VirtualColumn\\VirtualColumn',
        'currentClassName' => 'Stancl\\VirtualColumn\\VirtualColumn',
        'aliasName' => NULL,
      ),
      'getCustomColumns' => 
      array (
        'name' => 'getCustomColumns',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'array',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 181,
        'endLine' => 186,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Stancl\\VirtualColumn',
        'declaringClassName' => 'Stancl\\VirtualColumn\\VirtualColumn',
        'implementingClassName' => 'Stancl\\VirtualColumn\\VirtualColumn',
        'currentClassName' => 'Stancl\\VirtualColumn\\VirtualColumn',
        'aliasName' => NULL,
      ),
      'getColumnForQuery' => 
      array (
        'name' => 'getColumnForQuery',
        'parameters' => 
        array (
          'column' => 
          array (
            'name' => 'column',
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
            'startLine' => 193,
            'endLine' => 193,
            'startColumn' => 39,
            'endColumn' => 52,
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
            'name' => 'string',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get a column name for an attribute that can be used in SQL queries.
 *
 * (`foo` or `data->foo` depending on whether `foo` is in custom columns)
 */',
        'startLine' => 193,
        'endLine' => 200,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Stancl\\VirtualColumn',
        'declaringClassName' => 'Stancl\\VirtualColumn\\VirtualColumn',
        'implementingClassName' => 'Stancl\\VirtualColumn\\VirtualColumn',
        'currentClassName' => 'Stancl\\VirtualColumn\\VirtualColumn',
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