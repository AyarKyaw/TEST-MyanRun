<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class TicketExport implements WithMultipleSheets
{
    protected $category;
    protected $status;
    protected $eventId; // New property

    public function __construct($category, $status = 'all', $eventId = null)
    {
        $this->category = $category;
        $this->status = $status;
        $this->eventId = $eventId;
    }

    /**
     * This creates the 3 tabs at the bottom of the Excel file
     */
    public function sheets(): array
    {
        return [
            // Pass $this->eventId as the 5th parameter to each sheet
            new TicketSheetExport($this->category, $this->status, 'all', 'All Runners', $this->eventId),
            new TicketSheetExport($this->category, $this->status, 'female', 'Female Only', $this->eventId),
            new TicketSheetExport($this->category, $this->status, 'male', 'Male Only', $this->eventId),
        ];
    }
}