{{-- ------------------- 403, forbidden ------------------- --}}
@include('errors.partials.layout', [
    'code'    => 403,
    'title'   => 'Access denied',
    'message' => 'Your account does not have permission to view that page.',
])
