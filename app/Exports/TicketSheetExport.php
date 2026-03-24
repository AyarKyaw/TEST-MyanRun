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
        $this->status = $status;
        $this->gender = $gender;
        $this->title = $title;
    }

    /**
     * Name of the Tab at the bottom
     */
    public function title(): string
    {
        return $this->title;
    }

    public function headings(): array
    {
        return ['Full Name', 'BIB Name', 'BIB Number', 'Gender', 'Category', 'T-Shirt', 'Blood', 'Price', 'Status', 'Date'];
    }

    public function collection()
    {
        $query = Ticket::query()->with(['athlete.user']);

        // 1. Filter by Category Number
        if ($this->category !== 'all') {
            $numericCategory = preg_replace('/[^0-9]/', '', $this->category);
            $query->where('category', 'LIKE', '%' . $numericCategory . '%');
        }

        // 2. Filter by Dashboard Status
        if ($this->status !== 'all') {
            $query->where('status', $this->status);
        }

        // 3. Filter by Tab Gender
        if ($this->gender !== 'all') {
            $query->whereHas('athlete', function($q) {
                $q->where('gender', 'LIKE', $this->gender);
            });
        }

        return $query->get()->map(function($t) {
            $user = $t->athlete?->user;
            $fullName = $user ? trim("{$user->first_name} {$user->mid_name} {$user->last_name}") : 'Guest';

            return [
                $fullName,
                $t->bib_name ?? 'N/A',
                $t->bib_number ?? '0000',
                ucfirst($t->athlete?->gender ?? 'N/A'),
                $t->category ?? 'N/A',
                $t->t_shirt_size ?? 'N/A',
                $t->athlete?->blood_type ?? 'N/A',
                number_format((float)$t->price) . ' MMK',
                ucfirst($t->status),
                $t->created_at ? $t->created_at->format('d/m/Y') : 'N/A',
            ];
        });
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]], // Make only the header row bold
        ];
    }
}