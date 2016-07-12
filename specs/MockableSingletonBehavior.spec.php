<?php
/**
 * Specs for the MockableSingletonBehavior trait
 * Testing is accomplished using the simple MockableSingleton implementation defined in src/
 */

use Singlemock\Test\Implementations\MockableSingletonConstructorArgumentSpy;
use Singlemock\Test\Implementations\MockableNullableInstanceSingleton;

describe('MockableSingletonBehavior trait', function() {

    afterEach(function() {
        MockableNullableInstanceSingleton::unsetInstance();
    });

    describe('::registerSingletonInstance($instance)', function() {

        it('should allow registering a specific instance as the singleton', function() {
            $instance = new MockableNullableInstanceSingleton();
            MockableNullableInstanceSingleton::registerSingletonInstance($instance);
            assert(MockableNullableInstanceSingleton::getInstance() === $instance);
        });

        it('should throw RuntimeException if instance type mismatch', function() {
            try {
                $instance = new stdClass();
                MockableNullableInstanceSingleton::registerSingletonInstance($instance);
                throw new Exception('Failed to throw exception on instance type mismatch');
            } catch (RuntimeException $e) {
                assert($e->getMessage() === 'Attempting to register a singleton instance which is not of the same type');
            }
        });

        it('should throw RuntimeException if an instance is already registered', function() {
            try {
                MockableNullableInstanceSingleton::registerSingletonInstance(new MockableNullableInstanceSingleton());
                MockableNullableInstanceSingleton::registerSingletonInstance(new MockableNullableInstanceSingleton());
                throw new Exception('Failed to throw exception on attempted instance multi-registration');
            } catch (RuntimeException $e) {
                assert($e->getMessage() === 'Singleton instance already registered');
            }
        });

    });


    describe('::registerDefaultSingleton(...$singleton_constructor_args)', function() {

        it('should throw RuntimeException if an instance is already registered', function() {
            try {
                MockableNullableInstanceSingleton::registerDefaultSingleton();
                MockableNullableInstanceSingleton::registerDefaultSingleton();
                throw new Exception('Failed to throw exception on attempted instance multi-registration');
            } catch (RuntimeException $e) {
                assert($e->getMessage() === 'Singleton instance already registered');
            }
        });

        it('should create an instance of the same type', function() {
            MockableNullableInstanceSingleton::registerDefaultSingleton();
            assert(MockableNullableInstanceSingleton::getInstance() instanceof MockableNullableInstanceSingleton);
        });

        it('should pass given arguments to constructor', function() {
            MockableSingletonConstructorArgumentSpy::registerDefaultSingleton('foo', 'bar', true);
            $instance = MockableSingletonConstructorArgumentSpy::getInstance();
            assert($instance->didReceiveConstructionArgs('foo', 'bar', true));
        });

    });


    describe('::getInstance()', function() {

        it('should throw RuntimeException if no instance registered', function() {
            try {
                $instance = MockableNullableInstanceSingleton::getInstance();
                throw new Exception('Failed to throw exception when attempting to fetch a null instance');
            } catch (RuntimeException $e) {
                assert($e->getMessage() === 'Singleton instance not yet registered');
            }
        });

    });

});
