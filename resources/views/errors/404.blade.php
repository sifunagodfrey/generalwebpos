{{-- ------------------- 404, page not found ------------------- --}}
@include('errors.partials.layout', [
    'code'    => 404,
    'title'   => 'We cannot find that page',
    'message' => 'The page has been moved or the address is wrong.',
])
