{{-- -------------------
     Status chip. Green tones for anything settled, red for anything needing
     attention, neutral for everything in between.
------------------- --}}
@php
    $key = strtolower((string) $value);

    $good    = ['active', 'paid', 'confirmed', 'delivered', 'completed', 'accepted', 'issued', 'sent', 'success', 'subscribed', 'quoted', 'converted', 'good', 'online', 'available', 'done'];
    $bad     = ['cancelled', 'void', 'failed', 'rejected', 'expired', 'unpaid', 'overdue', 'suspended', 'inactive', 'out_of_stock', 'returned', 'damaged', 'offline'];
    $working = ['pending', 'processing', 'draft', 'under_review', 'submitted', 'in_transit', 'dispatched', 'partially_paid', 'partially_delivered', 'ready', 'new', 'read', 'short_delivered', 'on_call', 'break', 'in_progress'];

    $class = in_array($key, $good, true) ? 'nm-badge--secondary'
        : (in_array($key, $bad, true) ? 'nm-badge--primary'
        : (in_array($key, $working, true) ? 'nm-badge--tint' : 'nm-badge--outline'));
@endphp

<span class="nm-badge {{ $class }}">{{ Str::headline($value) }}</span>
