@extends('layouts.app')

@section('title', __('search.title'))

@section('content')
    <div class="container-x mt-10">

        {{-- رأس البحث --}}
        <div class="sec-head">
            <span class="kicker">{{ __('search.title') }}</span>
            <h1 class="section-title centered !mb-0 !text-3xl">
                @if ($q !== '')
                    {{ __('search.results_for', ['q' => $q]) }}
                @else
                    {{ __('search.title') }}
                @endif
            </h1>
        </div>

        {{-- حقل البحث --}}
        <form action="{{ route('search') }}" method="GET" class="relative mx-auto mb-12 max-w-xl">
            <input type="search" name="q" value="{{ $q }}" placeholder="{{ __('nav.search_placeholder') }}" autofocus
                   class="w-full rounded-full border border-navy-950/12 bg-white px-6 py-3.5 text-sm outline-none transition focus:border-gold-500 focus:shadow-[0_0_0_3px_rgba(198,164,76,.14)] pe-13">
            <button type="submit" class="absolute top-1/2 -translate-y-1/2 text-gray-400 transition hover:text-gold-600 end-4" aria-label="{{ __('search.title') }}">
                <x-shop-icon name="search" class="h-5 w-5" />
            </button>
        </form>

        @if ($products->isNotEmpty())
            <div class="grid grid-cols-2 gap-5 md:grid-cols-3 lg:grid-cols-4">
                @foreach ($products as $product)
                    @include('partials.product-card', ['product' => $product])
                @endforeach
            </div>
            <div class="mt-10">{{ $products->links() }}</div>
        @else
            <div class="card-lux mx-auto flex max-w-lg flex-col items-center gap-4 p-16 text-center">
                <span class="grid h-16 w-16 place-items-center rounded-full bg-sand text-gold-600">
                    <x-shop-icon name="search" class="h-7 w-7" />
                </span>
                <p class="font-extrabold text-navy-950" style="font-family:Almarai,Tajawal,sans-serif">{{ __('search.no_results') }}</p>
                <a href="{{ route('home') }}" class="btn-gold">{{ __('cart.continue_shopping') }}</a>
            </div>
        @endif
    </div>
@endsection
