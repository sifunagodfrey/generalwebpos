{{-- ------------------- 503, maintenance ------------------- --}}
@include('errors.partials.layout', [
    'code'    => 503,
    'title'   => 'Down for maintenance',
    'message' => 'We are making some improvements. Please check back shortly.',
])
