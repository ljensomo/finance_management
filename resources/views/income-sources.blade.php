@extends('layout.main')

@section('title', 'Income Sources')

@section('content')
    <income-source-component/>
@endsection

@section('scripts')
    <script src="{{ asset('js/jquery.min.js')}}"></script>
    <script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>

    {{-- custom functions --}}
    <script src="{{ asset('js/error-functions.js') }}"></script>
    <script src="{{ asset('js/functions.js') }}"></script>
@endsection