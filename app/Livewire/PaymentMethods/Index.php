<?php

namespace App\Livewire\PaymentMethods;

use App\Models\PaymentMethod;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class Index extends Component
{
    use WithFileUploads;

    /*
    |--------------------------------------------------------------------------
    | Form
    |--------------------------------------------------------------------------
    */

    public ?int $editingId = null;

    public string $name = '';

    public string $type = 'bank_transfer';

    public string $provider = '';

    public string $account_name = '';

    public string $account_number = '';

    public string $instructions = '';

    public bool $requires_proof = true;

    public bool $is_active = true;

    public int $sort_order = 0;

    /*
    |--------------------------------------------------------------------------
    | QR Image
    |--------------------------------------------------------------------------
    */

    public $qr_image = null;

    public ?string $existingQrImage = null;

    /*
    |--------------------------------------------------------------------------
    | Validation
    |--------------------------------------------------------------------------
    */

    protected function rules(): array
    {
        $rules = [
            'name' => [
                'required',
                'string',
                'min:3',
                'max:100',
            ],

            'type' => [
                'required',
                Rule::in([
                    'bank_transfer',
                    'ewallet',
                    'qris',
                    'manual',
                ]),
            ],

            'provider' => [
                'nullable',
                'string',
                'max:100',
            ],

            'account_name' => [
                'nullable',
                'string',
                'max:150',
            ],

            'account_number' => [
                'nullable',
                'string',
                'max:100',
            ],

            'instructions' => [
                'nullable',
                'string',
                'max:2000',
            ],

            'requires_proof' => [
                'boolean',
            ],

            'is_active' => [
                'boolean',
            ],

            'sort_order' => [
                'integer',
                'min:0',
                'max:9999',
            ],

            'qr_image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
        ];

        /*
        |--------------------------------------------------------------------------
        | Bank / E-Wallet
        |--------------------------------------------------------------------------
        */

        if (
            in_array(
                $this->type,
                [
                    'bank_transfer',
                    'ewallet',
                ],
                true
            )
        ) {
            $rules['account_name'] = [
                'required',
                'string',
                'max:150',
            ];

            $rules['account_number'] = [
                'required',
                'string',
                'max:100',
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | QRIS
        |--------------------------------------------------------------------------
        */

        if (
            $this->type === 'qris'
            && ! $this->existingQrImage
        ) {
            $rules['qr_image'] = [
                'required',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ];
        }

        return $rules;
    }

    protected function messages(): array
    {
        return [
            'name.required' =>
            'Nama metode pembayaran wajib diisi.',

            'name.min' =>
            'Nama metode pembayaran minimal 3 karakter.',

            'type.required' =>
            'Tipe pembayaran wajib dipilih.',

            'account_name.required' =>
            'Nama pemilik rekening/akun wajib diisi.',

            'account_number.required' =>
            'Nomor rekening/nomor akun wajib diisi.',

            'qr_image.required' =>
            'Gambar QRIS wajib diupload.',

            'qr_image.image' =>
            'File QRIS harus berupa gambar.',

            'qr_image.max' =>
            'Ukuran gambar QRIS maksimal 2 MB.',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Save
    |--------------------------------------------------------------------------
    */

    public function save(): void
    {
        $validated = $this->validate();

        /*
        |--------------------------------------------------------------------------
        | QR Image
        |--------------------------------------------------------------------------
        */

        $qrPath = $this->existingQrImage;

        if ($this->qr_image) {
            /*
             * Hapus gambar lama kalau sedang edit.
             */
            if (
                $this->existingQrImage
                && Storage::disk('public')
                ->exists($this->existingQrImage)
            ) {
                Storage::disk('public')
                    ->delete($this->existingQrImage);
            }

            $qrPath = $this->qr_image->store(
                'payment-methods',
                'public'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Kalau bukan QRIS, hapus gambar lama
        |--------------------------------------------------------------------------
        */

        if ($this->type !== 'qris') {
            if (
                $qrPath
                && Storage::disk('public')
                ->exists($qrPath)
            ) {
                Storage::disk('public')
                    ->delete($qrPath);
            }

            $qrPath = null;
        }

        /*
        |--------------------------------------------------------------------------
        | Data
        |--------------------------------------------------------------------------
        */

        $data = [
            'name' =>
            trim($validated['name']),

            'type' =>
            $validated['type'],

            'provider' =>
            $this->nullableString(
                $this->provider
            ),

            'account_name' =>
            $this->nullableString(
                $this->account_name
            ),

            'account_number' =>
            $this->nullableString(
                $this->account_number
            ),

            'qr_image' =>
            $qrPath,

            'instructions' =>
            $this->nullableString(
                $this->instructions
            ),

            /*
             * Tahap ini masih manual.
             */
            'mode' =>
            'manual',

            'gateway_provider' =>
            null,

            'requires_proof' =>
            $this->requires_proof,

            'is_active' =>
            $this->is_active,

            'sort_order' =>
            $this->sort_order,
        ];

        /*
        |--------------------------------------------------------------------------
        | Create / Update
        |--------------------------------------------------------------------------
        */

        if ($this->editingId) {
            $method = PaymentMethod::findOrFail(
                $this->editingId
            );

            $method->update($data);

            session()->flash(
                'success',
                'Metode pembayaran berhasil diperbarui.'
            );
        } else {
            PaymentMethod::create($data);

            session()->flash(
                'success',
                'Metode pembayaran berhasil ditambahkan.'
            );
        }

        $this->resetForm();
    }

    /*
    |--------------------------------------------------------------------------
    | Edit
    |--------------------------------------------------------------------------
    */

    public function edit(int $id): void
    {
        $method = PaymentMethod::findOrFail($id);

        $this->editingId =
            $method->id;

        $this->name =
            (string) $method->name;

        $this->type =
            (string) $method->type;

        $this->provider =
            (string) ($method->provider ?? '');

        $this->account_name =
            (string) ($method->account_name ?? '');

        $this->account_number =
            (string) ($method->account_number ?? '');

        $this->instructions =
            (string) ($method->instructions ?? '');

        $this->requires_proof =
            (bool) $method->requires_proof;

        $this->is_active =
            (bool) $method->is_active;

        $this->sort_order =
            (int) $method->sort_order;

        $this->existingQrImage =
            $method->qr_image;

        $this->qr_image = null;

        $this->resetValidation();
    }

    /*
    |--------------------------------------------------------------------------
    | Cancel Edit
    |--------------------------------------------------------------------------
    */

    public function cancelEdit(): void
    {
        $this->resetForm();
    }

    /*
    |--------------------------------------------------------------------------
    | Toggle Active
    |--------------------------------------------------------------------------
    */

    public function toggleActive(int $id): void
    {
        $method = PaymentMethod::findOrFail($id);

        $method->update([
            'is_active' =>
            ! $method->is_active,
        ]);

        session()->flash(
            'success',
            $method->fresh()->is_active
                ? 'Metode pembayaran berhasil diaktifkan.'
                : 'Metode pembayaran berhasil dinonaktifkan.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Delete
    |--------------------------------------------------------------------------
    */

    public function delete(int $id): void
    {
        $method = PaymentMethod::withCount(
            'transactions'
        )->findOrFail($id);

        /*
         * Jangan hapus jika sudah punya histori transaksi.
         * Cukup nonaktifkan.
         */
        if ($method->transactions_count > 0) {
            session()->flash(
                'error',
                'Metode pembayaran sudah pernah digunakan dalam transaksi. '
                    . 'Silakan nonaktifkan, bukan menghapusnya.'
            );

            return;
        }

        if (
            $method->qr_image
            && Storage::disk('public')
            ->exists($method->qr_image)
        ) {
            Storage::disk('public')
                ->delete($method->qr_image);
        }

        $method->delete();

        if ($this->editingId === $id) {
            $this->resetForm();
        }

        session()->flash(
            'success',
            'Metode pembayaran berhasil dihapus.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Reset Form
    |--------------------------------------------------------------------------
    */

    private function resetForm(): void
    {
        $this->reset([
            'editingId',
            'name',
            'provider',
            'account_name',
            'account_number',
            'instructions',
            'qr_image',
            'existingQrImage',
        ]);

        $this->type =
            'bank_transfer';

        $this->requires_proof =
            true;

        $this->is_active =
            true;

        $this->sort_order =
            0;

        $this->resetValidation();
    }

    private function nullableString(
        ?string $value
    ): ?string {
        $value = trim(
            (string) $value
        );

        return $value === ''
            ? null
            : $value;
    }

    /*
    |--------------------------------------------------------------------------
    | Render
    |--------------------------------------------------------------------------
    */

    public function render()
    {
        return view(
            'livewire.payment-methods.index',
            [
                'methods' =>
                PaymentMethod::query()
                    ->withCount('transactions')
                    ->ordered()
                    ->get(),

                'totalActive' =>
                PaymentMethod::active()
                    ->count(),

                'totalMethods' =>
                PaymentMethod::count(),
            ]
        )->layout('layouts.admin');
    }
}
