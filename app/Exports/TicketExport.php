<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class TicketExport implements WithMultipleSheets
{
    protected $category;
    protected $status;
    protected $printStatus; // New property
    protected $eventId;

    public function __construct($category, $status = 'all', $printStatus = 'all', $eventId = null)
    {
        $this->category = $category;
        $this->status = $status;
        $this->printStatus = $printStatus; // Initialize
        $this->eventId = $eventId;
    }

    public function sheets(): array
    {
        // Pass the new $this->printStatus parameter down to the sheets
        return [
            new TicketSheetExport($this->category, $this->status, $this->printStatus, 'all', 'All Runners', $this->eventId),
            new TicketSheetExport($this->category, $this->status, $this->printStatus, 'female', 'Female Only', $this->eventId),
            new TicketSheetExport($this->category, $this->status, $this->printStatus, 'male', 'Male Only', $this->eventId),
        ];
    }
}