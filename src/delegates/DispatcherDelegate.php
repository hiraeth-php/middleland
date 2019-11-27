<?php

namespace Hiraeth\Middleland;

use Hiraeth;
use Middleland\Dispatcher;
use Psr\Http\Message\ServerRequestInterface as ServerRequest;
use Psr\Http\Message\ResponseInterface as Response;

/**
 * {@inheritDoc}
 */
class DispatcherDelegate implements Hiraeth\Delegate
{
	/**
	 * {@inheritDoc}
	 */
	static public function getClass(): string
	{
		return Dispatcher::class;
	}


	/**
	 * {@inheritDoc}
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
