<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;

class DatabaseBackup extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'db:backup';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Backup a MySQL database and store it to Dropbox.';

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
		return array(
			array('frequency', NULL, InputOption::VALUE_REQUIRED, 'Frequency of when to run backup.')
		);
	}

	/**
	 * Execute the console command.
	 * Crontab runs every day at midnight & weekly at midnight on Sunday.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		$frequency = $this->option('frequency');

		$database        = $_ENV['DB_DATABASE'];
		$username        = $_ENV['DB_USERNAME'];
		$password        = $_ENV['DB_PASSWORD'];
		$backup_path     = storage_path() . '/dbdumps/';
		$backup_filename = $database . '-' . date('Y-m-d-H.i.s') . '-' . $frequency . '.sql.gz';

		// check amount of daily backups that already exist
		if ('daily' == $frequency)
		{
			// get all daily backups from Dropbox
			$listings = Dropbox::searchFileNames('/', 'daily');

			// get daily limit value from db settings
			$limit = (int) \Options::get('number_daily_dropbox_backups');
		}
		elseif ('weekly' == $frequency)
		{
			// get all weekly backups from Dropbox
			$listings = Dropbox::searchFileNames('/', 'weekly');

			// get daily limit value from db settings
			$limit = (int) \Options::get('number_weekly_dropbox_backups');
		}

		// delete old backups
		$this->deleteBackup($listings, $limit);

		// initiate the dump
		$this->info('Database Backup Started.');

		$command = 'mysqldump -u ' . $username . ' -p' . $password . ' ' . $database . ' | gzip > ' . $backup_path . $backup_filename;
		system($command);

		$this->info('Backup File Created At: ' . $backup_path . $backup_filename);
		$this->info('Database Backup Completed.');

		// move the dump to Dropbox
		$this->info('Moving dump to Dropbox.');

		$dump = fopen($backup_path . $backup_filename, 'rb');
		Dropbox::uploadFile('/' . $backup_filename, Dropbox\WriteMode::add(), $dump);

		$this->info('Dump moved to Dropbox successfully.');

		// cleanup
		$this->info('Running cleanup.');

		File::delete($backup_path . $backup_filename);

		$this->info('Cleanup completed.');
	}

	/**
	 * Delete all backups.
	 *
	 * @param $listings
	 * @param $limit
	 */
	private function deleteBackup($listings, $limit)
	{
		// if # of backups > limit
		if (count($listings) > $limit)
		{
			$this->info('Purging oldest backup(s).');

			// pluck just the path strings out of the array & sort them oldest first
			$listings = array_pluck($listings, 'path');
			asort($listings);

			// iterate over backups deleting the oldest first until the limit is met
			$x = count($listings);
			foreach ($listings AS $listing)
			{
				if ($x > $limit)
				{
					Dropbox::delete($listing);
				}

				$x--;
			}
		}
	}

}