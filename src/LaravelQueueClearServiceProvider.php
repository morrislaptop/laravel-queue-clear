<?php namespace Morrislaptop\LaravelQueueClear;

use Illuminate\Support\ServiceProvider;

class LaravelQueueClearServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app->bind(
			'Morrislaptop\LaravelQueueClear\Contracts\Clearer',
			'Morrislaptop\LaravelQueueClear\Clearer'
		);
		$this->commands('Morrislaptop\LaravelQueueClear\Console\QueueClearCommand');
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return [];
	}

}
