<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Doctor;
use App\Models\DoctorTimeSlot;
use App\Models\Appointment;
use Carbon\Carbon;

class AppointmentController extends Controller
{
    public function index()
    {
        $doctors = Doctor::all();
        return view('user.appointments.index', compact('doctors'));
    }

    public function getSlots($doctor_id, Request $request)
    {

        $date = $request->query('date');
        $dayName = Carbon::parse($date)->format('l');
        $slots = DoctorTimeSlot::where('doctor_id', $doctor_id)->where('day', $dayName)->get();
        $availableSlots = $slots->filter(function ($slot) use ($doctor_id, $date) {
            $isBooked = Appointment::where('doctor_id', $doctor_id)
                        ->where('appointment_date', $date)
                        ->where('start_time', $slot->start_time)
                        ->exists();

            $isPast = Carbon::parse($date . ' ' . $slot->start_time)->isPast();
            return !$isBooked && !$isPast;
        });

        return response()->json($availableSlots->values());
    }

    public function book(Request $request)
    {
        $request->validate([
            'doctor_id' => 'required',
            'appointment_date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
            'patient_name' => 'required|string',
            'patient_email' => 'required|email',
        ]);

        $exists = Appointment::where('doctor_id', $request->doctor_id)
            ->where('appointment_date', $request->appointment_date)
            ->where('start_time', $request->start_time)
            ->exists();

        if ($exists) {
            return back()->with('error', 'Slot already booked!');
        }

        Appointment::create($request->all());

        return back()->with('success', 'Appointment booked successfully!');
    }
}
