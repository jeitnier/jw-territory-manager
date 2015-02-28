@extends('_templates.master')

@section('title')
Manage Publishers ::
@parent
@stop

@section('content')

					<header>

						<h1 class="page-header">Manage Publishers</h1>

					</header>

					<section id="publishers">

						<a href="{{ route('admin.publishers.create') }}" class="btn btn-primary btn-lg pull-left mobile-button">
							<i class="fa fa-plus-circle"></i> Add Publisher
						</a>

						<form class="search">
							<input type="text" id="publishers-search" class="form-control input-lg"
							placeholder="Search Publishers..." autofocus>
						</form>

						<div class="clearfix"></div>

						<hr>

						<div id="publishers-list">
							@include('admin.publishers.index-list')
						</div>

					</section>

@stop