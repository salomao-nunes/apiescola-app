<?php

namespace App\Imports;

use App\Models\Escola;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class EscolaImport implements ToCollection
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        //

        // dd($rows);

    }
}
