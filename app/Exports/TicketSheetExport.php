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

    public function __construct($category, $status, $gender, $title)
    {
        $this->category = $category;
        $this->status   = $status;
        $this->gender   = $gender;
        $this->title    = $title;
    }

    public function title(): string { return $this->title; }

    public function headings(): array
    {
        return ['Full Name', 'BIB Name', 'BIB Number', 'ID No.', 'Phone no.', "Date of Birth", 'Gender', 'Category', 'T-Shirt size', 'Blood Type', 'Price', 'Status', 'Purchase Date', 'Division', 'Address'];
    }

    public function collection()
    {
        $query = Ticket::query()->with(['athlete.user']);

        // Filter Category
        if ($this->category !== 'all') {
            $numeric = preg_replace('/[^0-9]/', '', $this->category);
            $query->where('category', 'LIKE', '%' . $numeric . '%');
        }

        // Filter Status
        if ($this->status !== 'all') {
            $query->where('status', $this->status);
        }

        // Filter Gender
        if ($this->gender !== 'all') {
            $query->whereHas('athlete', function($q) {
                $q->where('gender', $this->gender);
            });
        }

        return $query->orderBy('created_at', 'asc')->get()->map(function($t) {
            $athlete = $t->athlete;
            $user = $athlete?->user;
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
                $athlete?->address ?? 'N/A',
            ];
        });
    }

    public function styles(Worksheet $sheet)
    {
        return [1 => ['font' => ['bold' => true]]];
    }
}