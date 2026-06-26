<div class="grid grid-cols-5 gap-4">
    @foreach($this->columns as $col)
        <div class="rounded-lg bg-white/80 p-3 border border-gray-100">
            <div class="text-sm font-semibold mb-3">{{ $col['status'] }}</div>
            <div class="space-y-3">
                @forelse($col['items'] as $item)
                    <a href="{{ $item['url'] }}" class="block rounded-md p-3 bg-white shadow-sm hover:shadow-md transition no-underline text-sm text-text-dark">
                        <div class="font-semibold">{{ $item['title'] }}</div>
                        <div class="text-xs text-text-mid">{{ $item['candidate'] }} · {{ $item['when'] }}</div>
                    </a>
                @empty
                    <div class="text-xs text-text-light p-3">No items</div>
                @endforelse
            </div>
        </div>
    @endforeach
</div>
