<?php

namespace App\Exports;

use App\Models\ItemSold;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SalesExport implements FromCollection, WithHeadings,ShouldAutoSize,WithStyles
{
    /**
    * @return \Illuminate\Support\Collection
    */


    public function __construct(Request $request)
    {
        $this->request = $request;
    }


    public function collection()
    {
      
       $startDate = Carbon::now()->toDateString();
       $endDate = Carbon::now()->toDateString();
        if($this->request->filled('startDate')&&$this->request->filled('endDate'))
        {
        $startDate = $this->request->startDate;
        $endDate = $this->request->endDate;
        }
        $itemSold = ItemSold::whereBetween('created_at',[$startDate." 00:00:00",$endDate." 23:59:59"]);
       
        
       if($this->request->filled('customer'))
       {
        $itemSold = $itemSold->where('costumer_name','like','%'.$this->request->customer.'%');
       
       }
       if($this->request->filled('receipt'))
       {
        $itemSold = $itemSold->where('receipt_number',$this->request->receipt);
      
       }
       if($this->request->filled('cashier'))
       {
        $itemSold = $itemSold->where('cashier','like','%'.$this->request->cashier.'%');
       
       }
        return $itemSold->get(['item_name','price','quantity','total','costumer_name','receipt_number','cashier','created_at']);
    }
    public function headings(): array{
        return[
            'item Name',
            'Unit Price',
            'Quantity',
            'Total',
            'Customer Name',
            'Receipt Number',
            'Cashier Name',
            'Date'

        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle(1)->getFont()->setBold(true);
        $sheet->getStyle('A')->getFont()->setBold(true);
    }
}
