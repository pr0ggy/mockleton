<?php

namespace Mockleton\Test\Implementations;

use Mockleton\MockableSingletonBehavior;

/**
 * Simple testing implementation which allows the singleton instance to
 * be unset
 *
 * @package  Mockleton\Test\Implementations;
 */
class MockableNullableInstanceSingleton
{
    use MockableSingletonBehavior;

    public static function unsetInstance()
    {
        self::$instance = null;
    }
}
