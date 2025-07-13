@extends('layouts.bakery-manager')

@section('header')
Edit Production Batch
@endsection

@section('content')
<div class="max-w-xl mx-auto">
    @include('bakery.batches.form', ['batch' => $batch])
</div>
@endsection