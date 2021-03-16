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

class CustomerExport implements WithColumnFormatting, FromQuery,  WithHeadings, WithMapping
{
    use Exportable;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function query()
    {
        return Customer::query()->where(
            'id',
            $this->id
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
            $customer->tune ? "Yes" : "No",
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
