@extends('_templates.master')

@section('title')
Territory Resolver ::
@parent
@stop

@section('content')

					<header>

						<h1 class="page-header">Find Territory By Address</h1>

					</header>

					<section id="utilities" class="territory-resolver">

						<div class="row">

							{{ Form::open(array(
								'role'  => 'form',
								'class' => 'col-xs-12 col-sm-8'
							)) }}

							<div class="form-group">
								<label for="address">Enter Address</label>
								<input type="text" id="address" class="form-control input-lg" name="address" placeholder="Ex: 125 Fremont St., Lancaster, PA 17603">
							</div><div class="form-group">
								<button type="submit" class="btn btn-primary btn-lg"><i class="fa fa-search"></i> Search</button>
							</div>

							<p><em><strong>NOTE:</strong> Not all addresses will fall in territory boundaries.</em></p>

							{{ Form::close() }}

						</div>

						<div id="messages" class="hide clearfix">
							<i class="pull-left fa fa-spinner fa-spin fa-3x"></i>
							<h3 class="pull-left">Searching Territories...</h3>
						</div>

						<div class="clearfix"></div>

					</section>

@stop