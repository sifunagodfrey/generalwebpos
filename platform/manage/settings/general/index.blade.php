{{-- ------------------- General settings ------------------- --}}
@extends('manage.layouts.app')

@section('title', 'General settings')

@section('content')

    @include('manage.partials.head', [
        'title'  => 'General settings',
        'lead'   => 'The company name, the marks and the currency every price is shown in.',
        'crumbs' => ['Configurations' => route('manage.settings.general.index'), 'General' => null],
    ])

    <div class="grid lg:grid-cols-4 gap-6 items-start">

        <div class="lg:col-span-1">
            @include('manage.settings.partials.nav')
        </div>

        <form method="POST" action="{{ route('manage.settings.general.update') }}" enctype="multipart/form-data" class="lg:col-span-3 space-y-6">
            @csrf


        <div class="nm-panel">
            <div class="nm-panel__head">The company</div>
            <div class="nm-panel__body grid sm:grid-cols-2 gap-5">

                @include('manage.partials.form.field', [
                    'name' => 'company_name',
                    'label' => 'Registered name',
                    'placeholder' => 'Duramax Mabati',
                    'required' => true,
                    'value' => $values['company_name'] ?? null,
                ])

                @include('manage.partials.form.field', [
                    'name' => 'site_name',
                    'label' => 'Short name',
                    'help' => 'Used where the full name will not fit.',
                    'value' => $values['site_name'] ?? null,
                ])

                @include('manage.partials.form.field', [
                    'name' => 'tagline',
                    'label' => 'Tagline',
                    'placeholder' => 'Quality Roofing Solutions',
                    'wrapper' => 'sm:col-span-2',
                    'value' => $values['tagline'] ?? null,
                ])

            </div>
        </div>

        <div class="nm-panel">
            <div class="nm-panel__head">Marks</div>
            <div class="nm-panel__body grid sm:grid-cols-2 gap-5">

                @include('manage.partials.form.field', [
                    'name' => 'logo',
                    'label' => 'Logo',
                    'type' => 'file',
                    'help' => 'Used on documents and on a light background.',
                    'value' => null,
                    'current' => $values['logo'] ?? null,
                ])

                @include('manage.partials.form.field', [
                    'name' => 'logo_light',
                    'label' => 'Logo for dark backgrounds',
                    'type' => 'file',
                    'help' => 'The white version used in the header block.',
                    'value' => null,
                    'current' => $values['logo_light'] ?? null,
                ])

                @include('manage.partials.form.field', [
                    'name' => 'favicon',
                    'label' => 'Browser icon',
                    'type' => 'file',
                    'help' => 'Square, at least 64 pixels.',
                    'value' => null,
                    'current' => $values['favicon'] ?? null,
                ])

            </div>
        </div>

        <div class="nm-panel">
            <div class="nm-panel__head">Currency</div>
            <div class="nm-panel__body grid sm:grid-cols-2 gap-5">

                @include('manage.partials.form.field', [
                    'name' => 'currency_code',
                    'label' => 'Code',
                    'placeholder' => 'KES',
                    'value' => $values['currency_code'] ?? null,
                ])

                @include('manage.partials.form.field', [
                    'name' => 'currency_symbol',
                    'label' => 'Symbol',
                    'placeholder' => 'KSh',
                    'value' => $values['currency_symbol'] ?? null,
                ])

            </div>
        </div>


            @include('manage.partials.form.actions', ['label' => 'Save settings'])

        </form>

    </div>

@endsection
