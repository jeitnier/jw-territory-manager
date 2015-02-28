@extends('_templates.master')

@section('title')
Manage Territories ::
@parent
@stop

@section('content')

					<header>

						<h1 class="page-header">Manage Territories</h1>

					</header>

					<section id="territories">

						<a href="{{ route('admin.territories.create') }}" class="btn btn-primary btn-lg pull-left mobile-button">
							<i class="fa fa-plus-circle"></i> Add Territory
						</a>

						<form class="search">
							<input type="text" id="territories-search" class="form-control input-lg"
							placeholder="Search Territories..." autofocus>
						</form>

						<div class="clearfix"></div>

						<hr>

						<div id="territories-list">
							@include('admin.territories.index-list')
						</div>

					</section>

@stop