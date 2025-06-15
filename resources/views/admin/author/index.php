@extends('layouts.app')
@section('title', 'User Management')
@section('content')
    <div class="p-4 mx-auto max-w-(--breakpoint-2xl) md:p-6">
        <!-- Breadcrumb Start -->
        <div x-data="{ pageName: `Basic Tables` }">
            <include src="./partials/breadcrumb.html" />
        </div>
        <!-- Breadcrumb End -->
        <div class="space-y-5 sm:space-y-6">
        </div>
    </div>
@endsection