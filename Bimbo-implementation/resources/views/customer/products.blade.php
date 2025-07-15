@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Our Fresh Bread Products') }}
    </h2>
@endsection

@section('content')
    @include('components.product-gallery', ['products' => $products])
@endsection
