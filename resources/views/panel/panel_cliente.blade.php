@extends('layouts.templateCliente')

@section('title','Panel cliente')

@push('css')
<link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush

@php
    $rol = auth()->user()->getRoleNames()->first();
@endphp

@section('content')
    <div class="container-fluid px-4">
        <h1 class="mt-4">Panel de cliente</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">Panel de cliente</li>
        </ol>
    
@endsection