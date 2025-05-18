<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Doctor;
use App\Models\DoctorAvailability;
use App\Models\DoctorTimeSlot;

class DoctorController extends Controller
{
       public function create()
    {
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        return view('admin.doctors.create', compact('days'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'specialization' => 'required',
            'available_days' => 'required|array',
            'time_slots' => 'required|array',
        ]);

        $doctor = Doctor::create($request->only('name', 'specialization'));

        foreach ($request->available_days as $day) {
            DoctorAvailability::create([
                'doctor_id' => $doctor->id,
                'day' => $day,
            ]);
        }

        foreach ($request->time_slots as $day => $slots) {
            foreach ($slots as $slot) {
                if (!empty($slot['start']) && !empty($slot['end'])) {
                    DoctorTimeSlot::create([
                        'doctor_id' => $doctor->id,
                        'day' => $day,
                        'start_time' => $slot['start'],
                        'end_time' => $slot['end'],
                    ]);
                }
            }
        }

        return redirect()->back()->with('success', 'Doctor created successfully.');
    }
}
