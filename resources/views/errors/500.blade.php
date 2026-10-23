{{-- ------------------- 500, server error ------------------- --}}
@include('errors.partials.layout', [
    'code'    => 500,
    'title'   => 'Something went wrong',
    'message' => 'An unexpected error occurred. It has been logged and will be looked into.',
])
