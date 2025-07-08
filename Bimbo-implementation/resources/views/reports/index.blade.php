@extends('layouts.dashboard')

@section('content')
    <h2 class="text-2xl font-bold mb-4">Your Reports</h2>

    <div class="mb-8">
        <h3 class="text-xl font-semibold mb-2">Daily Reports</h3>
        @if($dailyReports->isEmpty())
            <p>No daily reports found.</p>
        @else
            <ul class="list-disc pl-6">
                @foreach($dailyReports as $file)
                    <li>
                        <a href="{{ route('reports.download', ['type' => 'daily', 'filename' => basename($file)]) }}" class="text-blue-600 hover:underline">
                            {{ basename($file) }}
                        </a>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>

    <div>
        <h3 class="text-xl font-semibold mb-2">Weekly Reports</h3>
        @if($weeklyReports->isEmpty())
            <p>No weekly reports found.</p>
        @else
            <ul class="list-disc pl-6">
                @foreach($weeklyReports as $file)
                    <li>
                        <a href="{{ route('reports.download', ['type' => 'weekly', 'filename' => basename($file)]) }}" class="text-blue-600 hover:underline">
                            {{ basename($file) }}
                        </a>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
@endsection
