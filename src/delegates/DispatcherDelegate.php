<?php

namespace Hiraeth\Middleland;

use Hiraeth;
use Middleland\Dispatcher;

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
		return new Dispatcher($app->get(Hiraeth\Middleware\Manager::class)->getAll());
	}
}
