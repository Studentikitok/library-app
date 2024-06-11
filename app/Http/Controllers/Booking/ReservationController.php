<?php

namespace App\Http\Controllers\Booking;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Models\Book;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class ReservationController extends Controller
{
    public function reserveBook(Request $request, $bookId)
    {
        $book = Book::findOrFail($bookId);

        if ($book->status !== 'available') {
            return response()->json(['message' => 'Book is not available for reservation'], 400);
        }

        $reservation = Reservation::create([
            'book_id' => $book->id,
            'user_id' => Auth::id(),
            'status' => 'pending',
            'start_date' => Carbon::now(),
            'end_date' => Carbon::now()->addWeek(),
        ]);

        $book->status = 'reserved';
        $book->save();

        return response()->json(['message' => 'Заявка на бронирование отправлена.', 'reservation' => $reservation], 201);
    }

    public function updateReservationStatus(Request $request, $reservationId)
    {
        $reservation = Reservation::findOrFail($reservationId);
        $status = $request->input('status');

        if ($status === 'rejected') {
            $reservation->start_date = null;
            $reservation->end_date = null;
        }

        if ($status === 'accepted') {
            $reservation->book->status = 'reserved';
        } elseif ($status === 'rejected') {
            $reservation->book->status = 'available';
        }

        $reservation->status = $status;
        $reservation->save();

        $reservation->book->save();

        return response()->json(['message' => 'Статус заявки обновлён.', 'reservation' => $reservation]);
    }

    public function autoUpdateStatuses()
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

        return response()->json(['message' => 'Статусы книг и заявок обновлены.']);
    }
}