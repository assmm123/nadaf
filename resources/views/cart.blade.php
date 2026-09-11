@extends('layouts.app')

@section('title', __('cart.title'))

@section('content')
    <div class="container-x mt-10">
        <div class="sec-head mb-10">
            <span class="kicker">— خطوة بخطوة بثقة —</span>
            <h1 class="section-title centered !mb-0 !text-3xl">{{ __('cart.title') }}</h1>
        </div>
        <livewire:cart-table />
    </div>
@endsection
