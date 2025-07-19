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
    <div class="w-full flex justify-center mt-8">
        <form method="POST" action="{{ route('reports.generate') }}">
            @csrf
            <button type="submit" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-500 to-green-500 text-white font-bold rounded-lg shadow-lg text-lg uppercase tracking-widest hover:from-blue-600 hover:to-green-600 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
                Generate
            </button>
        </form>
    </div>
@endsection
