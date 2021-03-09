<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Facades\App\Classes\CpTestTemplate;
use Auth;

class CpTestTemplateImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        $rows = $rows->slice(1)->all();
        
        $result = [];
        foreach ($rows as $row) {
            $index = $row[0];
            $item = [
            'type' => $row[8] == 0 ? 'a' : 'b',
            'value' => null,
            'choice' => collect($row)->slice(3, 5)->values()->toArray(),
            'category' => null,
            'question' => $row[2]
          ];

            if (isset($result[$index])) {
                ($result[$index])->push($item);
            } else {
                $result[$index] = collect([$item]);
            }
        }

        if (empty($result)) {
            return;
        }

        foreach ($result as $key=>$list) {
            $list = array_chunk($list->toArray(), 5);
            
            $cpt = data_get(CpTestTemplate::index(['cpt_order' => (int) $key]), 0, null);
            if ($cpt) {
                $params = [
                    'cpt_id' => $cpt->cpt_id ?? null,
                    'cpt_order' => (int) $key,
                    'cpt_title' => null,
                    'cpt_title_en' => null,
                    'cpt_desc' => null,
                    'cpt_title' => null,
                    'cpt_question' => json_encode($list),
                    'admin_id' => Auth::id(),
                ];

                CpTestTemplate::update($params);
            }
        }
    }
}
