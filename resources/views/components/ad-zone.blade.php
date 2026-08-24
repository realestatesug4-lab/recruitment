<div class="ad-zone ad-zone--{{ $slotClass ?: 'inline' }} ad-zone-{{ $zone->slug }}"
     data-zone-id="{{ $zone->id }}"
     data-zone-slug="{{ $zone->slug }}"
     data-revive-zone-id="{{ $zone->revive_zone_id }}"
     data-zone-width="{{ $zone->width }}"
     data-zone-height="{{ $zone->height }}"
     data-zone-device="{{ $zone->device_type }}"
     @if($context) data-context="{{ json_encode($context) }}" @endif>

    {{-- Revive Adserver Invocation Code --}}
    @if($invocationCode)
        {!! $invocationCode !!}
    @else
        {{-- Placeholder while the zone is unmapped or unavailable --}}
        <div class="ad-placeholder"
             style="width: {{ $zone->width }}px; height: {{ $zone->height }}px;
                    background: #f0f0f0; display: flex; align-items: center; justify-content: center;
                    border: 2px dashed #ccc; color: #999; font-size: 12px;">
            <span>{{ $zone->name }} ({{ $zone->getDimensions() }})</span>
        </div>
    @endif
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Track ad impressions if needed
    const adZone = document.querySelector('[data-zone-slug="{{ $zone->slug }}"]');
    if (adZone) {
        // Initialize lazy loading if needed
        // Track viewing for analytics
    }
});
</script>
@endpush
