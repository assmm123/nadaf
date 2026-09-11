@extends('layouts.app')

@section('title', __('contact.title'))

@section('content')
    <div class="container-x mt-12 max-w-4xl pb-6">
        <div class="sec-head">
            <span class="kicker">— {{ __('contact.title') }} —</span>
            <h1 class="section-title centered !mb-0 !text-3xl">{{ __('contact.title') }}</h1>
            <p class="mt-3 text-sm leading-7 text-gray-500">{{ __('contact.subtitle') }}</p>
            <div class="divider-damask mt-6"><span>❦</span></div>
        </div>

        <div class="grid gap-5 sm:grid-cols-2">
            @foreach ($contactMethods as $method)
                <a href="{{ $method->link() }}" target="_blank" rel="noopener"
                   class="card group flex items-center gap-5 p-6 transition hover:-translate-y-1 hover:border-gold-500/50 hover:shadow-lg" wire:key="cm-{{ $method->id }}">
                    <span class="grid h-14 w-14 shrink-0 place-items-center rounded-2xl bg-navy-950 text-gold-400 transition group-hover:bg-gold-500 group-hover:text-navy-950">
                        <x-shop-icon name="{{ \App\Models\CommunicationMethod::TYPES[$method->type]['icon'] ?? 'globe' }}" class="h-6 w-6" />
                    </span>
                    <div class="min-w-0">
                        <p class="font-extrabold text-navy-950" style="font-family:Almarai,Tajawal,sans-serif">{{ $method->label ?? $method->typeLabel() }}</p>
                        <p class="font-code mt-1 truncate text-sm text-gray-500" dir="ltr">{{ $method->value }}</p>
                    </div>
                    <x-shop-icon name="arrow" class="ms-auto h-4 w-4 shrink-0 text-gray-300 transition group-hover:text-gold-600 rtl:-scale-x-100" />
                </a>
            @endforeach
        </div>
    </div>
@endsection
