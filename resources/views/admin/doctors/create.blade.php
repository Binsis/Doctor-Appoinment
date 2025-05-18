@extends('admin.layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Create Doctor</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('doctors.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label class="form-label">Name:</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Specialization:</label>
            <input type="text" name="specialization" class="form-control" required>
        </div>

        <h5 class="mt-4">Select Available Days:</h5>
        <div class="mb-3">
            @foreach($days as $day)
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" name="available_days[]" value="{{ $day }}" id="day_{{ $day }}">
                    <label class="form-check-label" for="day_{{ $day }}">{{ $day }}</label>
                </div>
            @endforeach
        </div>

        <h5 class="mt-4">Time Slots (per day):</h5>
        @foreach($days as $day)
            <div class="mb-3">
                <label class="form-label fw-bold">{{ $day }}</label>
                <div id="slots-{{ $day }}">
                    <div class="d-flex gap-2 mb-2">
                        <input type="time" class="form-control" name="time_slots[{{ $day }}][0][start]">
                        <input type="time" class="form-control" name="time_slots[{{ $day }}][0][end]">
                    </div>
                </div>
                <button type="button" class="btn btn-sm btn-outline-primary" onclick="addSlot('{{ $day }}')">Add Slot</button>
            </div>
        @endforeach

        <button type="submit" class="btn btn-success mt-4">Save Doctor</button>
    </form>
</div>

<script>
function addSlot(day) {
    let $container = $('#slots-' + day);
    let index = $container.children().length;

    let $div = $(`
        <div class="d-flex gap-2 mb-2">
            <input type="time" class="form-control" name="time_slots[${day}][${index}][start]">
            <input type="time" class="form-control" name="time_slots[${day}][${index}][end]">
        </div>
    `);

    $container.append($div);
}
</script>
@endsection
