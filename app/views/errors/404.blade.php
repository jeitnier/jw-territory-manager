@extends('errors.master')

@section('title')
@parent
:: 404 Error!
@stop

@section('content')
					
					<p class="col-xs-12 col-sm-8 col-md-6 col-lg-4">Whoops, that page isn't for real! <a href="{{ url('') }}">Head back here</a> to get going...</p>
@stop