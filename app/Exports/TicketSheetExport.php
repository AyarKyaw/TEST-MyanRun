<?php

namespace App\Exports;

use App\Models\Ticket;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TicketSheetExport implements FromCollection, WithTitle, WithHeadings, ShouldAutoSize, WithStyles
{
    protected $category;
    protected $status;
    protected $gender;
    protected $title;
    protected $eventId; // Added property

    public function __construct($category, $status, $gender, $title, $eventId = null)
    {
        $this->category = $category;
        $this->status   = $status;
        $this->gender   = $gender;
        $this->title    = $title;
        $this->eventId  = $eventId; // Store the event ID
    }

    public function title(): string { return $this->title; }

    public function headings(): array
    {
        return ['Full Name', 'BIB Name', 'BIB Number', 'ID No.', 'Phone no.', "Date of Birth", 'Gender', 'Category', 'T-Shirt size', 'Blood Type', 'Price', 'Status', 'Purchase Date', 'Division', 'Nationality', 'Address'];
    }

    public function collection()
    {
        // 1. Start with Event filtering
        $query = Ticket::query()->with(['athlete.user']);

        if ($this->eventId) {
            $query->where('event_id', $this->eventId);
        }

        // 2. Filter Category
        if ($this->category !== 'all') {
            // Updated: Search for specific category name to support non-numeric event names like "Duathlon"
            $query->where('category', 'LIKE', '%' . $this->category . '%');
        }

        // 3. Filter Status
        if ($this->status !== 'all') {
            $query->where('status', $this->status);
        }

        // 4. Filter Gender
        if ($this->gender !== 'all') {
            $query->whereHas('athlete', function($q) {
                $q->where('gender', $this->gender);
            });
        }

        return $query->orderBy('created_at', 'asc')->get()->map(function($t) {
            $athlete = $t->athlete;
            $user = $athlete?->user;
            
            // Clean up name formatting
            $fullName = $user ? trim("{$user->first_name} {$user->mid_name} {$user->last_name}") : 'Guest';

            return [
                $fullName,
                $t->bib_name ?? 'N/A',
                $t->bib_number ?? '0000',
                $athlete?->id_number ?? 'N/A',
                $user?->phone ?? 'N/A',
                $athlete?->dob ?? 'N/A',
                ucfirst($athlete?->gender ?? 'N/A'),
                $t->category ?? 'N/A',
                $t->t_shirt_size ?? 'N/A',
                $athlete?->blood_type ?? 'N/A',
                number_format((float)$t->price) . ' MMK',
                ucfirst($t->status),
                $t->created_at ? $t->created_at->format('d/m/Y H:i') : 'N/A',
                $athlete?->state ?? 'N/A',
                $athlete?->nationality ?? 'N/A',
                $athlete?->address ?? 'N/A',
            ];
        });
    }

    public function styles(Worksheet $sheet)
    {
        return [1 => ['font' => ['bold' => true]]];
    }
}