@extends('layouts.app')
@section('content')
    <h1>Your Weekly Reports</h1>
    <ul>
        @foreach($files as $file)
            <li>
                <a href="{{ route('reports.weekly.download', ['filename' => basename($file)]) }}">
                    {{ basename($file) }}
                </a>
            </li>
        @endforeach
    </ul>
@endsection 