<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;

class InitializeApp extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'tm:init';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Initialize the site defaults.';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return [

		];
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		$this->info('Setup started.');

		Artisan::call('migrate', ['--package' => 'cartalyst/sentry', '--env' => App::environment(), '--force' => TRUE]);

		Artisan::call('migrate', ['--env' => App::environment(), '--force' => TRUE]);

		Artisan::call('db:seed', ['--env' => App::environment(), '--force' => TRUE]);

		$this->info('Setup completed.');
	}

}