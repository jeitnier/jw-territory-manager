<?php

use Illuminate\Support\MessageBag;

class ReportsController extends BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return mixed
	 */
	public function index()
	{
		$publishers = Sentry::getUserProvider()
							->createModel()
							->join('users_groups', 'users_groups.user_id', '=', 'users.id')
							->where('users_groups.group_id', '=', 2)
							->orderBy('users.last_name', 'ASC')
							->orderBy('users.first_name', 'ASC')
							->get();

		return View::make('admin.reports.index')->withPublishers($publishers);
	}

	/**
	 * Generate the selected report(s).
	 *
	 * @return mixed
	 */
	public function generate()
	{
		$errors = new MessageBag;

		$preg = '=</?a(\s[^>]*)?>=ims';

		if (Request::isMethod('POST'))
		{
			if (Input::has('reports'))
			{
				try
				{
					$html = '';
					$x    = 0;

					foreach (Input::get('reports') AS $report => $value)
					{
						if (is_array($value))
						{
							if (FALSE !== $key = array_search('multiselect-all', $value))
							{
								unset($value[$key]);
							}

							$y = 0;
							foreach ($value AS $r)
							{
								$data = array(
									'id'   => $r,
									'show' => FALSE
								);

								$html .= preg_replace($preg, '', View::make('admin.reports.' . $report, $data)->render());

								// if config is set to force each report to a new page
								if ((bool) \Options::get('reports_force_page_break'))
								{
									if (count($value) > 1 AND $y < count($value) - 1)
									{
										$html .= '<div style="page-break-after: always;"></div>';
									}
								}

								$y++;
							}
						}
						else
						{
							$html .= preg_replace($preg, '', View::make('admin.reports.' . $report)->withShow(FALSE)->render());
						}

						// if config is set to force each report to a new page
						if ((bool) \Options::get('reports_force_page_break'))
						{
							if (count(Input::get('reports')) > 1 AND $x < count(Input::get('reports')) - 1)
							{
								$html .= '<div style="page-break-after: always;"></div>';
							}

							$x++;
						}
					}

					$report = View::make('admin.reports._templates.download')->withHtml($html)->render();

					return PDF::loadHTML($report)->download('territory_manager_reports');
				}
				catch (Exception $e)
				{
					$errors->add('message', Lang::get('reports.messages.download_error'));
					return Redirect::back()->withInput(Input::all())->withErrors($errors);
				}
			}

			$errors->add('message', Lang::get('reports.messages.none_selected'));
			return Redirect::back()->withInput(Input::all())->withErrors($errors);
		}
	}

	/**
	 * Display the specified resource.
	 *
	 * @param $report
	 * @return mixed
	 */
	public function show($report)
	{
		return View::make('admin.reports.show')->withReport($report);
	}

}