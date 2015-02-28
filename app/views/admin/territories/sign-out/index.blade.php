@extends('_templates.master')

@section('title')
Sign Out Territory ::
@parent
@stop

@section('content')

					<header>

						<h1 class="page-header">Sign Out Territory</h1>

					</header>

					<section id="territories-sign-out">

						<h3><i class="fa fa-chevron-circle-down"></i> Select a type of territory</h3>

						<div class="row">

							<div class="btn-group btn-group-vertical btn-group-lg col-xs-12 col-md-8">

	                            <a href="{{ route('admin.territories.sign-out.house-to-house') }}" class="btn btn-default" role="button">
	                                <h4 class="text-primary"><i class="fa fa-home"></i> House-to-House</h4>
	                            </a>

	                            <a href="{{ route('admin.territories.sign-out.letter-writing-phone') }}" class="btn btn-default" role="button">
	                                <h4 class="text-primary"><i class="fa fa-phone"></i> Letter Writing / Phone</h4>
	                            </a>

	                            <a href="{{ route('admin.territories.sign-out.business') }}" class="btn btn-default" role="button">
	                                <h4 class="text-primary"><i class="fa fa-building"></i> Business</h4>
	                            </a>

	                        </div>

                        </div>

					</section>

@stop