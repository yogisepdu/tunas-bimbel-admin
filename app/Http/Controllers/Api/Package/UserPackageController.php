<?php

namespace App\Http\Controllers\Api\Package;

use App\Http\Controllers\Controller;
use App\Models\ClassRoom;
use App\Models\Packages;
use App\Models\PaymentMethod;
use App\Models\Transaction;
use App\Services\TransactionService;
use App\Support\StudentAccess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class UserPackageController extends Controller
{
    /**
     * Daftar paket + status kepemilikan aktif.
     */
    public function index(
        Request $request
    ) {
        $user = $request->user();

        StudentAccess::ensureStudent(
            $user
        );

        $activeEntitlements =
            DB::table('user_packages')
            ->where(
                'user_id',
                $user->id
            )
            ->where(
                'status',
                'active'
            )
            ->where(function ($query) {
                $query
                    ->whereNull(
                        'activated_at'
                    )
                    ->orWhere(
                        'activated_at',
                        '<=',
                        now()
                    );
            })
            ->where(function ($query) {
                $query
                    ->whereNull(
                        'expires_at'
                    )
                    ->orWhere(
                        'expires_at',
                        '>',
                        now()
                    );
            })
            ->get()
            ->keyBy('package_id');

        $packages =
            Packages::query()
            ->with('classes')
            ->latest()
            ->get()
            ->map(
                function (
                    $package
                ) use (
                    $activeEntitlements
                ) {
                    $entitlement =
                        $activeEntitlements
                        ->get(
                            $package->id
                        );

                    return [
                        'id' =>
                        $package->id,

                        'name' =>
                        $package->name,

                        'description' =>
                        $package
                            ->description,

                        'price' =>
                        (float)
                        $package->price,

                        'price_yearly' =>
                        (float)
                        $package->price
                            * 10,

                        'image' =>
                        $package->image
                            ? asset(
                                'storage/'
                                    . $package->image
                            )
                            : null,

                        'totalClass' =>
                        $package
                            ->classes
                            ->count(),

                        'is_owned' =>
                        (bool)
                        $entitlement,

                        'access_status' =>
                        $entitlement
                            ? 'active'
                            : null,

                        'activated_at' =>
                        $entitlement
                            ? $entitlement
                            ->activated_at
                            : null,

                        'expires_at' =>
                        $entitlement
                            ? $entitlement
                            ->expires_at
                            : null,
                    ];
                }
            );

        return response()->json([
            'data' => $packages,
        ]);
    }

    /**
     * Kelas yang benar-benar masih dapat diakses.
     */
    public function myClasses(
        Request $request
    ) {
        $user = $request->user();

        StudentAccess::ensureStudent(
            $user
        );

        $classIds =
            StudentAccess::accessibleClassIds(
                $user
            );

        if ($classIds->isEmpty()) {
            return response()->json([
                'data' => [],
            ]);
        }

        $classes = ClassRoom::query()
            ->whereIn(
                'id',
                $classIds
            )
            ->with('chapters')
            ->get();

        return response()->json([
            'data' => $classes,
        ]);
    }

    /**
     * Metode pembayaran yang dapat dipilih dari aplikasi.
     *
     * Nomor rekening lengkap tidak perlu dikirim pada tahap ini.
     * Detail snapshot akan tersedia setelah transaksi dibuat.
     */
    public function paymentMethods(
        Request $request
    ) {
        StudentAccess::ensureStudent(
            $request->user()
        );

        $methods =
            PaymentMethod::query()
            ->active()
            ->ordered()
            ->get()
            ->map(
                fn($method) => [
                    'id' =>
                    $method->id,

                    'name' =>
                    $method->name,

                    'type' =>
                    $method->type,

                    'provider' =>
                    $method->provider,

                    'mode' =>
                    $method->mode,

                    'requires_proof' =>
                    (bool)
                    $method
                        ->requires_proof,
                ]
            );

        return response()->json([
            'data' => $methods,
        ]);
    }

    /**
     * Riwayat transaksi student.
     */
    public function myTransactions(
        Request $request
    ) {
        $user = $request->user();

        StudentAccess::ensureStudent(
            $user
        );

        $transactions =
            Transaction::query()
            ->where(
                'user_id',
                $user->id
            )
            ->latest('id')
            ->get()
            ->map(
                function (
                    $transaction
                ) {
                    return [
                        'invoice_no' =>
                        $transaction
                            ->invoice_no,

                        'package_id' =>
                        $transaction
                            ->package_id,

                        'package_name' =>
                        $transaction
                            ->package_name,

                        'billing' =>
                        $transaction
                            ->billing,

                        'billing_label' =>
                        $transaction
                            ->billing_label,

                        'duration_months' =>
                        $transaction
                            ->duration_months,

                        'total' =>
                        (float)
                        $transaction
                            ->total,

                        'status' =>
                        $transaction
                            ->status,

                        'status_label' =>
                        $transaction
                            ->status_label,

                        'payment_method' =>
                        $transaction
                            ->payment_method_name,

                        'created_at' =>
                        $transaction
                            ->created_at
                            ?->toIso8601String(),

                        'expires_at' =>
                        $transaction
                            ->expires_at
                            ?->toIso8601String(),

                        'payment_url' =>
                        route(
                            'payment.show',
                            [
                                'token' =>
                                $transaction
                                    ->public_token,
                            ]
                        ),
                    ];
                }
            );

        return response()->json([
            'data' => $transactions,
        ]);
    }

    /**
     * Membeli paket dari aplikasi.
     *
     * PENTING:
     * Endpoint ini TIDAK lagi insert langsung ke user_packages.
     * Ia hanya membuat invoice/transaksi pending_payment.
     */
    public function buy(
        Request $request,
        TransactionService $transactionService
    ) {
        $user = $request->user();

        StudentAccess::ensureStudent(
            $user
        );

        $data = $request->validate([
            'package_id' => [
                'required',
                'integer',
                'exists:packages,id',
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

            'customer_phone' => [
                'nullable',
                'string',
                'min:9',
                'max:30',
                'regex:/^[0-9+\-\s()]+$/',
            ],
        ]);

        $package = Packages::findOrFail(
            $data['package_id']
        );

        $paymentMethod =
            PaymentMethod::query()
            ->active()
            ->findOrFail(
                $data['payment_method_id']
            );

        /*
         * Gunakan phone dari request.
         * Jika tidak dikirim, gunakan profile Student.
         */
        $user->loadMissing('student');

        $phone = trim(
            (string) (
                $data['customer_phone']
                ?? $user
                ->student
                ?->phone
                ?? ''
            )
        );

        if ($phone === '') {
            throw ValidationException::withMessages([
                'customer_phone' =>
                'Nomor WhatsApp wajib tersedia untuk membuat invoice.',
            ]);
        }

        /*
         * Jika bukti sudah dalam proses verifikasi,
         * arahkan kembali ke transaksi tersebut.
         */
        $waitingVerification =
            Transaction::query()
            ->where(
                'user_id',
                $user->id
            )
            ->where(
                'package_id',
                $package->id
            )
            ->where(
                'status',
                Transaction::STATUS_WAITING_VERIFICATION
            )
            ->latest('id')
            ->first();

        if ($waitingVerification) {
            return response()->json([
                'message' =>
                'Pembayaran paket ini sedang menunggu verifikasi.',

                'data' =>
                $this->transactionResponse(
                    $waitingVerification
                ),
            ]);
        }

        /*
         * Cegah double click / retry mobile.
         */
        $samePending =
            Transaction::query()
            ->where(
                'user_id',
                $user->id
            )
            ->where(
                'package_id',
                $package->id
            )
            ->where(
                'billing',
                $data['billing']
            )
            ->where(
                'payment_method_id',
                $paymentMethod->id
            )
            ->where(
                'status',
                Transaction::STATUS_PENDING_PAYMENT
            )
            ->where(function ($query) {
                $query
                    ->whereNull(
                        'expires_at'
                    )
                    ->orWhere(
                        'expires_at',
                        '>',
                        now()
                    );
            })
            ->latest('id')
            ->first();

        if ($samePending) {
            return response()->json([
                'message' =>
                'Invoice aktif ditemukan.',

                'data' =>
                $this->transactionResponse(
                    $samePending
                ),
            ]);
        }

        /*
         * Batalkan invoice pending lama untuk
         * user + paket yang sama.
         */
        Transaction::query()
            ->where(
                'user_id',
                $user->id
            )
            ->where(
                'package_id',
                $package->id
            )
            ->where(
                'status',
                Transaction::STATUS_PENDING_PAYMENT
            )
            ->whereNull('proof_path')
            ->update([
                'status' =>
                Transaction::STATUS_CANCELLED,

                'updated_at' =>
                now(),
            ]);

        $transaction =
            $transactionService->create(
                user: $user,

                package: $package,

                paymentMethod: $paymentMethod,

                customer: [
                    'name' =>
                    $user->name,

                    'email' =>
                    $user->email,

                    'phone' =>
                    $phone,
                ],

                billing: $data['billing']
            );

        return response()->json(
            [
                'message' =>
                'Invoice berhasil dibuat. Selesaikan pembayaran untuk mengaktifkan paket.',

                'data' =>
                $this->transactionResponse(
                    $transaction
                ),
            ],
            201
        );
    }

    private function transactionResponse(
        Transaction $transaction
    ): array {
        return [
            'invoice_no' =>
            $transaction
                ->invoice_no,

            'package_id' =>
            $transaction
                ->package_id,

            'package_name' =>
            $transaction
                ->package_name,

            'billing' =>
            $transaction
                ->billing,

            'duration_months' =>
            $transaction
                ->duration_months,

            'total' =>
            (float)
            $transaction
                ->total,

            'status' =>
            $transaction
                ->status,

            'expires_at' =>
            $transaction
                ->expires_at
                ?->toIso8601String(),

            'payment_url' =>
            route(
                'payment.show',
                [
                    'token' =>
                    $transaction
                        ->public_token,
                ]
            ),
        ];
    }
}
