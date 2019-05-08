<?php

namespace Hiraeth\Middleland;

use Hiraeth;
use Middleland\Dispatcher;
use Psr\Http\Message\ServerRequestInterface as ServerRequest;
use Psr\Http\Message\ResponseInterface as Response;

/**
 *
 */
class DispatcherDelegate implements Hiraeth\Delegate
{
	/**
	 * Get the class for which the delegate operates.
	 *
	 * @static
	 * @access public
	 * @return string The class for which the delegate operates
	 */
	static public function getClass(): string
	{
		return Dispatcher::class;
	}


	/**
	 * Get the instance of the class for which the delegate operates.
	 *
	 * @access public
	 * @param Hiraeth\Application $app The application instance for which the delegate operates
	 * @return object The instance of the class for which the delegate operates
	 */
	public function __invoke(Hiraeth\Application $app): object
	{
		$configs    = $app->getConfig('*', 'middleware', []);
		$middleware = array();

		usort($configs, function($a, $b) {
			$a_priority = $a['priority'] ?? 50;
			$b_priority = $b['priority'] ?? 50;

			return $a_priority - $b_priority;
		});

		foreach ($configs as $config) {
			if (!$config || $config['disabled'] ?? FALSE) {
				continue;
			}

			$middleware[] = $config['class'];
		}

		return new Dispatcher($middleware, $app);
	}
}
