@extends('layouts.customer')

@section('title', __('Profile'))

@section('header')
    <h1 class="text-2xl font-semibold text-gray-900">{{ __('Profile') }}</h1>
@endsection

@section('content')
    <section class="py-12">
        <div class="max-w-7xl mx-auto space-y-6 px-4 sm:px-6 lg:px-8">
            <div class="rounded-lg bg-white p-4 shadow sm:p-8">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="rounded-lg bg-white p-4 shadow sm:p-8">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="rounded-lg bg-white p-4 shadow sm:p-8">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </section>
@endsection
