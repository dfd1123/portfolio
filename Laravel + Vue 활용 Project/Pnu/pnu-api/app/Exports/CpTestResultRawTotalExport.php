<?php

namespace App\Exports;

use Facades\App\Classes\UserCpTestResult;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;

class CpTestResultRawTotalExport implements FromCollection
{
    protected $batch_id;

    public function __construct($batch_id)
    {
        $this->batch_id = $batch_id;
    }

    public function collection()
    {
        $raw_datas = UserCpTestResult::raw_total($this->batch_id);
        $max_len = max(array_map('count', $raw_datas->toArray()));

        $result = collect([
          collect(['설문지 문항'])->concat(range(1, $max_len - 1))->toArray(),
          ['참가자번호']
        ]);
        
        foreach ($raw_datas as $raw_data) {
          $result->push($raw_data);
        }

        return new Collection($result);
    }
}
