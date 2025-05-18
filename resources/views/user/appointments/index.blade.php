@extends('user.layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Book an Appointment</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form id="bookingForm" method="POST" action="{{ route('appointments.book') }}">
        @csrf

        <div class="mb-3">
            <label for="doctorSelect" class="form-label">Doctor</label>
            <select name="doctor_id" id="doctorSelect" class="form-select" required>
                <option value="">-- Select Doctor --</option>
                @foreach($doctors as $doctor)
                    <option value="{{ $doctor->id }}">
                        {{ $doctor->name }} ({{ $doctor->specialization }})
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="appointmentDate" class="form-label">Date</label>
            <input type="date" name="appointment_date" id="appointmentDate"
                class="form-control"
                min="{{ now()->toDateString() }}"
                max="{{ now()->addDays(30)->toDateString() }}"
                required>
        </div>

        <div class="mb-3">
            <label for="slotSelect" class="form-label">Available Time Slots</label>
            <select name="start_time" id="slotSelect" class="form-select" required></select>
            <input type="hidden" name="end_time" id="endTime">
        </div>

        <div class="mb-3">
            <label for="patient_name" class="form-label">Your Name</label>
            <input type="text" name="patient_name" id="patient_name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="patient_email" class="form-label">Your Email</label>
            <input type="email" name="patient_email" id="patient_email" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">Book Appointment</button>
    </form>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function () {
    function loadSlots() {
        let doctorId = $('#doctorSelect').val();
        let date = $('#appointmentDate').val();
        let $slotSelect = $('#slotSelect');
        let $endTime = $('#endTime');

        if (doctorId && date) {
            $.ajax({
                url: `/doctor/${doctorId}/slots`,
                type: 'GET',
                data: { date: date },
                success: function (data) {
                    $slotSelect.empty();
                    $.each(data, function (index, slot) {
                        let option = $('<option></option>')
                            .val(slot.start_time)
                            .text(`${slot.start_time} - ${slot.end_time}`)
                            .attr('data-end', slot.end_time);
                        $slotSelect.append(option);
                    });

                    if (data.length > 0) {
                        $endTime.val(data[0].end_time);
                    } else {
                        $endTime.val('');
                    }
                }
            });
        }
    }

    $('#appointmentDate, #doctorSelect').on('change', loadSlots);

    $('#slotSelect').on('change', function () {
        let endTime = $(this).find(':selected').data('end');
        $('#endTime').val(endTime);
    });
});
</script>
@endpush
