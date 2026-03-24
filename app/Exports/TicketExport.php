<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class TicketExport implements WithMultipleSheets
{
    protected $category;
    protected $status;

    public function __construct($category, $status = 'all')
    {
        $this->category = $category;
        $this->status = $status;
    }

    /**
     * This creates the 3 tabs at the bottom of the Excel file
     */
    public function sheets(): array
    {
        return [
            new TicketSheetExport($this->category, $this->status, 'all', 'All Runners'),
            new TicketSheetExport($this->category, $this->status, 'female', 'Female Only'),
            new TicketSheetExport($this->category, $this->status, 'male', 'Male Only'),
        ];
    }
}