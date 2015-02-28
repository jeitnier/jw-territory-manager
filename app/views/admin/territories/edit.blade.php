@extends('_templates.master')

@section('title')
Edit Territory {{ $territory->label }}::
@parent
@stop

@section('content')

					<header>

						<h1 class="page-header">Edit Territory <em>{{ $territory->label }}</em></h1>

					</header>

					<section id="territories">

						<div class="row">

							{{ Form::model($territory, [
								'route'  => ['admin.territories.update', $territory->id],
								'method' => 'PUT',
								'role'   => 'form',
								'class'  => 'col-xs-12 col-sm-8'
							]) }}

								<div class="form-group">
									<label for="label">Territory Label</label>
									{{ Form::text(
										'label',
										NULL,
										['id' => 'label', 'class' => 'form-control input-lg', 'required', 'autofocus']
									) }}
								</div>

								<div class="form-group input-group-lg">
									<label for="territory-type">Territory Type</label>
									{{ Form::select(
										'type_id',
										TerritoriesTypes::all()->lists('label', 'id'),
										$territory->type_id,
										['id' => 'territory-type', 'class' => 'form-control']
									) }}
								</div>

								<div class="form-group input-group-lg">
									<label for="territory-area-type">Area Type</label>
									{{ Form::select(
										'area_type_id',
										TerritoriesAreaTypes::all()->lists('label', 'id'),
										$territory->area_type_id,
										['id' => 'territory-area-type', 'class' => 'form-control']
									) }}
								</div>

								<div class="form-group">
									<label for="map-embed-id">Map Embed ID</label>
									{{ Form::text(
										'map_embed_id',
										NULL,
										['id' => 'map-embed-id', 'class' => 'form-control input-lg', 'required']
									) }}
								</div>

								<div class="form-group">
									<label for="notes">Notes</label>
									{{ Form::textarea(
										'notes',
										NULL,
										['id' => 'notes', 'class' => 'form-control input-lg']
									) }}
								</div>

								<div class="form-group">
									<a href="{{ route('admin.territories.index') }}" class="btn btn-default btn-lg">
										<i class="fa fa-arrow-circle-left"></i> Cancel
									</a>
									<button type="submit" class="btn btn-primary btn-lg">
										<i class="fa fa-check-circle"></i> Update Territory
									</button>
								</div>

							{{ Form::close() }}

						</div>

					</section>

@stop