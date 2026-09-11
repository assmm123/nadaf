<div wire:key="floating-chat">
    {{-- زر الشات العائم — نبضة ذهبية --}}
    @if (! $open)
        <button wire:click="toggle" aria-label="فتح المحادثة"
                class="pulse-ring fixed bottom-6 end-6 z-[80] grid h-14 w-14 place-items-center rounded-full shadow-[0_10px_30px_rgba(162,131,58,.4)] transition hover:-translate-y-1"
                style="background:linear-gradient(135deg,#d8b96b,#a2833a);color:#0e1b2a">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-6 w-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H8.25m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H12m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 0 1-2.555-.337A5.972 5.972 0 0 1 5.41 20.97a5.969 5.969 0 0 1-.474-.065 4.48 4.48 0 0 0 .978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25Z" />
            </svg>
            <span class="absolute -top-0.5 -end-0.5 h-3.5 w-3.5 rounded-full border-2 border-ivory bg-[#5F7A60]"></span>
        </button>
    @endif

    {{-- نافذة المحادثة --}}
    @if ($open)
        <div class="fixed bottom-6 end-6 z-[80] flex h-[480px] w-[calc(100vw-2.5rem)] max-w-sm flex-col overflow-hidden rounded-3xl border border-navy-950/10 bg-white shadow-[0_20px_60px_rgba(14,27,42,.25)]" x-data x-cloak>
            <div class="relative flex items-center justify-between bg-navy-950 px-5 py-3.5">
                <div class="pointer-events-none absolute inset-0 bg-damask-lines"></div>
                <div class="relative flex items-center gap-3">
                    <span class="grid h-10 w-10 place-items-center rounded-full bg-gold-500/15 text-gold-400">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-5 w-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H8.25m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H12m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 0 1-2.555-.337A5.972 5.972 0 0 1 5.41 20.97a5.969 5.969 0 0 1-.474-.065 4.48 4.48 0 0 0 .978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25Z" />
                        </svg>
                    </span>
                    <div>
                        <p class="text-sm font-extrabold text-ivory" style="font-family:Almarai,Tajawal,sans-serif">مساعد نداف</p>
                        <p class="mt-0.5 flex items-center gap-1.5 text-[10px] text-[#8fce8f]">
                            <span class="h-1.5 w-1.5 rounded-full bg-[#5F7A60]"></span>
                            متصل الآن — رد فوري
                        </p>
                    </div>
                </div>
                <button wire:click="toggle" class="relative rounded-full p-1.5 text-ivory/60 transition hover:bg-ivory/10 hover:text-ivory" aria-label="إغلاق">✕</button>
            </div>

            {{-- الرسائل --}}
            <div class="flex-1 space-y-3.5 overflow-y-auto bg-sand/30 p-3.5" wire:key="chat-msgs">
                @foreach ($messages as $i => $msg)
                    <div class="{{ $msg['from'] === 'user' ? 'flex justify-end' : 'flex justify-start' }}" wire:key="msg-{{ $i }}">
                        <div class="{{ $msg['from'] === 'user'
                            ? 'rounded-2xl rounded-te-md bg-navy-950 text-ivory'
                            : 'rounded-2xl rounded-ts-md border border-navy-950/8 bg-white text-navy-950' }} max-w-[85%] px-4 py-2.5 text-sm leading-6 whitespace-pre-line shadow-sm">
                            {{ $msg['text'] }}

                            @if (isset($msg['suggestions']))
                                <div class="mt-3 flex flex-wrap gap-1.5">
                                    @foreach ($msg['suggestions'] as $s)
                                        <button wire:click="ask(@js($s))"
                                                class="rounded-full border border-gold-500/60 bg-gold-50 px-3.5 py-1 text-xs font-bold text-gold-700 transition hover:bg-gold-500 hover:text-navy-950">
                                            {{ $s }}
                                        </button>
                                    @endforeach
                                </div>
                            @endif

                            @if (isset($msg['show_contact']) && $msg['show_contact'])
                                {{-- وسائل التواصل بأيقونات كبيرة --}}
                                <div class="mt-3.5 grid grid-cols-2 gap-2">
                                    @foreach (\App\Models\CommunicationMethod::where('is_active', true)->orderBy('sort_order')->get() as $cm)
                                        <a href="{{ $cm->link() }}" target="_blank" rel="noopener"
                                           class="group flex flex-col items-center gap-2 rounded-2xl border border-navy-950/8 bg-gradient-to-b from-white to-sand/50 py-3.5 transition hover:-translate-y-0.5 hover:border-gold-500/60 hover:shadow-md">
                                            <span class="grid h-11 w-11 place-items-center rounded-full bg-navy-950 text-gold-400 transition group-hover:bg-gold-500 group-hover:text-navy-950">
                                                <x-shop-icon name="{{ \App\Models\CommunicationMethod::TYPES[$cm->type]['icon'] ?? 'globe' }}" class="h-5.5 w-5.5" />
                                            </span>
                                            <span class="text-xs font-extrabold text-navy-950">{{ $cm->label ?? $cm->typeLabel() }}</span>
                                        </a>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- الإدخال --}}
            <form wire:submit="ask" class="flex items-center gap-2.5 border-t border-navy-950/8 bg-white p-3">
                <input type="text" wire:model="message" placeholder="اكتب سؤالك..."
                       class="flex-1 rounded-full border border-navy-950/12 bg-ivory px-5 py-2.5 text-sm outline-none transition focus:border-gold-500 focus:bg-white" maxlength="300" autocomplete="off">
                <button type="submit" wire:loading.attr="disabled"
                        class="grid h-10 w-10 shrink-0 place-items-center rounded-full bg-gradient-to-br from-gold-400 to-gold-600 text-navy-950 shadow-md transition hover:scale-105" aria-label="إرسال">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-4.5 w-4.5 rtl:-scale-x-100">
                        <path d="M3.478 2.405a.75.75 0 0 0-.926.94l2.432 7.905H13.5a.75.75 0 0 1 0 1.5H4.984l-2.432 7.905a.75.75 0 0 0 .926.94 60.519 60.519 0 0 0 18.445-8.986.75.75 0 0 0 0-1.218A60.517 60.517 0 0 0 3.478 2.405Z" />
                    </svg>
                </button>
            </form>
        </div>
    @endif
</div>
