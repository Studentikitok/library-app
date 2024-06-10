<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Reservation;
use Carbon\Carbon;

class UpdateStatuses extends Command
{
    protected $signature = 'reservations:update-statuses';
    protected $description = 'Обновление статусов книг и заявок';

    public function handle()
    {
        $now = Carbon::now();

        Reservation::where('end_date', '<', $now)
            ->where('status', 'accepted')
            ->update(['status' => 'expired']);

        $expiredReservations = Reservation::where('end_date', '<', $now)->get();

        foreach ($expiredReservations as $reservation) {
            $reservation->book->status = 'available';
            $reservation->book->save();
        }

        $this->info('Статусы книг и заявок обновлены.');
    }
}
