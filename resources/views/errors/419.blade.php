{{-- ------------------- 419, expired session ------------------- --}}
@include('errors.partials.layout', [
    'code'    => 419,
    'title'   => 'Your session expired',
    'message' => 'Please sign in again to continue.',
])
