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
            return response()->json(['message' => 'Книга не может быть зарезервирована'], 400);
        }

        $reservation = Reservation::create([
            'book_id' => $book->id,
            'user_id' => Auth::id(),
            'status' => 'pending',
            'start_date' => null,
            'end_date' => null,
        ]);

        $book->status = 'reserved';
        $book->save();

        return response()->json(['message' => 'Заявка на бронирование отправлена.', 'reservation' => $reservation], 201);
    }

    public function updateReservationStatus(Request $request, $reservationId)
    {
        $reservation = Reservation::findOrFail($reservationId);
        $status = $request->input('status');

        if ($status === 'approved') {
            $reservation->book->status = 'reserved';
            $reservation->start_date = Carbon::now();
            $reservation->end_date = Carbon::now()->addWeek();
        } elseif ($status === 'rejected') {
            $reservation->book->status = 'available';
        }

        $reservation->status = $status;
        $reservation->save();

        $reservation->book->save();

        return response()->json(['message' => 'Статус заявки обновлён', 'reservation' => $reservation]);
    }
}