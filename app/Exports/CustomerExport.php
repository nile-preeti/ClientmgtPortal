<?php


namespace App\Exports;

use App\Enums\RideStatus;
use App\Models\Address;
use App\Models\DriverDetail;
use App\Models\DriverRide;
use App\Models\PaymentDetail;
use App\Models\Review;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CustomerExport implements FromCollection, WithHeadings
{
    protected $users;

    public function __construct($users)
    {
        $this->users = $users;
    }
    public function collection()
    {
        // Fetch data from the database


        $modifiedData = $this->users->map(function ($item, $_i) {


            return [
                "ID" => $item->id,
                "Name" => $item->first_name . ' ' . $item->last_name ?? 'N/A',
                "Email" => $item->email ?? 'N/A',
                "Phone" => $item->mobile_phone ?? 'N/A',
                "Status" => $item->status ?? 'N/A',
                "Address" => $item->full_address ?? 'N/A',




            ];
        });

        return $modifiedData;
    }

    public function headings(): array
    {
        return [
            "ID",
            "Name",
            "Email",
            "Phone",
            "Status",
            "Address"
        ];
    }
}
