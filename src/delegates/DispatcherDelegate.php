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
	 * Default configuration for a middleware
	 *
	 * @var array
	 */
	static $defaultConfig = [
		'class'    => NULL,
		'disabled' => FALSE,
		'priority' => 50
	];


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
		$middleware = array();
		$configs    = $app->getConfig('*', 'middleware', static::$defaultConfig);

		usort($configs, function($a, $b) {
			return $a['priority'] - $b['priority'];
		});

		foreach ($configs as $config) {
			if (!$config['class'] || $config['disabled']) {
				continue;
			}

			$middleware[] = $config['class'];
		}

		return new Dispatcher($middleware, $app);
	}
}
