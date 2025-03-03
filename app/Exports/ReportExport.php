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

class ReportExport implements FromCollection, WithHeadings
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
                "Name" => $item->name  ?? 'N/A',
                "Email" => $item->email ?? 'N/A',
                "Phone" => $item->phone ?? 'N/A',
                "Status" => $item->status ?"Active": 'Inactive',
                "Working Hours" => $item->working_hours ?? 'N/A',




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

            "Working Hours"
        ];
    }
}
