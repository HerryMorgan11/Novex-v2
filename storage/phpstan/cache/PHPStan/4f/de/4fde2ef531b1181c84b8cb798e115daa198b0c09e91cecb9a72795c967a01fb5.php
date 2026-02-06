<?php declare(strict_types = 1);

// osfsl-/Users/davidjacobocastillo/Documents/TFG/novex-v2/vendor/composer/../laravel/framework/src/Illuminate/Validation/Rules/Password.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Illuminate\Validation\Rules\Password
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-259afda8920246f958c7d070c20cec299e926e40e0725d7faaaf0308525f877c-8.4.1-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Illuminate\\Validation\\Rules\\Password',
        'filename' => '/Users/davidjacobocastillo/Documents/TFG/novex-v2/vendor/composer/../laravel/framework/src/Illuminate/Validation/Rules/Password.php',
      ),
    ),
    'namespace' => 'Illuminate\\Validation\\Rules',
    'name' => 'Illuminate\\Validation\\Rules\\Password',
    'shortName' => 'Password',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => NULL,
    'attributes' => 
    array (
    ),
    'startLine' => 19,
    'endLine' => 448,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => NULL,
    'implementsClassNames' => 
    array (
      0 => 'Illuminate\\Contracts\\Validation\\DataAwareRule',
      1 => 'Illuminate\\Contracts\\Validation\\ImplicitRule',
      2 => 'IteratorAggregate',
      3 => 'Illuminate\\Contracts\\Validation\\Rule',
      4 => 'Illuminate\\Contracts\\Validation\\ValidatorAwareRule',
    ),
    'traitClassNames' => 
    array (
      0 => 'Illuminate\\Support\\Traits\\Conditionable',
    ),
    'immediateConstants' => 
    array (
    ),
    'immediateProperties' => 
    array (
      'validator' => 
      array (
        'declaringClassName' => 'Illuminate\\Validation\\Rules\\Password',
        'implementingClassName' => 'Illuminate\\Validation\\Rules\\Password',
        'name' => 'validator',
        'modifiers' => 2,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The validator performing the validation.
 *
 * @var \\Illuminate\\Contracts\\Validation\\Validator
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 28,
        'endLine' => 28,
        'startColumn' => 5,
        'endColumn' => 25,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'data' => 
      array (
        'declaringClassName' => 'Illuminate\\Validation\\Rules\\Password',
        'implementingClassName' => 'Illuminate\\Validation\\Rules\\Password',
        'name' => 'data',
        'modifiers' => 2,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The data under validation.
 *
 * @var array
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 35,
        'endLine' => 35,
        'startColumn' => 5,
        'endColumn' => 20,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'min' => 
      array (
        'declaringClassName' => 'Illuminate\\Validation\\Rules\\Password',
        'implementingClassName' => 'Illuminate\\Validation\\Rules\\Password',
        'name' => 'min',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '8',
          'attributes' => 
          array (
            'startLine' => 42,
            'endLine' => 42,
            'startTokenPos' => 121,
            'startFilePos' => 1022,
            'endTokenPos' => 121,
            'endFilePos' => 1022,
          ),
        ),
        'docComment' => '/**
 * The minimum size of the password.
 *
 * @var int
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 42,
        'endLine' => 42,
        'startColumn' => 5,
        'endColumn' => 23,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'max' => 
      array (
        'declaringClassName' => 'Illuminate\\Validation\\Rules\\Password',
        'implementingClassName' => 'Illuminate\\Validation\\Rules\\Password',
        'name' => 'max',
        'modifiers' => 2,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The maximum size of the password.
 *
 * @var int
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 49,
        'endLine' => 49,
        'startColumn' => 5,
        'endColumn' => 19,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'required' => 
      array (
        'declaringClassName' => 'Illuminate\\Validation\\Rules\\Password',
        'implementingClassName' => 'Illuminate\\Validation\\Rules\\Password',
        'name' => 'required',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => 'false',
          'attributes' => 
          array (
            'startLine' => 56,
            'endLine' => 56,
            'startTokenPos' => 139,
            'startFilePos' => 1229,
            'endTokenPos' => 139,
            'endFilePos' => 1233,
          ),
        ),
        'docComment' => '/**
 * If the password is required.
 *
 * @var bool
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 56,
        'endLine' => 56,
        'startColumn' => 5,
        'endColumn' => 32,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'sometimes' => 
      array (
        'declaringClassName' => 'Illuminate\\Validation\\Rules\\Password',
        'implementingClassName' => 'Illuminate\\Validation\\Rules\\Password',
        'name' => 'sometimes',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => 'false',
          'attributes' => 
          array (
            'startLine' => 63,
            'endLine' => 63,
            'startTokenPos' => 150,
            'startFilePos' => 1366,
            'endTokenPos' => 150,
            'endFilePos' => 1370,
          ),
        ),
        'docComment' => '/**
 * If the password should only be validated when present.
 *
 * @var bool
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 63,
        'endLine' => 63,
        'startColumn' => 5,
        'endColumn' => 33,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'mixedCase' => 
      array (
        'declaringClassName' => 'Illuminate\\Validation\\Rules\\Password',
        'implementingClassName' => 'Illuminate\\Validation\\Rules\\Password',
        'name' => 'mixedCase',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => 'false',
          'attributes' => 
          array (
            'startLine' => 70,
            'endLine' => 70,
            'startTokenPos' => 161,
            'startFilePos' => 1522,
            'endTokenPos' => 161,
            'endFilePos' => 1526,
          ),
        ),
        'docComment' => '/**
 * If the password requires at least one uppercase and one lowercase letter.
 *
 * @var bool
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 70,
        'endLine' => 70,
        'startColumn' => 5,
        'endColumn' => 33,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'letters' => 
      array (
        'declaringClassName' => 'Illuminate\\Validation\\Rules\\Password',
        'implementingClassName' => 'Illuminate\\Validation\\Rules\\Password',
        'name' => 'letters',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => 'false',
          'attributes' => 
          array (
            'startLine' => 77,
            'endLine' => 77,
            'startTokenPos' => 172,
            'startFilePos' => 1648,
            'endTokenPos' => 172,
            'endFilePos' => 1652,
          ),
        ),
        'docComment' => '/**
 * If the password requires at least one letter.
 *
 * @var bool
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 77,
        'endLine' => 77,
        'startColumn' => 5,
        'endColumn' => 31,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'numbers' => 
      array (
        'declaringClassName' => 'Illuminate\\Validation\\Rules\\Password',
        'implementingClassName' => 'Illuminate\\Validation\\Rules\\Password',
        'name' => 'numbers',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => 'false',
          'attributes' => 
          array (
            'startLine' => 84,
            'endLine' => 84,
            'startTokenPos' => 183,
            'startFilePos' => 1774,
            'endTokenPos' => 183,
            'endFilePos' => 1778,
          ),
        ),
        'docComment' => '/**
 * If the password requires at least one number.
 *
 * @var bool
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 84,
        'endLine' => 84,
        'startColumn' => 5,
        'endColumn' => 31,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'symbols' => 
      array (
        'declaringClassName' => 'Illuminate\\Validation\\Rules\\Password',
        'implementingClassName' => 'Illuminate\\Validation\\Rules\\Password',
        'name' => 'symbols',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => 'false',
          'attributes' => 
          array (
            'startLine' => 91,
            'endLine' => 91,
            'startTokenPos' => 194,
            'startFilePos' => 1900,
            'endTokenPos' => 194,
            'endFilePos' => 1904,
          ),
        ),
        'docComment' => '/**
 * If the password requires at least one symbol.
 *
 * @var bool
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 91,
        'endLine' => 91,
        'startColumn' => 5,
        'endColumn' => 31,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'uncompromised' => 
      array (
        'declaringClassName' => 'Illuminate\\Validation\\Rules\\Password',
        'implementingClassName' => 'Illuminate\\Validation\\Rules\\Password',
        'name' => 'uncompromised',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => 'false',
          'attributes' => 
          array (
            'startLine' => 98,
            'endLine' => 98,
            'startTokenPos' => 205,
            'startFilePos' => 2050,
            'endTokenPos' => 205,
            'endFilePos' => 2054,
          ),
        ),
        'docComment' => '/**
 * If the password should not have been compromised in data leaks.
 *
 * @var bool
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 98,
        'endLine' => 98,
        'startColumn' => 5,
        'endColumn' => 37,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'compromisedThreshold' => 
      array (
        'declaringClassName' => 'Illuminate\\Validation\\Rules\\Password',
        'implementingClassName' => 'Illuminate\\Validation\\Rules\\Password',
        'name' => 'compromisedThreshold',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '0',
          'attributes' => 
          array (
            'startLine' => 105,
            'endLine' => 105,
            'startTokenPos' => 216,
            'startFilePos' => 2235,
            'endTokenPos' => 216,
            'endFilePos' => 2235,
          ),
        ),
        'docComment' => '/**
 * The number of times a password can appear in data leaks before being considered compromised.
 *
 * @var int
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 105,
        'endLine' => 105,
        'startColumn' => 5,
        'endColumn' => 40,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'customRules' => 
      array (
        'declaringClassName' => 'Illuminate\\Validation\\Rules\\Password',
        'implementingClassName' => 'Illuminate\\Validation\\Rules\\Password',
        'name' => 'customRules',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[]',
          'attributes' => 
          array (
            'startLine' => 112,
            'endLine' => 112,
            'startTokenPos' => 227,
            'startFilePos' => 2408,
            'endTokenPos' => 228,
            'endFilePos' => 2409,
          ),
        ),
        'docComment' => '/**
 * Additional validation rules that should be merged into the default rules during validation.
 *
 * @var array
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 112,
        'endLine' => 112,
        'startColumn' => 5,
        'endColumn' => 32,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'messages' => 
      array (
        'declaringClassName' => 'Illuminate\\Validation\\Rules\\Password',
        'implementingClassName' => 'Illuminate\\Validation\\Rules\\Password',
        'name' => 'messages',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[]',
          'attributes' => 
          array (
            'startLine' => 119,
            'endLine' => 119,
            'startTokenPos' => 239,
            'startFilePos' => 2517,
            'endTokenPos' => 240,
            'endFilePos' => 2518,
          ),
        ),
        'docComment' => '/**
 * The failure messages, if any.
 *
 * @var array
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 119,
        'endLine' => 119,
        'startColumn' => 5,
        'endColumn' => 29,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'defaultCallback' => 
      array (
        'declaringClassName' => 'Illuminate\\Validation\\Rules\\Password',
        'implementingClassName' => 'Illuminate\\Validation\\Rules\\Password',
        'name' => 'defaultCallback',
        'modifiers' => 17,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The callback that will generate the "default" version of the password rule.
 *
 * @var string|array|callable|null
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 126,
        'endLine' => 126,
        'startColumn' => 5,
        'endColumn' => 35,
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
      '__construct' => 
      array (
        'name' => '__construct',
        'parameters' => 
        array (
          'min' => 
          array (
            'name' => 'min',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 133,
            'endLine' => 133,
            'startColumn' => 33,
            'endColumn' => 36,
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
 * Create a new rule instance.
 *
 * @param  int  $min
 */',
        'startLine' => 133,
        'endLine' => 136,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Validation\\Rules',
        'declaringClassName' => 'Illuminate\\Validation\\Rules\\Password',
        'implementingClassName' => 'Illuminate\\Validation\\Rules\\Password',
        'currentClassName' => 'Illuminate\\Validation\\Rules\\Password',
        'aliasName' => NULL,
      ),
      'defaults' => 
      array (
        'name' => 'defaults',
        'parameters' => 
        array (
          'callback' => 
          array (
            'name' => 'callback',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 146,
                'endLine' => 146,
                'startTokenPos' => 298,
                'startFilePos' => 3211,
                'endTokenPos' => 298,
                'endFilePos' => 3214,
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
            'startColumn' => 37,
            'endColumn' => 52,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Set the default callback to be used for determining a password\'s default rules.
 *
 * If no arguments are passed, the default password rule configuration will be returned.
 *
 * @param  static|callable|null  $callback
 * @return static|void
 */',
        'startLine' => 146,
        'endLine' => 157,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Illuminate\\Validation\\Rules',
        'declaringClassName' => 'Illuminate\\Validation\\Rules\\Password',
        'implementingClassName' => 'Illuminate\\Validation\\Rules\\Password',
        'currentClassName' => 'Illuminate\\Validation\\Rules\\Password',
        'aliasName' => NULL,
      ),
      'default' => 
      array (
        'name' => 'default',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the default configuration of the password rule.
 *
 * @return static
 */',
        'startLine' => 164,
        'endLine' => 171,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Illuminate\\Validation\\Rules',
        'declaringClassName' => 'Illuminate\\Validation\\Rules\\Password',
        'implementingClassName' => 'Illuminate\\Validation\\Rules\\Password',
        'currentClassName' => 'Illuminate\\Validation\\Rules\\Password',
        'aliasName' => NULL,
      ),
      'required' => 
      array (
        'name' => 'required',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the default configuration of the password rule and mark the field as required.
 *
 * @return static
 */',
        'startLine' => 178,
        'endLine' => 185,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Illuminate\\Validation\\Rules',
        'declaringClassName' => 'Illuminate\\Validation\\Rules\\Password',
        'implementingClassName' => 'Illuminate\\Validation\\Rules\\Password',
        'currentClassName' => 'Illuminate\\Validation\\Rules\\Password',
        'aliasName' => NULL,
      ),
      'sometimes' => 
      array (
        'name' => 'sometimes',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the default configuration of the password rule and mark the field as sometimes being required.
 *
 * @return static
 */',
        'startLine' => 192,
        'endLine' => 199,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Illuminate\\Validation\\Rules',
        'declaringClassName' => 'Illuminate\\Validation\\Rules\\Password',
        'implementingClassName' => 'Illuminate\\Validation\\Rules\\Password',
        'currentClassName' => 'Illuminate\\Validation\\Rules\\Password',
        'aliasName' => NULL,
      ),
      'setValidator' => 
      array (
        'name' => 'setValidator',
        'parameters' => 
        array (
          'validator' => 
          array (
            'name' => 'validator',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 207,
            'endLine' => 207,
            'startColumn' => 34,
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
        'docComment' => '/**
 * Set the performing validator.
 *
 * @param  \\Illuminate\\Contracts\\Validation\\Validator  $validator
 * @return $this
 */',
        'startLine' => 207,
        'endLine' => 212,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Validation\\Rules',
        'declaringClassName' => 'Illuminate\\Validation\\Rules\\Password',
        'implementingClassName' => 'Illuminate\\Validation\\Rules\\Password',
        'currentClassName' => 'Illuminate\\Validation\\Rules\\Password',
        'aliasName' => NULL,
      ),
      'setData' => 
      array (
        'name' => 'setData',
        'parameters' => 
        array (
          'data' => 
          array (
            'name' => 'data',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 220,
            'endLine' => 220,
            'startColumn' => 29,
            'endColumn' => 33,
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
 * Set the data under validation.
 *
 * @param  array  $data
 * @return $this
 */',
        'startLine' => 220,
        'endLine' => 225,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Validation\\Rules',
        'declaringClassName' => 'Illuminate\\Validation\\Rules\\Password',
        'implementingClassName' => 'Illuminate\\Validation\\Rules\\Password',
        'currentClassName' => 'Illuminate\\Validation\\Rules\\Password',
        'aliasName' => NULL,
      ),
      'min' => 
      array (
        'name' => 'min',
        'parameters' => 
        array (
          'size' => 
          array (
            'name' => 'size',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 233,
            'endLine' => 233,
            'startColumn' => 32,
            'endColumn' => 36,
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
 * Set the minimum size of the password.
 *
 * @param  int  $size
 * @return $this
 */',
        'startLine' => 233,
        'endLine' => 236,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Illuminate\\Validation\\Rules',
        'declaringClassName' => 'Illuminate\\Validation\\Rules\\Password',
        'implementingClassName' => 'Illuminate\\Validation\\Rules\\Password',
        'currentClassName' => 'Illuminate\\Validation\\Rules\\Password',
        'aliasName' => NULL,
      ),
      'max' => 
      array (
        'name' => 'max',
        'parameters' => 
        array (
          'size' => 
          array (
            'name' => 'size',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 244,
            'endLine' => 244,
            'startColumn' => 25,
            'endColumn' => 29,
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
 * Set the maximum size of the password.
 *
 * @param  int  $size
 * @return $this
 */',
        'startLine' => 244,
        'endLine' => 249,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Validation\\Rules',
        'declaringClassName' => 'Illuminate\\Validation\\Rules\\Password',
        'implementingClassName' => 'Illuminate\\Validation\\Rules\\Password',
        'currentClassName' => 'Illuminate\\Validation\\Rules\\Password',
        'aliasName' => NULL,
      ),
      'uncompromised' => 
      array (
        'name' => 'uncompromised',
        'parameters' => 
        array (
          'threshold' => 
          array (
            'name' => 'threshold',
            'default' => 
            array (
              'code' => '0',
              'attributes' => 
              array (
                'startLine' => 257,
                'endLine' => 257,
                'startTokenPos' => 648,
                'startFilePos' => 5621,
                'endTokenPos' => 648,
                'endFilePos' => 5621,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 257,
            'endLine' => 257,
            'startColumn' => 35,
            'endColumn' => 48,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Ensures the password has not been compromised in data leaks.
 *
 * @param  int  $threshold
 * @return $this
 */',
        'startLine' => 257,
        'endLine' => 264,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Validation\\Rules',
        'declaringClassName' => 'Illuminate\\Validation\\Rules\\Password',
        'implementingClassName' => 'Illuminate\\Validation\\Rules\\Password',
        'currentClassName' => 'Illuminate\\Validation\\Rules\\Password',
        'aliasName' => NULL,
      ),
      'mixedCase' => 
      array (
        'name' => 'mixedCase',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Makes the password require at least one uppercase and one lowercase letter.
 *
 * @return $this
 */',
        'startLine' => 271,
        'endLine' => 276,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Validation\\Rules',
        'declaringClassName' => 'Illuminate\\Validation\\Rules\\Password',
        'implementingClassName' => 'Illuminate\\Validation\\Rules\\Password',
        'currentClassName' => 'Illuminate\\Validation\\Rules\\Password',
        'aliasName' => NULL,
      ),
      'letters' => 
      array (
        'name' => 'letters',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Makes the password require at least one letter.
 *
 * @return $this
 */',
        'startLine' => 283,
        'endLine' => 288,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Validation\\Rules',
        'declaringClassName' => 'Illuminate\\Validation\\Rules\\Password',
        'implementingClassName' => 'Illuminate\\Validation\\Rules\\Password',
        'currentClassName' => 'Illuminate\\Validation\\Rules\\Password',
        'aliasName' => NULL,
      ),
      'numbers' => 
      array (
        'name' => 'numbers',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Makes the password require at least one number.
 *
 * @return $this
 */',
        'startLine' => 295,
        'endLine' => 300,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Validation\\Rules',
        'declaringClassName' => 'Illuminate\\Validation\\Rules\\Password',
        'implementingClassName' => 'Illuminate\\Validation\\Rules\\Password',
        'currentClassName' => 'Illuminate\\Validation\\Rules\\Password',
        'aliasName' => NULL,
      ),
      'symbols' => 
      array (
        'name' => 'symbols',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Makes the password require at least one symbol.
 *
 * @return $this
 */',
        'startLine' => 307,
        'endLine' => 312,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Validation\\Rules',
        'declaringClassName' => 'Illuminate\\Validation\\Rules\\Password',
        'implementingClassName' => 'Illuminate\\Validation\\Rules\\Password',
        'currentClassName' => 'Illuminate\\Validation\\Rules\\Password',
        'aliasName' => NULL,
      ),
      'rules' => 
      array (
        'name' => 'rules',
        'parameters' => 
        array (
          'rules' => 
          array (
            'name' => 'rules',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 320,
            'endLine' => 320,
            'startColumn' => 27,
            'endColumn' => 32,
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
 * Specify additional validation rules that should be merged with the default rules during validation.
 *
 * @param  \\Closure|string|array  $rules
 * @return $this
 */',
        'startLine' => 320,
        'endLine' => 325,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Validation\\Rules',
        'declaringClassName' => 'Illuminate\\Validation\\Rules\\Password',
        'implementingClassName' => 'Illuminate\\Validation\\Rules\\Password',
        'currentClassName' => 'Illuminate\\Validation\\Rules\\Password',
        'aliasName' => NULL,
      ),
      'passes' => 
      array (
        'name' => 'passes',
        'parameters' => 
        array (
          'attribute' => 
          array (
            'name' => 'attribute',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 334,
            'endLine' => 334,
            'startColumn' => 28,
            'endColumn' => 37,
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
            'startLine' => 334,
            'endLine' => 334,
            'startColumn' => 40,
            'endColumn' => 45,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Determine if the validation rule passes.
 *
 * @param  string  $attribute
 * @param  mixed  $value
 * @return bool
 */',
        'startLine' => 334,
        'endLine' => 387,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Validation\\Rules',
        'declaringClassName' => 'Illuminate\\Validation\\Rules\\Password',
        'implementingClassName' => 'Illuminate\\Validation\\Rules\\Password',
        'currentClassName' => 'Illuminate\\Validation\\Rules\\Password',
        'aliasName' => NULL,
      ),
      'message' => 
      array (
        'name' => 'message',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the validation error message.
 *
 * @return array
 */',
        'startLine' => 394,
        'endLine' => 397,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Validation\\Rules',
        'declaringClassName' => 'Illuminate\\Validation\\Rules\\Password',
        'implementingClassName' => 'Illuminate\\Validation\\Rules\\Password',
        'currentClassName' => 'Illuminate\\Validation\\Rules\\Password',
        'aliasName' => NULL,
      ),
      'fail' => 
      array (
        'name' => 'fail',
        'parameters' => 
        array (
          'messages' => 
          array (
            'name' => 'messages',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 405,
            'endLine' => 405,
            'startColumn' => 29,
            'endColumn' => 37,
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
 * Adds the given failures, and return false.
 *
 * @param  array|string  $messages
 * @return bool
 */',
        'startLine' => 405,
        'endLine' => 410,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Validation\\Rules',
        'declaringClassName' => 'Illuminate\\Validation\\Rules\\Password',
        'implementingClassName' => 'Illuminate\\Validation\\Rules\\Password',
        'currentClassName' => 'Illuminate\\Validation\\Rules\\Password',
        'aliasName' => NULL,
      ),
      'appliedRules' => 
      array (
        'name' => 'appliedRules',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get information about the current state of the password validation rules.
 *
 * @return array
 */',
        'startLine' => 417,
        'endLine' => 430,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Validation\\Rules',
        'declaringClassName' => 'Illuminate\\Validation\\Rules\\Password',
        'implementingClassName' => 'Illuminate\\Validation\\Rules\\Password',
        'currentClassName' => 'Illuminate\\Validation\\Rules\\Password',
        'aliasName' => NULL,
      ),
      'getIterator' => 
      array (
        'name' => 'getIterator',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Traversable',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get an iterator for the password validation rules.
 *
 * @return \\ArrayIterator<TKey, TValue>
 */',
        'startLine' => 437,
        'endLine' => 447,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Validation\\Rules',
        'declaringClassName' => 'Illuminate\\Validation\\Rules\\Password',
        'implementingClassName' => 'Illuminate\\Validation\\Rules\\Password',
        'currentClassName' => 'Illuminate\\Validation\\Rules\\Password',
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