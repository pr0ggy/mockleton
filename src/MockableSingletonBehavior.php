<?php

namespace Mockleton;

/**
 * Trait which defines singleton behavior which allows an instance to be created and registered as
 * the singleton instance.  Also offers a pure private-constructor singleton approach which allows
 * constructor arguments to be passed in.
 *
 * @package  Mockleton
 */
trait MockableSingletonBehavior
{
    /**
     * @var mixed
     */
    protected static $instance;

    /**
     * Registers a specific instance of the class to use as the singleton instance
     *
     * @param  static $instance  the instance to register as the singleton instance
     * @throws \TypeError if the given instance argument does not match the type of the context object
     * @throws \RuntimeException if an instance has already been registered
     */
    public static function registerSingletonInstance($instance)
    {
        self::verifyInstanceNotYetRegistered();
        self::verifyInstanceType($instance);
        self::$instance = $instance;
    }

    /**
     * @param  mixed $instance the instance to verify as being the same type as the context object
     * @throws \TypeError if the given instance argument does not match the type of the context object
     */
    protected static function verifyInstanceType($instance)
    {
        if (($instance instanceof static) === false) {
            throw new \TypeError('Attempting to register a singleton instance which is not of the same type');
        }
    }

    /**
     * Allows for the purist implementation where even the singleton constructor is kept private, but
     * allows for construction parameters to be passed to the singleton instance to be created.
     *
     * @param  variadic $singleton_constructor_args arguments to be passed to the created instance constructor
     * @throws \RuntimeException if an instance has already been registered
     */
    public static function createAndRegisterSingletonWithConstructionArgs(...$singleton_constructor_args)
    {
        self::verifyInstanceNotYetRegistered();
        self::$instance = new static(...$singleton_constructor_args);
    }

    /**
     * @throws \RuntimeException if an instance has already been registered
     */
    protected static function verifyInstanceNotYetRegistered()
    {
        if (isset(self::$instance)) {
            throw new \RuntimeException('Singleton instance already registered');
        }
    }

    /**
     * @return mixed the singleton instance
     */
    public static function getInstance()
    {
        self::verifyInstanceHasBeenRegistered();
        return self::$instance;
    }

    /**
     * @throws \RuntimeException if a singleton instance has not been registered
     */
    protected static function verifyInstanceHasBeenRegistered()
    {
        if (isset(self::$instance) === false) {
            throw new \RuntimeException('No singleton instance registered');
        }
    }

    /**
     * Unsets the singleton instance
     */
    public function unregisterSingletonInstance()
    {
        self::$instance = null;
    }

    /*
     * Protect instance creation methods.  Note that __construct() is purposefully ommitted as it
     * may need to be public to allow creation of an instance to register outside of the singleton
     * itself.  It is up to the user of the trait whether it's constructor should be made private
     * or kept public and simply rely on a call to
     *     self::verifyInstanceNotYetRegistered()
     * to verify no singleton instance already exists.
     */

    private function __clone()
    {
    }

    private function __wakeup()
    {
    }
}
