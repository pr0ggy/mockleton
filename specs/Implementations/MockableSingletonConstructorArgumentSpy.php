<?php

namespace Singlemock\Test\Implementations;

use Singlemock\MockableSingletonBehavior;

/**
 * Testing double which allows inspection of arguments passed to the constructor
 *
 * @package  Singlemock\Test\Implementations
 */
class MockableSingletonConstructorArgumentSpy
{
    use MockableSingletonBehavior;

    private $received_constructor_args = [];

    /**
     * @param variadic $args
     */
    protected function __construct(...$args)
    {
        $this->received_constructor_args = $args;
    }

    /**
     * @param  variadic $args
     * @return boolean
     */
    public function didReceiveConstructionArgs(...$args)
    {
        return ($args == $this->received_constructor_args);
    }
}
