@extends('layout.main')

@section('title', 'Home')

@section('content')
    <index-component/>
@endsection

@section('scripts')
    <script src="{{ asset('js/jquery.min.js')}}"></script>
    <script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>

    {{-- custom functions --}}
    <script src="{{ asset('js/error-functions.js') }}"></script>
    <script src="{{ asset('js/functions.js') }}"></script>
@endsection