<?php

namespace App\Livewire;

use App\Models\Order;
use App\Models\PaymentMethod;
use App\Services\CartService;
use App\Services\CheckoutService;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;

class CheckoutModal extends Component
{
    use WithFileUploads;

    public bool $open = false;

    /** wallets | details | success */
    public string $step = 'wallets';

    public array $paymentMethods = [];
    public ?int $payment_method_id = null;

    public string $shipping_method = 'pickup';
    public string $city = '';
    public string $address = '';
    public ?string $payment_reference = null;
    public ?string $payment_sender_name = null;
    public array $dynamicData = [];
    public $proof = null;
    public ?string $notes = null;

    public ?string $orderCode = null;
    public ?array $orderTotals = null;

    #[On('open-checkout-modal')]
    public function open(): void
    {
        if (! auth()->user()) {
            session(['url.intended' => url()->previous()]);
            $this->redirect(route('login'));

            return;
        }

        if (CartService::count() === 0) {
            $this->dispatch('flash', message: __('cart.empty'));

            return;
        }

        $this->loadMethods();
        $this->resetValidation();
        $this->open = true;
        $this->step = 'wallets';
    }

    public function close(): void
    {
        $this->open = false;
        $this->reset(['step', 'payment_method_id', 'city', 'address', 'payment_reference', 'payment_sender_name', 'dynamicData', 'notes', 'orderCode', 'orderTotals']);
        $this->proof = null;
        $this->resetValidation();
    }

    protected function loadMethods(): void
    {
        $this->paymentMethods = PaymentMethod::active()
            ->orderBy('sort_order')
            ->get()
            ->map(fn (PaymentMethod $m) => [
                ...$m->toArray(),
                'icon' => $m->icon(),
                'icon_url' => $m->iconUrl(),
                'barcode_url' => $m->barcodeUrl(),
                'dynamic_fields' => $m->dynamicFields(),
            ])
            ->toArray();
    }

    public function getSelectedMethodProperty(): ?array
    {
        return collect($this->paymentMethods)
            ->first(fn ($m) => $m['id'] === $this->payment_method_id);
    }

    public function getRequiresProofProperty(): bool
    {
        return (bool) ($this->selectedMethod['requires_proof'] ?? false);
    }

    public function getTotalsProperty(): array
    {
        return CartService::totals($this->shipping_method);
    }

    /** اختيار محفظة → الانتقال لتفاصيلها */
    public function selectWallet(int $id): void
    {
        $this->payment_method_id = $id;
        $this->dynamicData = [];
        $this->step = 'details';
        $this->resetErrorBag();
    }

    /** رجوع خطوة للخلف */
    public function back(): void
    {
        if ($this->step === 'details') {
            $this->step = 'wallets';
            $this->resetErrorBag();
        }
    }

    public function confirm()
    {
        if (! $this->selectedMethod) {
            $this->addError('payment_method_id', 'اختر طريقة الدفع أولًا');

            return;
        }

        $rules = [
            'shipping_method' => ['required', 'in:pickup,local'],
            'city' => ['required_if:shipping_method,local', 'nullable', 'string', 'max:100'],
            'address' => ['required_if:shipping_method,local', 'nullable', 'string', 'max:500'],
            'payment_reference' => $this->requiresProof
                ? ['required_without:proof', 'nullable', 'string', 'max:100']
                : ['nullable', 'string', 'max:100'],
            'payment_sender_name' => ['nullable', 'string', 'max:100'],
            'proof' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,pdf', 'max:5120'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];

        $messages = [
            'city.required_if' => 'المدينة مطلوبة للتوصيل المحلي',
            'address.required_if' => 'العنوان مطلوب للتوصيل المحلي',
            'payment_reference.required_without' => 'أدخل رقم الحوالة أو ارفع صورة الإيصال (أحدهما مطلوب على الأقل)',
            'proof.mimes' => 'النوع المسموح: صورة (JPG/PNG/WEBP) أو ملف PDF',
            'proof.max' => 'حجم الملف يتجاوز 5MB',
        ];

        $data = $this->validate($rules, $messages);

        try {
            $order = CheckoutService::place(auth()->user(), [
                ...$data,
                'coupon_code' => '',
            ]);
        } catch (ValidationException $e) {
            foreach ($e->errors() as $key => $messages_) {
                foreach ($messages_ as $message) {
                    $this->addError($key, $message);
                }
            }

            return;
        }

        // ربط إثبات الدفع + الحقول الديناميكية
        $dynFormatted = collect($this->selectedMethod['dynamic_fields'] ?? [])
            ->filter(fn ($f) => ! empty($this->dynamicData[$f['label']] ?? null))
            ->map(fn ($f) => $f['label'].': '.$this->dynamicData[$f['label']])
            ->implode(' | ');

        try {
            $order->update([
                'payment_reference' => $this->payment_reference ?: null,
                'payment_sender_name' => $this->payment_sender_name ?: null,
                'notes' => trim((string) ($order->notes ?? '').($dynFormatted !== '' ? "\n".$dynFormatted : '')) ?: null,
            ]);
        } catch (\Throwable $e) {
            report($e);
        }

        if ($this->proof) {
            try {
                $path = $this->proof->store('payment-proofs', 'public');
                $order->update([
                    'payment_proof_path' => $path,
                    'payment_reference' => $this->payment_reference ?: null,
                    'payment_sender_name' => $this->payment_sender_name ?: null,
                ]);
            } catch (\Throwable $e) {
                report($e);
            }
        }

        $this->orderCode = $order->order_code;
        $this->orderTotals = [
            'total_usd' => $order->total_usd,
            'total_syp' => $order->total_syp,
        ];
        $this->step = 'success';
        $this->dispatch('cart-updated');
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.checkout-modal', [
            'totals' => $this->totals,
            'selectedMethod' => $this->selectedMethod,
            'requiresProof' => $this->requiresProof,
            'cartItems' => $this->open && $this->step !== 'success' ? CartService::detailed() : collect(),
        ]);
    }
}
