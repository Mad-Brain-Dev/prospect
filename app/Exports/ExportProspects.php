<?php

namespace App\Exports;

use App\Models\Suppression;
use Maatwebsite\Excel\Concerns\FromCollection;

class ExportProspects implements FromCollection
{
    protected $id;

    function __construct($id) {
        $this->id = $id;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Suppression::where('time_id',$this->id)->get();
    }
}
