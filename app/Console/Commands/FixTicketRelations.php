<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class FixTicketRelations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:tickets';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tickets = \App\Models\Ticket::all();

        foreach ($tickets as $ticket) {

            $event = \App\Models\Event::where('name', $ticket->event)->first();

            if (!$event) {
                $this->error("Event not found for ticket ID: {$ticket->id}");
                continue;
            }

            $type = \App\Models\EventTicketType::firstOrCreate([
                'event_id' => $event->id,
                'name' => $ticket->category,
            ], [
                'type' => 'solo',
                'price' => $ticket->price,
            ]);

            $ticket->event_id = $event->id;
            $ticket->ticket_type_id = $type->id;
            $ticket->save();

            $this->info("Updated ticket ID: {$ticket->id}");
        }

        $this->info("✅ All tickets processed!");
    }
}
