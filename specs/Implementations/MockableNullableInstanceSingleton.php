<?php

namespace Singlemock\Test\Implementations;

use Singlemock\MockableSingletonBehavior;

/**
 * Simple testing implementation which allows the singleton instance to
 * be unset
 *
 * @package  Singlemock\Test\Implementations;
 */
class MockableNullableInstanceSingleton
{
    use MockableSingletonBehavior;

    public static function unsetInstance()
    {
        self::$instance = null;
    }
}
