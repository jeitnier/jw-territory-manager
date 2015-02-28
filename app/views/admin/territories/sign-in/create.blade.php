@extends('_templates.master')

@section('title')
Sign In Territory ::
@parent
@stop

@section('css')
@parent
{{ HTML::style('assets/css/bootstrap-datetimepicker.css') }}
@stop

@section('content')

					<header>

						<h1 class="page-header">Sign In Territory</h1>

					</header>

					<section id="territories">

						@if (count($publishers))

						<div class="row">

							{{ Form::open(array(
								'route' => 'admin.territories.sign-in',
								'role'  => 'form'
							)) }}

								<input type="hidden" id="territory-id" name="territory_id">

								<div class="col-xs-12 col-sm-12">

									<div class="form-group">
										<label for="publisher-id">Publisher Name <em>*</em></label>
										<select id="publisher-id" class="form-control input-lg" name="publisher_id" autofocus>
											@foreach ($publishers AS $key => $publisher)
											<option value="{{ $key }}">{{ $publisher }}</option>
											@endforeach
										</select>
									</div>

                                    <div class="form-group">

                                        <div class="well">

                                            <h4>Territories Signed Out</h4>

                                            <p>(Select One to Sign In)</p>

                                            <ul class="territories-signed-out nav nav-pills nav-stacked"></ul>

                                            <div class="tab-content"></div>

                                        </div>

                                    </div>

									<div class="form-group">
										<div class="input-group-lg date" id="datepicker">
											<label for="sign-in-date">Sign-In Date <em>*</em></label>
											<input type="text" id="sign-in-date" class="form-control input-lg" name="sign_in_date"
											       required>
										</div>
									</div>

									<div id="campaign-type" class="form-group">
										<label>Campaign?</label>
										<div class="clearfix"></div>

										<div class="row indent-left">
											<div class="col-xs-12">
												<label>
													<input type="radio" id="campaign-memorial" class="input-special"
													name="campaign" value="memorial">
													<span> Memorial</span>
												</label>
												<br>
												<label>
													<input type="radio" id="campaign-convention" class="input-special"
													name="campaign" value="convention">
													<span> Convention</span>
												</label>
												<br>
												<label>
													<input type="radio" id="other" class="input-special" name="campaign" value="other">
													<span> Other</span>
												</label>
												<input type="text" id="campaign-other" class="form-control input-lg hidden"
												name="campaign_other">
											</div>
										</div>
									</div>

									<div class="form-group">
										<label for="notes">Notes (Do Not Calls, Unworkable, etc.)</label>
										<textarea id="notes" class="form-control input-lg" name="notes" rows="10"></textarea>
									</div>

									<div class="form-group">
										<button type="submit" class="btn btn-primary btn-lg">
											<i class="fa fa-chevron-circle-right"></i> Sign In
										</button>
									</div>

								</div>

							{{ Form::close() }}

						</div>

						@else

						<h3>@lang('territories.messages.sign_in_list_empty')</h3>

						@endif

					</section>

@stop

@if (count($publishers))
@section('js')
@parent
{{ HTML::script('assets/js/moment.min.js') }}
{{ HTML::script('assets/js/bootstrap-datetimepicker.js') }}
<script>
	$(function() {
		$('#datepicker').datetimepicker({
			pickTime: false
		});

		$('#campaign-type input[type="radio"]').on('change', function() {
			if ('other' == $(this).attr('id')) {
				$('#campaign-other').removeClass('hidden');
			} else {
				$('#campaign-other').val('').addClass('hidden');
			}
		});
	});

	showPublisherTerritories();
</script>
@stop
@endif