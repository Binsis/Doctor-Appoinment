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

        // to check the user is already booked an appoinment with other doctor
        $newStart = Carbon::parse("{$request->appointment_date} {$request->start_time}");
        $newEnd = Carbon::parse("{$request->appointment_date} {$request->end_time}");

        $checkTime = Appointment::where('appointment_date', $request->appointment_date)
        ->where('patient_email', $request->patient_email)
        ->get()
        ->first(function ($appointment) use ($newStart, $newEnd) {
            $existingStart = Carbon::parse("{$appointment->appointment_date} {$appointment->start_time}");
            $existingEnd = Carbon::parse("{$appointment->appointment_date} {$appointment->end_time}");
            return $newStart < $existingEnd && $newEnd > $existingStart;
        });

    if ($checkTime) {
        $doctor = Doctor::find($checkTime->doctor_id);
        $dayName = Carbon::parse($checkTime->appointment_date)->format('l');
        $start = Carbon::parse($checkTime->start_time)->format('h:i A');
        $end = Carbon::parse($checkTime->end_time)->format('h:i A');

        return back()->with('error', "You already booked an appointment with {$doctor->name} on {$dayName} from {$start} to {$end}.");
    }

        // to check the slot already booked
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
