<?php

namespace App\Imports;

use App\Models\CbtSoal;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CbtSoalImport implements ToModel, WithHeadingRow
{
    protected $ujian_id;

    public function __construct($ujian_id)
    {
        $this->ujian_id = $ujian_id;
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new CbtSoal([
            'ujian_id'      => $this->ujian_id,
            'pertanyaan'    => $row['pertanyaan'],
            'opsi_a'        => $row['opsi_a'],
            'opsi_b'        => $row['opsi_b'],
            'opsi_c'        => $row['opsi_c'],
            'opsi_d'        => $row['opsi_d'],
            'opsi_e'        => $row['opsi_e'] ?? null,
            'jawaban_benar' => strtoupper($row['jawaban_benar']),
            'mapel'         => $row['mapel'] ?? null,
            'kelas'         => $row['kelas'] ?? null,
        ]);
    }
}
