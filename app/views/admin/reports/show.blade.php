@extends('_templates.master')

@section('content')

		<?php $show = TRUE; ?>

		@include('admin.reports.' . $report)

		@include('_helpers.back-button', array('route' => 'admin.reports.index'))

@stop