@extends('layouts.app')

@section('title', $page->title)

@section('content')
    <div class="container-x mt-10 max-w-4xl">

        {{-- رأس الصفحة --}}
        <div class="sec-head">
            <span class="kicker">— {{ setting('store_name_ar', 'نداف') }} —</span>
            <h1 class="section-title centered !mb-0 !text-3xl">{{ $page->title }}</h1>
        </div>

        {{-- النص العام إن وجد --}}
        @if ($page->content)
            <div class="card-lux prose prose-sm max-w-none p-8 leading-8 sm:p-10 [&_h3]:font-extrabold [&_h3]:text-navy-950 [&_strong]:text-navy-950">
                {!! \Illuminate\Support\Str::markdown($page->content ?? '') !!}
            </div>
        @endif

        {{-- الأقسام الديناميكية --}}
        @forelse ($page->sections as $section)
            <section class="card-lux mt-7 overflow-hidden p-8 sm:p-10" wire:key="section-{{ $section->id }}">
                @if ($section->heading)
                    <div class="mb-6 flex items-center gap-4">
                        <span class="h-7 w-1.5 rounded-full bg-gold-500"></span>
                        <h2 class="text-xl font-extrabold text-navy-950" style="font-family:Almarai,Tajawal,sans-serif">{{ $section->heading }}</h2>
                    </div>
                @endif

                @php $images = $section->mediaUrls($section->images); $videos = $section->mediaUrls($section->videos); @endphp

                {{-- تخطيط نص + صور جانبًا لجانب --}}
                @if ($section->layout === 'split' && $section->body && count($images))
                    <div class="grid items-center gap-8 md:grid-cols-2">
                        <div class="whitespace-pre-line text-[15px] leading-8 text-gray-600">{{ $section->body }}</div>
                        <div class="grid grid-cols-2 gap-3">
                            @foreach ($images as $img)
                                <img src="{{ $img }}" alt="{{ $section->heading }}" loading="lazy"
                                     class="aspect-square w-full rounded-2xl object-cover shadow-sm transition duration-500 hover:scale-[1.02]">
                            @endforeach
                        </div>
                    </div>
                @else
                    @if ($section->body)
                        <div class="whitespace-pre-line text-[15px] leading-8 text-gray-600">{{ $section->body }}</div>
                    @endif

                    @if (count($images))
                        <div class="mt-6 grid grid-cols-2 gap-3 sm:grid-cols-3">
                            @foreach ($images as $img)
                                <img src="{{ $img }}" alt="{{ $section->heading }}" loading="lazy"
                                     class="aspect-square w-full rounded-2xl object-cover shadow-sm transition duration-500 hover:scale-[1.02]">
                            @endforeach
                        </div>
                    @endif

                    @if (count($videos))
                        <div class="mt-6 grid gap-4 sm:grid-cols-2">
                            @foreach ($videos as $vid)
                                <video src="{{ $vid }}" controls preload="metadata"
                                       class="w-full max-h-80 rounded-2xl bg-navy-950 shadow-sm"></video>
                            @endforeach
                        </div>
                    @endif
                @endif
            </section>
        @empty
        @endforelse
    </div>
@endsection
