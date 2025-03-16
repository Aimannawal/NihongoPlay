@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-primary text-center mb-8">NihongoPlay - Kartu Kosakata</h1>
    
    <div class="text-center mb-8">
        <button onclick="window.print()" class="bg-primary hover:bg-primary-dark text-white font-bold py-2 px-6 rounded-lg shadow-md transition duration-300 mr-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
            </svg>
            Cetak Kartu
        </button>
        <a href="{{ route('vocabularies.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-6 rounded-lg shadow-md transition duration-300">
            Kembali
        </a>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($vocabularies as $vocabulary)
        <div class="card-printable bg-white rounded-xl shadow-lg overflow-hidden transform transition duration-300 hover:scale-105">
            <div class="p-6 text-center">
                @if($vocabulary->card_image)
                <div class="mb-4">
                    <img src="{{ asset('storage/'.$vocabulary->card_image) }}" class="mx-auto h-32 object-contain" alt="{{ $vocabulary->meaning }}">
                </div>
                @endif
                
                <h3 class="text-2xl font-bold text-primary mb-2">{{ $vocabulary->japanese_word }}</h3>
                <p class="text-lg text-gray-700 mb-1">{{ $vocabulary->romaji }}</p>
                <p class="text-lg font-medium mb-3">{{ $vocabulary->meaning }}</p>
                <p class="text-sm text-gray-500 mb-4">{{ ucfirst($vocabulary->category) }}</p>
                
                <div class="flex justify-center">
                    {!! QrCode::size(120)->generate($vocabulary->barcode_data) !!}
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection

