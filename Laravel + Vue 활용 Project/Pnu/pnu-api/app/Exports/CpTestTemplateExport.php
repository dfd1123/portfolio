<?php

namespace App\Exports;

use Facades\App\Classes\CpTestTemplate;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class CpTestTemplateExport implements FromCollection, WithEvents
{
    public function collection()
    {
        $params = [
            'cpt_id' => null,
            'cpt_order' => null,
            'offset' => null,
            'limit' => null
        ];

        $cpts = CpTestTemplate::index($params);
        $result = collect([['핵심역량번호', '문항번호', '측정문항', '응답1(1점)', '응답2(2점)', '응답3(3점)', '응답4(4점)', '응답5(5점)', '역코딩 여부']]);
        $index = 1;
        foreach ($cpts as $cpt) {
            $pages = json_decode($cpt->cpt_question);

            foreach ($pages as $page) {
                foreach ($page as $question) {
                    if(isset($question->choice)) {
                      $choices = $question->choice;
                    } else {
                      $choices = ['', '', '', '', '',];
                    }

                    $result->push([
                      $cpt->cpt_order,
                      $index++,
                      $question->question,
                      $choices[0],
                      $choices[1],
                      $choices[2],
                      $choices[3],
                      $choices[4],
                      $question->type == 'a' ? '0' : '1'
                    ]);
                }
            }
        }

        return $result;
    }

    /**
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $columns = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I'];

                foreach ($columns as $column) {
                  if($column == 'C') {
                    $event->sheet->getDelegate()->getColumnDimension($column)->setWidth(75);
                  } else {
                    $event->sheet->getDelegate()->getColumnDimension($column)->setWidth(15);
                  }
                }
            },
        ];
    }
}
