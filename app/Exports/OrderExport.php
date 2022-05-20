<?php

namespace App\Exports;

use App\Models\SchedulerCustomer;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;


class OrderExport implements FromView, ShouldAutoSize, WithEvents
{
    public function __construct($order = null)
    {
        $this->order = $order;
    }

    public function view(): View
    {
        return view('Reports.NewExcelReport', [
            'result' => $this->order
        ]);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event) {
                $cellRange = 'A1:W1'; // All headers
                // $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(14)->setBold($cellRange);
                $event->sheet->getStyle('A:Z')->getAlignment()->setVertical('top');
            },
        ];
    }

    // public function registerEvents(): array
    // {
    //     return [
    //         // array callable, refering to a static method.
    //         AfterSheet::class => [self::class, 'afterSheet'],
    //     ];
    // }

    // public static function afterSheet(AfterSheet $event)
    // {
    //     Sheet::macro('styleCells', function (Sheet $sheet, string $cellRange, array $style) {
    //         $sheet->getDelegate()->getStyle($cellRange)->applyFromArray($style);
    //     });

    //     $event->sheet->styleCells('A:A', [
    //         'alignment' => [
    //             'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
    //         ],
    //     ]);
    // }
}
