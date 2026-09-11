<?php

namespace App\Livewire;

use App\Models\Coupon;
use App\Models\PaymentMethod;
use App\Services\CartService;
use App\Services\CheckoutService;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Livewire\WithFileUploads;

class CheckoutForm extends Component
{
    use WithFileUploads;

    public string $shipping_method = 'pickup';
    public string $city = '';
    public string $address = '';
    public ?int $payment_method_id = null;
    public ?string $notes = null;
    public string $coupon_code = '';

    public $proof = null;                 // صورة/ملف إثبات الدفع
    public ?string $payment_reference = null;   // رقم الحوالة/الإشعار
    public ?string $payment_sender_name = null; // اسم مرسل الحوالة

    public ?int $appliedCouponId = null;
    public string $couponMessage = '';

    public array $paymentMethods = [];

    public function mount()
    {
        $this->paymentMethods = PaymentMethod::active()
            ->orderBy('sort_order')
            ->get()
            ->map(fn (PaymentMethod $m) => [
                ...$m->toArray(),
                'icon' => $m->icon(),
                'barcode_url' => $m->barcodeUrl(),
            ])
            ->toArray();
    }

    public function getTotalsProperty()
    {
        return CartService::totals($this->shipping_method, $this->coupon);
    }

    public function getCouponProperty(): ?Coupon
    {
        return $this->appliedCouponId ? Coupon::find($this->appliedCouponId) : null;
    }

    public function getSelectedMethodProperty(): ?array
    {
        return collect($this->paymentMethods)
            ->first(fn ($m) => $m['id'] === $this->payment_method_id);
    }

    /** هل تتطلب وسيلة الدفع المختارة إثبات دفع قبل توليد الكود؟ */
    public function getRequiresProofProperty(): bool
    {
        return (bool) ($this->selectedMethod['requires_proof'] ?? false);
    }

    public function applyCoupon()
    {
        $coupon = Coupon::where('code', trim($this->coupon_code))->first();

        if ($coupon && $coupon->isValid($this->totals['subtotal_usd'])) {
            $this->appliedCouponId = $coupon->id;
            $this->couponMessage = __('cart.coupon_applied');
        } else {
            $this->appliedCouponId = null;
            $this->couponMessage = __('checkout.invalid_coupon');
        }
    }

    public function confirm()
    {
        $rules = [
            'shipping_method' => ['required', 'in:pickup,local'],
            'city' => ['required_if:shipping_method,local', 'nullable', 'string', 'max:100'],
            'address' => ['required_if:shipping_method,local', 'nullable', 'string', 'max:500'],
            'payment_method_id' => ['required', 'in:'.implode(',', array_column($this->paymentMethods, 'id'))],
            'notes' => ['nullable', 'string', 'max:1000'],
            'coupon_code' => ['nullable', 'string', 'max:50'],
        ];

        // إثبات الدفع: للوسائل التي تتطلبه — رقم الحوالة أو رفع الإيصال (أحدهما على الأقل)
        if ($this->requiresProof) {
            $rules['proof'] = ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,pdf', 'max:5120'];
            $rules['payment_reference'] = ['required_without:proof', 'nullable', 'string', 'max:100'];
        } else {
            $rules['proof'] = ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,pdf', 'max:5120'];
            $rules['payment_reference'] = ['nullable', 'string', 'max:100'];
        }
        $rules['payment_sender_name'] = ['nullable', 'string', 'max:100'];

        $messages = [
            'proof.required' => 'يجب رفع صورة إيصال الدفع قبل توليد كود الطلب',
            'proof.file' => 'ملف الإثبات غير صالح — ارفع صورة أو PDF',
            'proof.mimes' => 'النوع المسموح: صورة (JPG/PNG/WEBP) أو ملف PDF',
            'proof.max' => 'حجم الملف يتجاوز 5MB',
            'payment_reference.required_without' => 'أدخل رقم الحوالة أو ارفع صورة الإيصال (أحدهما مطلوب على الأقل)',
            'city.required_if' => 'المدينة مطلوبة للتوصيل المحلي',
        ];

        $data = $this->validate($rules, $messages);

        $data['coupon_code'] = $this->appliedCouponId
            ? Coupon::find($this->appliedCouponId)?->code
            : $this->coupon_code;

        try {
            $order = CheckoutService::place(auth()->user(), $data);
        } catch (ValidationException $e) {
            foreach ($e->errors() as $key => $messages) {
                foreach ($messages as $message) {
                    $this->addError($key === 'coupon_code' ? 'coupon_code' : 'general', $message);
                }
            }

            return;
        }

        // ربط إثبات الدفع بالطلب (بعد نجاح الحفظ)
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

        session()->flash('success', 'شكرًا '.auth()->user()->name.' لثقتك بشركة النداف 💛 — طلبك قيد المراجعة وسيتم الرد عليك في أسرع وقت ممكن.');

        $this->redirect(route('checkout.success', $order->order_code), navigate: false);
    }

    public function render()
    {
        return view('livewire.checkout-form', [
            'totals' => $this->totals,
            'requiresProof' => $this->requiresProof,
            'selectedMethod' => $this->selectedMethod,
        ]);
    }
}
