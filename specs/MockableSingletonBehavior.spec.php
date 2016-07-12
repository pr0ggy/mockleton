<?php
/**
 * Specs for the MockableSingletonBehavior trait
 * Testing is accomplished using the simple MockableSingleton implementation defined in src/
 */

use Mockleton\Test\Implementations\MockableSingletonConstructorArgumentSpy;
use Mockleton\Test\Implementations\MockableNullableInstanceSingleton;

describe('MockableSingletonBehavior trait', function () {

    afterEach(function () {
        MockableNullableInstanceSingleton::unregisterSingletonInstance();
    });

    describe('::registerSingletonInstance($instance)', function () {

        it('should allow registering a specific instance as the singleton', function () {
            $instance = new MockableNullableInstanceSingleton();
            MockableNullableInstanceSingleton::registerSingletonInstance($instance);
            assert(MockableNullableInstanceSingleton::getInstance() === $instance);
        });

        it('should throw TypeError if instance type mismatch', function () {
            try {
                $instance = new stdClass();
                MockableNullableInstanceSingleton::registerSingletonInstance($instance);
            } catch (TypeError $e) {
                assert($e->getMessage() === 'Attempting to register a singleton instance which is not of the same type');
                return;
            }

            throw new Exception('Failed to throw exception on instance type mismatch');
        });

        it('should throw RuntimeException if an instance is already registered', function () {
            try {
                MockableNullableInstanceSingleton::registerSingletonInstance(new MockableNullableInstanceSingleton());
                MockableNullableInstanceSingleton::registerSingletonInstance(new MockableNullableInstanceSingleton());
            } catch (RuntimeException $e) {
                assert($e->getMessage() === 'Singleton instance already registered');
                return;
            }

            throw new Exception('Failed to throw exception on attempted instance multi-registration');
        });

    });


    describe('::createAndRegisterSingletonWithConstructionArgs(...$singleton_constructor_args)', function () {

        it('should throw RuntimeException if an instance is already registered', function () {
            try {
                MockableNullableInstanceSingleton::createAndRegisterSingletonWithConstructionArgs();
                MockableNullableInstanceSingleton::createAndRegisterSingletonWithConstructionArgs();
            } catch (RuntimeException $e) {
                assert($e->getMessage() === 'Singleton instance already registered');
                return;
            }

            throw new Exception('Failed to throw exception on attempted instance multi-registration');
        });

        it('should create an instance of the same type', function () {
            MockableNullableInstanceSingleton::createAndRegisterSingletonWithConstructionArgs();
            assert(MockableNullableInstanceSingleton::getInstance() instanceof MockableNullableInstanceSingleton);
        });

        it('should pass given arguments to constructor', function () {
            MockableSingletonConstructorArgumentSpy::createAndRegisterSingletonWithConstructionArgs('foo', 'bar', true);
            $instance = MockableSingletonConstructorArgumentSpy::getInstance();
            assert($instance->didReceiveConstructionArgs('foo', 'bar', true));
        });

    });


    describe('::getInstance()', function () {

        it('should throw RuntimeException if no instance registered', function () {
            try {
                $instance = MockableNullableInstanceSingleton::getInstance();
            } catch (RuntimeException $e) {
                assert($e->getMessage() === 'No singleton instance registered');
                return;
            }

            throw new Exception('Failed to throw exception when attempting to fetch a null instance');
        });

    });


    describe('::unregisterSingletonInstance()', function() {

        it('should unset the singleton instance', function() {
            MockableNullableInstanceSingleton::createAndRegisterSingletonWithConstructionArgs();
            $instance = MockableNullableInstanceSingleton::getInstance();
            assert(isset($instance));

            MockableNullableInstanceSingleton::unregisterSingletonInstance();
            try {
                $instance = MockableNullableInstanceSingleton::getInstance();
            } catch (RuntimeException $e) {
                assert($e->getMessage() === 'No singleton instance registered');
                return;
            }

            throw new Exception('Failed to unset singleton instance');
        });

    });

});
