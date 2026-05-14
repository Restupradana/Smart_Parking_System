@extends('layouts.app')

@section('title', 'Edit Slot Parkir')

@section('content')
<h2 class="text-2xl font-bold mb-4">Edit Slot Parkir</h2>

<form action="{{ route('admin.parking-slots.update', $parkingSlot->id) }}" method="POST" class="bg-white p-4 shadow rounded">
    @csrf
    @method('PUT')

    <div class="mb-4">
        <label class="block mb-1">Nomor Slot</label>
        <input type="text" name="slot_number" value="{{ $parkingSlot->slot_number }}" class="border p-2 rounded w-full" required>
    </div>

    <div class="mb-4">
        <label class="block mb-1">Area Parkir</label>
        <select name="area_id" class="border p-2 rounded w-full" required>
            @foreach($areas as $area)
                <option value="{{ $area->id }}" {{ $parkingSlot->area_id == $area->id ? 'selected' : '' }}>
                    {{ $area->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="mb-4">
        <label class="block mb-1">Status</label>
        <select name="status" class="border p-2 rounded w-full" required>
            <option value="kosong" {{ $parkingSlot->status == 'kosong' ? 'selected' : '' }}>Kosong</option>
            <option value="terisi" {{ $parkingSlot->status == 'terisi' ? 'selected' : '' }}>Terisi</option>
        </select>
    </div>

    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
        Simpan
    </button>
</form>
@endsection