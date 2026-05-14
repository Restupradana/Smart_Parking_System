<div class="grid grid-cols-2 md:grid-cols-6 gap-4">

@foreach($slots as $slot)
<div class="p-4 rounded-xl text-center text-white shadow
    {{ $slot->status == 'kosong' ? 'bg-green-500' : 'bg-red-500' }}">

    <div class="font-bold text-lg">
        {{ $slot->slot_number }}
    </div>

    <div class="text-sm mt-1">
        {{ $slot->area->location ?? '-' }}
    </div>

    <div class="text-xs mt-1">
        {{ $slot->status == 'kosong' ? 'Kosong' : 'Terisi' }}
    </div>

</div>
@endforeach

</div>