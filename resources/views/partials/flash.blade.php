{{-- إشعارات منبثقة: من جلسة Laravel أو حدث Livewire --}}
<div
    x-data="{ show: false, message: '' }"
    @flash.window="message = $event.detail.message; show = true; setTimeout(() => show = false, 3000)"
    @if(session('success')) x-init="message = @js(session('success')); show = true; setTimeout(() => show = false, 3000)" @elseif(session('error')) x-init="message = @js(session('error')); show = true; setTimeout(() => show = false, 3500)" @endif
    x-show="show" x-cloak
    class="pointer-events-none fixed inset-x-0 top-5 z-[90] flex justify-center px-4"
>
    <div class="pointer-events-auto flex items-center gap-2.5 rounded-full border border-gold-500/30 bg-navy-950/95 px-6 py-3 text-sm font-bold text-ivory shadow-[0_12px_40px_rgba(14,27,42,.35)] backdrop-blur">
        <span class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-gold-500 text-navy-950">
            <x-shop-icon name="check" class="h-3 w-3" />
        </span>
        <span x-text="message"></span>
    </div>
</div>
