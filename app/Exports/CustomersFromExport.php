<?php

namespace App\Exports;

use App\Customer;
use Illuminate\Support\Arr;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;

class CustomersFromExport implements WithColumnFormatting, FromQuery,  WithHeadings, WithMapping
{
    use Exportable;

    public function __construct($from, $to)
    {
        $this->from = $from;
        $this->to = $to;
    }

    public function query()
    {
        return Customer::query()->whereBetween(
            'created_at',
            [$this->from, $this->to]
        );
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
