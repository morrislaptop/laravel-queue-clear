<?php namespace Morrislaptop\LaravelQueueClear\Console;

use Illuminate\Console\Command;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Queue\QueueManager;
use Morrislaptop\LaravelQueueClear\Contracts\Clearer as ClearerContract;
use Symfony\Component\Console\Input\InputArgument;

class QueueClearCommand extends Command {


	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'queue:clear';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Clear all queued jobs, by deleting all pending jobs.';

	/**
	 * @var
	 */
	protected $config;

	/**
	 * @var QueueManager
	 */
	protected $queue;

	/**
	 * @var Clearer
	 */
	protected $clearer;

	/**
	 * @param Repository $config
	 * @param Clearer $clearer
	 */
	function __construct(Repository $config, ClearerContract $clearer)
	{
		$this->config = $config;
		$this->clearer = $clearer;
		parent::__construct();
	}

	/**
	 * Defines the arguments.
	 *
	 * @return array
	 */
	public function getArguments()
	{
		return array(
			array('connection', InputArgument::OPTIONAL, 'The connection of the queue driver to clear.'),
			array('queue', InputArgument::OPTIONAL, 'The name of the queue / pipe to clear.'),
		);
	}

	/**
	 * Execute the console command.
	 *
	 * @return void
	 */
	public function handle()
	{
		$connection = $this->argument('connection') ?: $this->config->get('queue.default');
		$queue = $this->argument('queue') ?: $this->config->get('queue.connections.' . $connection  . '.queue');

		$this->info(sprintf('Clearing queue "%s" on "%s"', $queue, $connection));
		$cleared = $this->clearer->clear($connection, $queue);
		$this->info(sprintf('Cleared %d jobs', $cleared));
	}

}
