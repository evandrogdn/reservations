<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Reservation;

class ReservationController extends Controller
{
    /**
     * @return Collection<int, Reservation>
     */
    public function index(): Collection
    {
        return Reservation::with('tables')->get();
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return Reservation
     */
    public function store(Request $request): Reservation 
    {
        $data = $request->validate([
            'client_name' => 'required|string',
            'table_id' => 'required|exists:tables,id',
            'phone_contact' => 'string|nullable',
            'email_contact' => 'string|nullable',
            'guests' => 'integer|required',
            'reservation_start' => 'required|date',
            'reservation_end' => 'required|date',
        ]);

        return Reservation::create($data);
    }

    /**
     * @param \App\Models\Reservation $reservation
     * @return Reservation
     */
    public function show(Reservation $reservation): Reservation
    {
        return $reservation->load('tables');
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Reservation $reservation
     * @return Reservation
     */
    public function update(Request $request, Reservation $reservation): Reservation
    {
        $data = $request->validate([
            'client_name' => 'sometimes|required|string',
            'table_id' => 'sometimes|required|exists:tables,id',
            'phone_contact' => 'sometimes|nullable|string',
            'email_contact' => 'sometimes|nullable|string',
            'guests' => 'sometimes|required|integer',
            'reservation_start' => 'sometimes|required|date',
            'reservation_end' => 'sometimes|required|date',
        ]);

        $reservation->update($data);
        return $reservation;
    }

    /**
     * @param \App\Models\Reservation $reservation
     * @return JsonResponse|mixed
     */
    public function destroy(Reservation $reservation): JsonResponse
    {
        $reservation->delete();

        return response()->json(['message' => 'Reserva removida com sucesso']);
    }
}
