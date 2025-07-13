@extends('layouts.bakery-manager')

@section('header')
New Production Batch
@endsection

@section('content')
<div class="max-w-xl mx-auto">
    @include('bakery.batches.form')
</div>
@endsection