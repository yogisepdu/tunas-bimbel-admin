<?php

namespace App\Livewire\Checkout;

use App\Models\Packages;
use App\Models\PaymentMethod;
use App\Models\Transaction;
use App\Models\User;
use App\Services\TransactionService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class CheckoutPage extends Component
{
    public Packages $package;

    /*
    |--------------------------------------------------------------------------
    | Checkout
    |--------------------------------------------------------------------------
    */

    public string $billing = 'monthly';

    public ?int $payment_method_id = null;

    /*
    |--------------------------------------------------------------------------
    | Customer
    |--------------------------------------------------------------------------
    */

    public string $customer_name = '';

    public string $customer_email = '';

    public string $customer_phone = '';

    /*
    |--------------------------------------------------------------------------
    | Review
    |--------------------------------------------------------------------------
    */

    public bool $reviewing = false;

    /*
    |--------------------------------------------------------------------------
    | Mount
    |--------------------------------------------------------------------------
    */

    public function mount(int $id): void
    {
        $this->package = Packages::with('classes')
            ->findOrFail($id);

        /*
         * Periode yang dikirim dari landing page:
         *
         * /checkout/1?billing=monthly
         * /checkout/1?billing=yearly
         */
        $requestedBilling = request()->query(
            'billing',
            'monthly'
        );

        $this->billing = in_array(
            $requestedBilling,
            [
                Transaction::BILLING_MONTHLY,
                Transaction::BILLING_YEARLY,
            ],
            true
        )
            ? $requestedBilling
            : Transaction::BILLING_MONTHLY;

        /*
         * Pilih metode pembayaran aktif pertama
         * sebagai default.
         */
        $this->payment_method_id =
            PaymentMethod::active()
            ->ordered()
            ->value('id');

        /*
         * Jika kebetulan user login sebagai student,
         * isi otomatis data dasarnya.
         */
        if (
            Auth::check()
            && Auth::user()->role === 'student'
        ) {
            $this->customer_name =
                (string) Auth::user()->name;

            $this->customer_email =
                (string) Auth::user()->email;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Rules
    |--------------------------------------------------------------------------
    */

    protected function rules(): array
    {
        return [
            'customer_name' => [
                'required',
                'string',
                'min:3',
                'max:150',
            ],

            'customer_email' => [
                'required',
                'email',
                'max:150',
            ],

            'customer_phone' => [
                'required',
                'string',
                'min:9',
                'max:30',
                'regex:/^[0-9+\-\s()]+$/',
            ],

            'billing' => [
                'required',
                Rule::in([
                    Transaction::BILLING_MONTHLY,
                    Transaction::BILLING_YEARLY,
                ]),
            ],

            'payment_method_id' => [
                'required',
                'integer',

                Rule::exists(
                    'payment_methods',
                    'id'
                )->where(
                    fn($query) =>
                    $query->where(
                        'is_active',
                        true
                    )
                ),
            ],
        ];
    }

    protected function messages(): array
    {
        return [
            'customer_name.required' =>
            'Nama lengkap wajib diisi.',

            'customer_name.min' =>
            'Nama lengkap minimal 3 karakter.',

            'customer_email.required' =>
            'Email student wajib diisi.',

            'customer_email.email' =>
            'Format email belum valid.',

            'customer_phone.required' =>
            'Nomor WhatsApp wajib diisi.',

            'customer_phone.min' =>
            'Nomor WhatsApp terlalu pendek.',

            'customer_phone.regex' =>
            'Format nomor WhatsApp tidak valid.',

            'payment_method_id.required' =>
            'Silakan pilih metode pembayaran.',

            'payment_method_id.exists' =>
            'Metode pembayaran tidak tersedia.',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Set Billing
    |--------------------------------------------------------------------------
    */

    public function setBilling(
        string $billing
    ): void {
        if (
            ! in_array(
                $billing,
                [
                    Transaction::BILLING_MONTHLY,
                    Transaction::BILLING_YEARLY,
                ],
                true
            )
        ) {
            return;
        }

        $this->billing = $billing;

        /*
         * Kalau sudah di halaman review lalu periode
         * berubah, kembali ke form.
         */
        $this->reviewing = false;
    }

    /*
    |--------------------------------------------------------------------------
    | Review
    |--------------------------------------------------------------------------
    */

    public function reviewOrder(): void
    {
        $this->prepareCustomerData();

        $this->validate();

        /*
        |--------------------------------------------------------------------------
        | Pastikan Email Adalah Student
        |--------------------------------------------------------------------------
        */

        $student = $this->findStudent();

        if (! $student) {
            throw ValidationException::withMessages([
                'customer_email' =>
                'Email tidak terdaftar sebagai akun student Tunas Bimbel.',
            ]);
        }

        $this->reviewing = true;

        $this->dispatch(
            'checkout-review-ready'
        );
    }

    public function editCustomerData(): void
    {
        $this->reviewing = false;
    }

    /*
    |--------------------------------------------------------------------------
    | Create Invoice
    |--------------------------------------------------------------------------
    */

    public function createInvoice(
        TransactionService $transactionService
    ) {
        /*
         * Jangan percaya data dari review saja.
         * Validasi kembali ketika invoice dibuat.
         */
        $this->prepareCustomerData();

        $this->validate();

        /*
        |--------------------------------------------------------------------------
        | Student
        |--------------------------------------------------------------------------
        */

        $student = $this->findStudent();

        if (! $student) {
            throw ValidationException::withMessages([
                'customer_email' =>
                'Email tidak terdaftar sebagai akun student Tunas Bimbel.',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Payment Method
        |--------------------------------------------------------------------------
        */

        $paymentMethod =
            PaymentMethod::active()
            ->find(
                $this->payment_method_id
            );

        if (! $paymentMethod) {
            throw ValidationException::withMessages([
                'payment_method_id' =>
                'Metode pembayaran tidak lagi tersedia. Silakan pilih metode lain.',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Cek Invoice Aktif
        |--------------------------------------------------------------------------
        |
        | Mencegah user menekan tombol berkali-kali dan membuat
        | banyak invoice untuk paket yang sama.
        |
        */

        $existingTransaction =
            Transaction::query()
            ->where(
                'user_id',
                $student->id
            )
            ->where(
                'package_id',
                $this->package->id
            )
            ->whereIn(
                'status',
                [
                    Transaction::STATUS_PENDING_PAYMENT,
                    Transaction::STATUS_WAITING_VERIFICATION,
                ]
            )
            ->where(function ($query) {
                $query
                    ->whereNull('expires_at')
                    ->orWhere(
                        'expires_at',
                        '>',
                        now()
                    );
            })
            ->latest('id')
            ->first();

        /*
         * Kalau invoice masih aktif,
         * gunakan invoice tersebut.
         */
        if ($existingTransaction) {
            return redirect()->route(
                'payment.show',
                [
                    'token' =>
                    $existingTransaction
                        ->public_token,
                ]
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Create Transaction
        |--------------------------------------------------------------------------
        */

        $transaction =
            $transactionService->create(
                user: $student,
                package: $this->package,
                paymentMethod: $paymentMethod,
                customer: [
                    'name' =>
                    $this->customer_name,

                    'email' =>
                    $this->customer_email,

                    'phone' =>
                    $this->customer_phone,
                ],
                billing: $this->billing
            );

        /*
        |--------------------------------------------------------------------------
        | Redirect Payment
        |--------------------------------------------------------------------------
        */

        return redirect()->route(
            'payment.show',
            [
                'token' =>
                $transaction->public_token,
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Student
    |--------------------------------------------------------------------------
    */

    private function findStudent(): ?User
    {
        return User::query()
            ->where(
                'email',
                $this->customer_email
            )
            ->where(
                'role',
                'student'
            )
            ->first();
    }

    /*
    |--------------------------------------------------------------------------
    | Prepare
    |--------------------------------------------------------------------------
    */

    private function prepareCustomerData(): void
    {
        $this->customer_name = trim(
            $this->customer_name
        );

        $this->customer_email = strtolower(
            trim(
                $this->customer_email
            )
        );

        $this->customer_phone = trim(
            $this->customer_phone
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Price Helpers
    |--------------------------------------------------------------------------
    */

    public function monthlyPrice(): float
    {
        return (float) $this->package->price;
    }

    public function regularYearlyPrice(): float
    {
        return $this->monthlyPrice() * 12;
    }

    public function yearlyPrice(): float
    {
        /*
         * Sesuai sistem paket sebelumnya:
         * tahunan = bayar 10 bulan.
         */
        return $this->monthlyPrice() * 10;
    }

    public function yearlySaving(): float
    {
        return max(
            0,
            $this->regularYearlyPrice()
                - $this->yearlyPrice()
        );
    }

    public function subtotal(): float
    {
        return $this->billing ===
            Transaction::BILLING_YEARLY
            ? $this->regularYearlyPrice()
            : $this->monthlyPrice();
    }

    public function discount(): float
    {
        return $this->billing ===
            Transaction::BILLING_YEARLY
            ? $this->yearlySaving()
            : 0;
    }

    public function totalPrice(): float
    {
        return $this->billing ===
            Transaction::BILLING_YEARLY
            ? $this->yearlyPrice()
            : $this->monthlyPrice();
    }

    public function periodLabel(): string
    {
        return $this->billing ===
            Transaction::BILLING_YEARLY
            ? '12 bulan'
            : '1 bulan';
    }

    public function billingLabel(): string
    {
        return $this->billing ===
            Transaction::BILLING_YEARLY
            ? 'Tahunan'
            : 'Bulanan';
    }

    /*
    |--------------------------------------------------------------------------
    | Render
    |--------------------------------------------------------------------------
    */

    public function render()
    {
        $paymentMethods =
            PaymentMethod::active()
            ->ordered()
            ->get();

        $selectedPaymentMethod =
            $this->payment_method_id
            ? $paymentMethods->firstWhere(
                'id',
                $this->payment_method_id
            )
            : null;

        return view(
            'livewire.checkout.checkout-page',
            [
                'paymentMethods' =>
                $paymentMethods,

                'selectedPaymentMethod' =>
                $selectedPaymentMethod,
            ]
        )->layout('layouts.landing');
    }
}
