@extends('layouts.app')
@section('title', 'Edit Template — ' . $template->nama_template)
@section('page-title', 'Edit Template')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('template-kepanitiaan.index') }}">Template Panitia</a></li>
<li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
    @include('template-kepanitiaan.create')
@endsection
