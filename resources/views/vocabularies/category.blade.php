<!-- resources/views/vocabularies/category.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-center my-4">Kategori: {{ ucfirst($category) }}</h1>
    
    <div class="mb-4">
        <a href="{{ route('vocabularies.index') }}" class="btn btn-secondary">Kembali ke Kategori</a>
    </div>
    
    <div class="row">
        @if($vocabularies->count() > 0)
            @foreach($vocabularies as $vocabulary)
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    @if($vocabulary->card_image)
                    <img src="{{ asset('storage/'.$vocabulary->card_image) }}" class="card-img-top" alt="{{ $vocabulary->meaning }}">
                    @endif
                    <div class="card-body">
                        <h3 class="card-title">{{ $vocabulary->japanese_word }}</h3>
                        <p class="card-text">{{ $vocabulary->romaji }}</p>
                        <p class="card-text"><strong>Arti:</strong> {{ $vocabulary->meaning }}</p>
                        <button class="btn btn-primary play-audio" data-audio="{{ asset('storage/'.$vocabulary->audio_path) }}">
                            <i class="bi bi-volume-up"></i> Putar Suara
                        </button>
                    </div>
                    <div class="card-footer text-center">
                        <img src="{{ route('vocabularies.barcode', $vocabulary->id) }}" alt="Barcode" class="img-fluid" style="max-height: 100px;">
                    </div>
                </div>
            </div>
            @endforeach
        @else
            <div class="col-12">
                <div class="alert alert-info">
                    Belum ada kosakata untuk kategori ini. <a href="{{ route('vocabularies.create') }}">Tambahkan sekarang!</a>
                </div>
            </div>
        @endif
    </div>
    
    <div class="mt-5">
        <h3>Scan Barcode</h3>
        <div class="row">
            <div class="col-md-6">
                <div id="reader" style="width: 100%;"></div>
                <button id="rescan" class="btn btn-secondary mt-3 d-none">Scan Ulang</button>
            </div>
            <div class="col-md-6">
                <div id="result" class="mt-3">
                    <div class="card">
                        <div class="card-body">
                            <h4 id="japanese-word" class="mb-2"></h4>
                            <p id="romaji" class="mb-1"></p>
                            <p id="meaning" class="mb-3"></p>
                            <button id="play-audio" class="btn btn-primary d-none">Putar Suara</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.0/font/bootstrap-icons.css">
<script src="https://unpkg.com/html5-qrcode"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/howler/2.2.3/howler.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Initialize HTML5 QR Code Scanner
        const html5QrCode = new Html5Qrcode("reader");
        const qrConfig = { fps: 10, qrbox: 250 };
        let sound = null;
        let scannerStarted = false;

        // Start scanner
        function startScanner() {
            html5QrCode.start({ facingMode: "environment" }, qrConfig, onScanSuccess, onScanFailure)
                .then(() => {
                    scannerStarted = true;
                    document.getElementById('rescan').classList.add('d-none');
                })
                .catch(err => {
                    console.error('Failed to start scanner', err);
                });
        }

        // Start scanner when page loads
        startScanner();

        // Success callback when QR code is scanned
        function onScanSuccess(decodedText) {
            // Stop scanning
            html5QrCode.stop().then(() => {
                scannerStarted = false;
                document.getElementById('rescan').classList.remove('d-none');
            });
            
            // Send the barcode data to the server
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
                    // Display the vocabulary information
                    document.getElementById('japanese-word').textContent = data.data.japanese_word;
                    document.getElementById('romaji').textContent = data.data.romaji;
                    document.getElementById('meaning').textContent = data.data.meaning;
                    
                    // Setup audio
                    sound = new Howl({
                        src: [data.audio_url],
                        html5: true
                    });
                    
                    // Show play button
                    const playButton = document.getElementById('play-audio');
                    playButton.classList.remove('d-none');
                    playButton.addEventListener('click', function() {
                        sound.play();
                    });
                    
                    // Auto play the audio
                    sound.play();
                } else {
                    alert(data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }

        // Error callback
        function onScanFailure(error) {
            // Handle scan failure, usually no QR in sight
            console.log('Scan error: ', error);
        }

        // Re-scan button
        document.getElementById('rescan').addEventListener('click', function() {
            document.getElementById('japanese-word').textContent = '';
            document.getElementById('romaji').textContent = '';
            document.getElementById('meaning').textContent = '';
            document.getElementById('play-audio').classList.add('d-none');
            
            if (sound) {
                sound.unload();
                sound = null;
            }
            
            if (!scannerStarted) {
                startScanner();
            }
        });

        // Play audio buttons for vocabulary cards
        document.querySelectorAll('.play-audio').forEach(button => {
            button.addEventListener('click', function() {
                const audioUrl = this.getAttribute('data-audio');
                
                // Create new Howl instance
                const cardSound = new Howl({
                    src: [audioUrl],
                    html5: true
                });
                
                // Play the sound
                cardSound.play();
            });
        });
    });
</script>
@endsection