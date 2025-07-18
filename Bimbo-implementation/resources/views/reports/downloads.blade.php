@extends('layouts.app')
@section('content')
    <div class="container py-4">
        <h1 class="mb-4" style="font-size:2.2rem; font-weight:bold; color:#007bff;"><i class="fas fa-file-alt mr-2"></i> Your Reports</h1>
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm" style="border:2px solid #17a2b8;">
                    <div class="card-header bg-info text-white" style="font-size:1.3rem; font-weight:bold;">
                        <i class="fas fa-calendar-day mr-2"></i> Daily Reports
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled mb-0">
                            @forelse($dailyFiles as $file)
                                <li class="mb-2 flex items-center gap-2">
                                    <a href="{{ route('reports.view', ['filename' => basename($file)]) }}" target="_blank"
                                       class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-400 to-blue-600 text-white font-semibold rounded-lg shadow-md transition-all duration-200 hover:from-blue-500 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-300 focus:ring-offset-2">
                                        <i class="fas fa-eye mr-2"></i> View
                                    </a>
                                    <a href="{{ route('reports.download', ['type' => 'daily', 'filename' => basename($file)]) }}"
                                       class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-400 to-green-600 text-white font-semibold rounded-lg shadow-md transition-all duration-200 hover:from-green-500 hover:to-green-700 focus:outline-none focus:ring-2 focus:ring-green-300 focus:ring-offset-2">
                                        <i class="fas fa-file-download mr-2"></i> Download
                                    </a>
                                    <span class="ml-2 text-dark break-all">{{ basename($file) }}</span>
                                </li>
                            @empty
                                <li class="text-center py-3" style="color:#888; font-size:1.1rem;">
                                    <i class="fas fa-exclamation-circle text-warning mr-2"></i> No daily reports found.
                                </li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm" style="border:2px solid #28a745;">
                    <div class="card-header bg-success text-white" style="font-size:1.3rem; font-weight:bold;">
                        <i class="fas fa-calendar-week mr-2"></i> Weekly Reports
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled mb-0">
                            @forelse($weeklyFiles as $file)
                                <li class="mb-2 flex items-center gap-2">
                                    <a href="{{ route('reports.weekly.view', ['filename' => basename($file)]) }}" target="_blank"
                                       class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-purple-400 to-purple-600 text-white font-semibold rounded-lg shadow-md transition-all duration-200 hover:from-purple-500 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-300 focus:ring-offset-2">
                                        <i class="fas fa-eye mr-2"></i> View
                                    </a>
                                    <a href="{{ route('reports.weekly.download', ['filename' => basename($file)]) }}"
                                       class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-yellow-400 to-yellow-600 text-white font-semibold rounded-lg shadow-md transition-all duration-200 hover:from-yellow-500 hover:to-yellow-700 focus:outline-none focus:ring-2 focus:ring-yellow-300 focus:ring-offset-2">
                                        <i class="fas fa-file-download mr-2"></i> Download
                                    </a>
                                    <span class="ml-2 text-dark break-all">{{ basename($file) }}</span>
                                </li>
                            @empty
                                <li class="text-center py-3" style="color:#888; font-size:1.1rem;">
                                    <i class="fas fa-exclamation-circle text-warning mr-2"></i> No weekly reports found.
                                </li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
        </div>
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