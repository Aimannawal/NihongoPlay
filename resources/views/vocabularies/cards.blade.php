<!-- resources/views/vocabularies/cards.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-center my-4">NihongoPlay - Kartu Kosakata</h1>
    
    <div class="text-center mb-4">
        <button onclick="window.print()" class="btn btn-primary">Cetak Kartu</button>
        <a href="{{ route('vocabularies.index') }}" class="btn btn-secondary ml-2">Kembali</a>
    </div>
    
    <div class="row">
        @foreach($vocabularies as $vocabulary)
        <div class="col-md-4 mb-4">
            <div class="card card-printable">
                <div class="card-body text-center">
                    <h3 class="card-title">{{ $vocabulary->japanese_word }}</h3>
                    <p>{{ $vocabulary->romaji }}</p>
                    <p><strong>{{ $vocabulary->meaning }}</strong></p>
                    <p class="text-muted">{{ ucfirst($vocabulary->category) }}</p>
                    
                    <div class="barcode-container mt-2">
                        <img src="{{ route('vocabularies.barcode', $vocabulary->id) }}" alt="Barcode">
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<style>
    @media print {
        body * {
            visibility: hidden;
        }
        .card-printable, .card-printable * {
            visibility: visible;
        }
        .card-printable {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
        }
        .container, .row, .col-md-4 {
            width: 100% !important;
            max-width: 100% !important;
            flex: 0 0 100% !important;
        }
    }
    .barcode-container {
        display: flex;
        justify-content: center;
    }
</style>
@endsection