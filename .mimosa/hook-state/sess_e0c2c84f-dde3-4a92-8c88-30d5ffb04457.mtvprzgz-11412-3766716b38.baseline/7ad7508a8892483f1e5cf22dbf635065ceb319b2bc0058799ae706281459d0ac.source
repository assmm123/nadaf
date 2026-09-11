<x-filament-panels::page>
    <div class="space-y-4">

        <div class="rounded-xl border border-amber-200 bg-amber-50 p-4 text-sm text-amber-800">
            أدخل <b>العدّ الفعلي</b> لكل صنف بعد جره فعليًا — الصنف الذي يتطابق عدّه مع الدفتري يُتجاهل تلقائيًا، والفروقات تُسجَّل كحركات «تسوية جرد» في سجل الحركات عند الضغط على حفظ.
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <input type="text" wire:model.live.debounce.400ms="search" placeholder="ابحث عن منتج..."
                   class="rounded-lg border border-gray-300 px-3 py-2 text-sm w-64">
            <span class="text-xs text-gray-500">{{ count($counts) }} صنفًا</span>
            <button wire:click="apply" wire:loading.attr="disabled"
                    class="ms-auto rounded-lg bg-primary-600 px-5 py-2.5 text-sm font-bold text-white hover:bg-primary-700 disabled:opacity-60">
                💾 حفظ التسويات
            </button>
        </div>

        <div class="overflow-x-auto rounded-xl border border-gray-200 bg-white">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b bg-gray-50 text-xs text-gray-500">
                        <th class="p-3 text-start">الصنف</th>
                        <th class="p-3">الرصيد الدفتري</th>
                        <th class="p-3">العدّ الفعلي</th>
                        <th class="p-3">الفرق</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($counts as $i => $row)
                        @php $d = (int) $row['actual'] - $row['book']; @endphp
                        <tr class="border-b border-gray-50 {{ $d !== 0 ? 'bg-amber-50/50' : '' }}" wire:key="cnt-{{ $row['id'] }}">
                            <td class="p-3 font-bold">{{ $row['name'] }}</td>
                            <td class="p-3 text-center">{{ $row['book'] }}</td>
                            <td class="p-3 text-center">
                                <input type="number" min="0" wire:model.live.debounce.500ms="counts.{{ $i }}.actual"
                                       class="w-24 rounded-lg border border-gray-300 px-2 py-1.5 text-center">
                            </td>
                            <td class="p-3 text-center font-extrabold {{ $d > 0 ? 'text-green-600' : ($d < 0 ? 'text-red-600' : 'text-gray-300') }}">
                                {{ $d > 0 ? '+' : '' }}{{ $d }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-filament-panels::page>
