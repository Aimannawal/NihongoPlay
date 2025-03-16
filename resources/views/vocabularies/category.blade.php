@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-primary text-white py-3 px-6 rounded-lg shadow-md mb-6">
        <h1 class="text-2xl font-bold text-center">Kategori: {{ ucfirst($category) }}</h1>
    </div>
    
    <div class="mb-6">
        <a href="{{ route('vocabularies.index') }}" class="inline-flex items-center bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-300">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali
        </a>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
        @if($vocabularies->count() > 0)
            @foreach($vocabularies as $vocabulary)
            <div class="bg-white rounded-xl shadow-lg overflow-hidden transform transition duration-300 hover:scale-105 hover:shadow-xl">
                <div class="p-6">
                    @if($vocabulary->card_image)
                    <div class="mb-4 flex justify-center">
                        <img src="{{ asset('storage/'.$vocabulary->card_image) }}" class="h-32 object-contain rounded-lg" alt="{{ $vocabulary->meaning }}">
                    </div>
                    @endif
                    
                    <h3 class="text-xl font-bold text-primary text-center mb-2">{{ $vocabulary->japanese_word }}</h3>
                    <p class="text-gray-700 text-center mb-1">{{ $vocabulary->romaji }}</p>
                    <p class="font-medium text-center mb-4">{{ $vocabulary->meaning }}</p>
                    
                    <button class="w-full bg-accent hover:bg-amber-600 text-white font-bold py-2 px-4 rounded-lg transition duration-300 play-audio" 
                            data-audio="{{ asset('storage/'.$vocabulary->audio_path) }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z" />
                        </svg>
                        Putar Suara
                    </button>
                </div>
                <div class="bg-gray-100 p-4 flex justify-center">
                    {!! QrCode::size(100)->generate($vocabulary->barcode_data) !!}
                </div>
            </div>
            @endforeach
        @else
            <div class="col-span-full">
                <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 rounded">
                    <p>Belum ada kosakata untuk kategori ini. <a href="{{ route('vocabularies.create') }}" class="underline font-bold">Tambahkan sekarang!</a></p>
                </div>
            </div>
        @endif
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const html5QrCode = new Html5Qrcode("reader");
        const qrConfig = { fps: 10, qrbox: 250 };
        let sound = null;
        let scannerStarted = false;

        function startScanner() {
            html5QrCode.start({ facingMode: "environment" }, qrConfig, onScanSuccess, onScanFailure)
                .then(() => {
                    scannerStarted = true;
                    document.getElementById('rescan').classList.add('hidden');
                })
                .catch(err => {
                    console.error('Failed to start scanner', err);
                });
        }

        startScanner();

        function onScanSuccess(decodedText) {
            html5QrCode.stop().then(() => {
                scannerStarted = false;
                document.getElementById('rescan').classList.remove('hidden');
            });
            
            document.getElementById('scan-placeholder').classList.add('hidden');
            document.getElementById('scan-result').classList.remove('hidden');
            
            fetch('{{ route('vocabularies.scan') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ barcode_data: decodedText })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('japanese-word').textContent = data.data.japanese_word;
                    document.getElementById('romaji').textContent = data.data.romaji;
                    document.getElementById('meaning').textContent = data.data.meaning;
                    
                    sound = new Howl({
                        src: [data.audio_url],
                        html5: true
                    });
                    
                    const playButton = document.getElementById('play-audio');
                    playButton.classList.remove('hidden');
                    playButton.addEventListener('click', function() {
                        sound.play();
                    });
                    
                    sound.play();
                } else {
                    alert(data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }

        function onScanFailure(error) {
            console.log('Scan error: ', error);
        }

        document.getElementById('rescan').addEventListener('click', function() {
            document.getElementById('scan-placeholder').classList.remove('hidden');
            document.getElementById('scan-result').classList.add('hidden');
            document.getElementById('japanese-word').textContent = '';
            document.getElementById('romaji').textContent = '';
            document.getElementById('meaning').textContent = '';
            document.getElementById('play-audio').classList.add('hidden');
            
            if (sound) {
                sound.unload();
                sound = null;
            }
            
            if (!scannerStarted) {
                startScanner();
            }
        });

        document.querySelectorAll('.play-audio').forEach(button => {
            button.addEventListener('click', function() {
                const audioUrl = this.getAttribute('data-audio');
                
                const cardSound = new Howl({
                    src: [audioUrl],
                    html5: true
                });
                
                cardSound.play();
            });
        });
    });
</script>
@endsection

