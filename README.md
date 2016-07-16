#Mockleton: A mockable singleton behavior trait

[The Singleton design pattern](https://en.wikipedia.org/wiki/Singleton_pattern) has its uses, but is often leveraged in client code in ways that make unit testing difficult.  Consider an ad-hoc DB querying example:
```php
function executeQuery($query, $parameters)
{
	$connection = DBConnectionPool::getInstance()->getConnection();
	$statement_handle = $connection->executeQuery($query, $parameters)
	// ...

	return $result_rows;
}
```

How can we verify that the example function above fetches a connection from the connection pool, and verify that the expected methods are called on the returned connection object?  With a traditional Singleton pattern using purely internal instantiation, testing is much more of a headache as there's no way to swap out the **`DBConnectionPool`** instance for a testing double.

This trait was created to alleviate this issue by exposing a few new methods that achieve the Singleton goal of restricting instantiation to a single instance while allowing the instance to be swapped out for a testing double.  Consider this simple example using the `describe-it` syntax of testing frameworks such as [Peridot](http://peridot-php.github.io/) and [pho](https://github.com/danielstjules/pho):
```php
describe('executeQuery($query, $parameters)', function () {
	$this->original_pool_instance = DBConnectionPool::getInstance();
	$this->connection_pool_double = createConnectionPoolTestDouble();

	function createConnectionPoolTestDouble()
	{
		// ...
	}

	beforeEach(function () {
		DBConnectionPool::unregisterSingletonInstance();
		DBConnectionPool::registerSingletonInstance(
			$this->connection_pool_double
		);
	});

	afterEach(function () {
		DBConnectionPool::unregisterSingletonInstance();
		DBConnectionPool::registerSingletonInstance(
			$this->original_pool_instance;
		);
	});

	it('should ask the DBConnectionPool singleton for a connection',
		function () {
			$this->connection_pool_double
				->shouldReceive('getConnection')
				->once();
	
			$some_query = 'SELECT * FROM SOME_TABLE';
			$some_parameters = [];
			executeQuery($some_query, $some_parameters);
		}
	);

	it('should...', function () {
		// ...
	});

	// ...
});
```

See the full interface in `src/MockableSingletonBehavior.php`
