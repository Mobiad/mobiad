<?php

namespace App\Exports;

use App\Customer;
use Illuminate\Support\Arr;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;


class AllCustomersExport implements WithColumnFormatting, FromCollection, WithHeadings, WithMapping
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Customer::all();
    }

    public function headings(): array
    {
        return [
            'Name',
            'Phone Number',
            'business Name',
            'business Description(SCRIPT)',
            'Service Period',
            'Subscribers Numbers',
            'Tune Production Status',
            'Voice',
            'Ref #',
            'Created At',
        ];
    }

    /**
     * @var Customer $customer
     */
    public function map($customer): array
    {
        $subscribers = Arr::pluck($customer->subscriber_numbers, "value");

        return [
            $customer->fullname,
            $customer->phone,
            $customer->businessname,
            $customer->businessdesc,
            $customer->subscription_period,
            implode(",", $subscribers),
            $customer->tune ? "Yes":"No",
            $customer->voice,
            $customer->ref,
            $customer->created_at,
        ];
    }

    public function columnFormats(): array
    {
        return [
            'C' => NumberFormat::FORMAT_NUMBER,
        ];
    }
}
