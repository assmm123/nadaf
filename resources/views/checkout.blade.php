@extends('layouts.app')

@section('title', __('checkout.title'))

@section('content')
    <div class="container-x mt-10">
        <div class="sec-head mb-10">
            <span class="kicker">— خطوة بخطوة بثقة —</span>
            <h1 class="section-title centered !mb-0 !text-3xl">{{ __('checkout.title') }}</h1>
        </div>
        <livewire:checkout-form />
    </div>
@endsection
