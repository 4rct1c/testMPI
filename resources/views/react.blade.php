@extends('layouts/app')
@section('content')
    <div id="application"></div>
@endsection
@section('scripts')
    <script>
        const role = @json($role);
        const apiRoutes = @json($apiRoutes);
    </script>
@endsection
