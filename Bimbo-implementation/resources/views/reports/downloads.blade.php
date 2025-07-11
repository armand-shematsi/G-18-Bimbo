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
                                <li class="mb-2">
                                    <a href="{{ route('reports.view', ['filename' => basename($file)]) }}" target="_blank" class="btn btn-link text-info font-weight-bold p-0 mr-2"><i class="fas fa-eye"></i> View</a>
                                    <a href="{{ route('reports.download', ['filename' => basename($file)]) }}" class="btn btn-link text-success font-weight-bold p-0"><i class="fas fa-file-download"></i> Download</a>
                                    <span class="ml-2 text-dark">{{ basename($file) }}</span>
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
                                <li class="mb-2">
                                    <a href="{{ route('reports.weekly.view', ['filename' => basename($file)]) }}" target="_blank" class="btn btn-link text-info font-weight-bold p-0 mr-2"><i class="fas fa-eye"></i> View</a>
                                    <a href="{{ route('reports.weekly.download', ['filename' => basename($file)]) }}" class="btn btn-link text-success font-weight-bold p-0"><i class="fas fa-file-download"></i> Download</a>
                                    <span class="ml-2 text-dark">{{ basename($file) }}</span>
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
@endsection 