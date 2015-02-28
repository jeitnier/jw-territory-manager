@extends('_templates.master')

@section('title')
Edit App Settings ::
@parent
@stop

@section('content')

					<header>

						<h1 class="page-header">Edit App Settings</h1>

					</header>

					<section id="user">

						<div class="row">

							{{ Form::model($settings, [
								'route'  => 'admin.settings.update',
								'method' => 'PUT',
								'role'   => 'form',
								'class'  => 'col-xs-12 col-sm-8'
							]) }}

                                <div class="form-group">
                                    <label for="admin-email">
                                        Admin Email Address
                                    </label>
                                    <input type="text" id="admin-email" class="form-control input-lg"
                                           name="admin_email"
                                           value="{{ $settings['admin_email'] }}" required>
                                </div>

                                <div class="form-group">
                                    <label for="mapbox-api-key">
                                        Mapbox API Key
                                    </label>
                                    <input type="text" id="mapbox-api-key" class="form-control input-lg"
                                           name="mapbox_api_key"
                                           value="{{ $settings['mapbox_api_key'] }}" required>
                                </div>

                                <div class="form-group">
                                    {{ Form::label('territory_due_date_months', '# Months Territory Is Due In') }}
                                    {{ Form::text('territory_due_date_months', NULL, ['class' => 'form-control input-lg', 'required']) }}
                                </div>

								<div class="form-group">
									<label for="territory-not-worked-interval-months">
										# Months Lapsed Until Territory Becomes Available For Sign Out
									</label>
									<input type="text" id="territory-not-worked-interval-months" class="form-control input-lg"
                                           name="territory_not_worked_interval_months"
                                           value="{{ $settings['territory_not_worked_interval_months'] }}" required>
								</div>

								<div class="form-group">
									<label for="territory-amount-available-house">
										# House-to-House Territories to Allow for Check-Out <em>(0 = Unlimited)</em>
									</label>
									<input type="text" id="territory-amount-available-house" class="form-control input-lg"
                                           name="territory_amount_available_house"
                                           value="{{ $settings['territory_amount_available_house'] }}" required>
								</div>

								<div class="form-group">
									<label for="territory-amount-available-business">
										# Business Territories to Allow for Check-Out <em>(0 = Unlimited)</em>
									</label>
									<input type="text" id="territory-amount-available-business"
                                           class="form-control input-lg" name="territory_amount_available_business"
                                           value="{{ $settings['territory_amount_available_business'] }}" required>
								</div>

								<div class="form-group">
									<label for="territory-amount-available-lwp">
										# Letter Writing / Phone Territories to Allow for Check-Out <em>(0 = Unlimited)</em>
									</label>
									<input type="text" id="territory-amount-available-lwp"
                                           class="form-control input-lg" name="territory_amount_available_lwp"
                                           value="{{ $settings['territory_amount_available_lwp'] }}" required>
								</div>

								<div class="form-group">
									<label for="number-territories-allowed">
                                        # of Territories a Publisher can Sign-Out Simultaneously
                                    </label>
									<input type="text" id="number-territories-allowed" class="form-control input-lg"
                                           name="number_territories_allowed"
                                           value="{{ $settings['number_territories_allowed'] }}" required>
								</div>

								<div class="form-group">
									<label for="territory-soon-days">
                                        # of Days Before Territory is Due to Show Warning in Dashboard
                                    </label>
									<input type="text" id="territory-soon-days" class="form-control input-lg"
                                           name="territory_soon_days"
                                           value="{{ $settings['territory_soon_days'] }}" required>
								</div>

                                <div class="form-group">
                                    <label>Force Page Break In Reports?</label>
                                    <br>
                                    <label class="radio-inline" style="padding-left: 0;">
                                        <input type="radio" name="reports_force_page_break" id="reports-force-page-break-true"
                                               value="true" class="input-special"
                                               required {{ $settings['reports_force_page_break'] == 'true' ? 'checked' : '' }}>
                                        <span> True</span>
                                    </label>
                                    <label class="radio-inline" style="padding-left: 0;">
                                        <input type="radio" name="reports_force_page_break" id="reports-force-page-break-false"
                                               value="false" class="input-special"
                                               required {{ $settings['reports_force_page_break'] == 'false' ? 'checked' : '' }}>
                                        <span> False</span>
                                    </label>
                                </div>

                                <div class="form-group">
                                    <label for="number-daily-dropbox-backups">
                                        # of Daily Dropbox Backups to Keep at a Time
                                    </label>
                                    <input type="text" id="number-daily-dropbox-backups" class="form-control input-lg"
                                           name="number_daily_dropbox_backups"
                                           value="{{ $settings['number_daily_dropbox_backups'] }}" required>
                                </div>

                                <div class="form-group">
                                    <label for="number-weekly-dropbox-backups">
                                        # of Weekly Dropbox Backups to Keep at a Time
                                    </label>
                                    <input type="text" id="number-weekly-dropbox-backups" class="form-control input-lg"
                                           name="number_weekly_dropbox_backups"
                                           value="{{ $settings['number_weekly_dropbox_backups'] }}" required>
                                </div>

								<div class="form-group">
									<button type="submit" class="btn btn-primary btn-lg">
										<i class="fa fa-gear"></i> Update Settings
									</button>
								</div>

							{{ Form::close() }}

						</div>

					</section>

@stop