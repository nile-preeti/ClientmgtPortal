<?php


namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ReportExport implements FromCollection, WithHeadings, WithStyles, WithEvents
{
    protected $users;
    protected $month;
    protected $year;

    public function __construct($users, $month, $year)
    {
        $this->users = $users;
        $this->month = $month;
        $this->year = $year;
    }

    public function collection()
    {
        $data = [];
        //dd($this->month);
        foreach ($this->users as $user) {
            // Get services for the user
            $services = \App\Models\JobSchedule::with(['service', 'subCategory'])
                ->where('user_id', $user->id)
                ->where('status', 2)
                ->whereMonth('start_date', $this->month)
                ->whereYear('start_date', $this->year)
                ->get();
            
            // Format services
            $serviceDetails = [];
            foreach ($services as $service) {
                $hours = $this->getTotalWorkingHoursPerService($user->id, $service->id, $this->month, $this->year);
                $serviceDetails[] = 
                    ($service->service->name ?? 'N/A') . ' - ' . 
                    ($service->subCategory->sub_category ?? 'N/A') . ' - ' . 
                    $hours . ' hrs';
            }
            $servicesText = implode("\n", $serviceDetails);
            
            // Get weekly hours
            $weeklyRequest = new \Illuminate\Http\Request([
                'month' => $this->month,
                'year' => $this->year
            ]);
            $weeklyResponse = app()->call(
                [app()->make('App\Http\Controllers\AdminController'), 'getWeeklyWorkingHours'], 
                ['userId' => $user->id, 'request' => $weeklyRequest]
            );
            $weeklyHours = json_decode($weeklyResponse->getContent(), true);
            
            $data[] = [
                'Name' => $user->name,
                'Email' => $user->email,
                'Phone' => $user->phone,
                'Services' => $servicesText,
                'Total Working Hours' => $user->working_hours .' hrs',
                'Week 1' => ($weeklyHours[0] ?? 0) . ' hrs',
                'Week 2' => ($weeklyHours[1] ?? 0) . ' hrs',
                'Week 3' => ($weeklyHours[2] ?? 0) . ' hrs',
                'Week 4' => ($weeklyHours[3] ?? 0) . ' hrs',
                'Week 5' => ($weeklyHours[4] ?? 0) . ' hrs',
            ];
        }
        
        return collect($data);
    }

    public function headings(): array
    {
        return [
            'Name',
            'Email',
            'Phone',
            'Services (Name - Category - Hours)',
            'Total Working Hours',
            'Week 1 Hours',
            'Week 2 Hours',
            'Week 3 Hours',
            'Week 4 Hours',
            'Week 5 Hours'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text
            1 => ['font' => ['bold' => true]],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                // Enable wrap text for the Services column (column E)
                $servicesRange = 'D2:D' . $event->sheet->getHighestRow();
                $event->sheet->getStyle($servicesRange)->getAlignment()->setWrapText(true);
                
                // Auto-size columns for better display
                foreach (range('A', 'J') as $col) {
                    $event->sheet->getColumnDimension($col)->setAutoSize(true);
                }
            },
        ];
    }

    protected function getTotalWorkingHoursPerService($userId, $jobId, $month, $year)
    {
        // Copy your existing getTotalWorkingHoursPerService logic here
        $attendanceRecords = \App\Models\Attendance::with('attendanceBreaks')
            ->where('user_id', $userId)
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->where('job_id', $jobId)
            ->get();

        $totalWorkingHours = 0;

        foreach ($attendanceRecords as $record) {
            if ($record->check_in_time && $record->check_out_time) {
                $checkIn = strtotime($record->check_in_time);
                $checkOut = strtotime($record->check_out_time);

                $workedSeconds = $checkOut - $checkIn;
                $breakSeconds = 0;

                foreach ($record->attendanceBreaks as $break) {
                    if ($break->start_break && $break->end_break) {
                        $breakSeconds += strtotime($break->end_break) - strtotime($break->start_break);
                    }
                }

                $netSeconds = max(0, $workedSeconds - $breakSeconds);
                $totalWorkingHours += $netSeconds / 3600;
            }
        }

        return number_format($totalWorkingHours, 2);
    }
}

