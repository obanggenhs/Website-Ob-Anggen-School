<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class RegistrationField extends Model
{
    public const TYPES = [
        'text'     => 'Teks singkat',
        'textarea' => 'Teks panjang',
        'email'    => 'Email',
        'tel'      => 'Nomor HP',
        'number'   => 'Angka',
        'date'     => 'Tanggal',
        'select'   => 'Pilihan (dropdown)',
        'radio'    => 'Pilihan (tombol radio)',
        'file'     => 'Unggah dokumen',
    ];

    public const SECTIONS = [
        'data_diri' => 'Data diri',
        'orang_tua' => 'Data orang tua / wali',
        'lainnya'   => 'Informasi lain',
        'dokumen'   => 'Dokumen persyaratan',
    ];

    protected $fillable = [
        'registration_type_id', 'label', 'name', 'type', 'section', 'options', 'placeholder',
        'help_text', 'is_required', 'width', 'position',
    ];

    protected $casts = ['is_required' => 'boolean'];

    public function type()
    {
        return $this->belongsTo(RegistrationType::class, 'registration_type_id');
    }

    public function optionList(): array
    {
        return lines($this->options);
    }

    /** Aturan validasi Laravel untuk field ini. */
    public function rules(): array
    {
        $rules = [$this->is_required ? 'required' : 'nullable'];

        return array_merge($rules, match ($this->type) {
            'email'          => ['email', 'max:150'],
            'tel'            => ['string', 'max:30'],
            'number'         => ['numeric'],
            'date'           => ['date'],
            'textarea'       => ['string', 'max:3000'],
            'select', 'radio' => [Rule::in($this->optionList())],
            'file'           => ['file', 'mimes:pdf,jpg,jpeg,png,webp', 'max:4096'],
            default          => ['string', 'max:255'],
        });
    }
}
