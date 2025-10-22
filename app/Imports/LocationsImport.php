<?php

namespace App\Imports;

use App\Models\Location;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Illuminate\Validation\Rule;

class LocationsImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnError
{
    use SkipsErrors;

    /**
     * Mapping Excel ke Database:
     * - Excel: nama, kel, kode
     * - Database: name, floor, description
     * 
     * @param array $row
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // Mapping Excel ke Database
        return new Location([
            'name'        => $row['nama'] ?? null,                    // Kolom 'nama' dari Excel
            'floor'       => $row['kel'] ?? null,                     // Kolom 'kel' dari Excel jadi 'floor'
            'description' => isset($row['kode']) ? 'Kode Ruangan: ' . $row['kode'] : null, // Kolom 'kode' jadi description
        ]);
    }

    /**
     * Validation rules
     */
    public function rules(): array
    {
        return [
            'nama' => 'required|string|max:255',
            'kel'  => 'nullable|string|max:50',
            'kode' => 'nullable',
        ];
    }

    /**
     * Custom validation messages
     */
    public function customValidationMessages()
    {
        return [
            'nama.required' => 'Nama unit wajib diisi pada baris :row',
            'nama.max'      => 'Nama unit maksimal 255 karakter pada baris :row',
        ];
    }
}