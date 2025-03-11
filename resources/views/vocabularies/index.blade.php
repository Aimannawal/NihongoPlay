<!-- resources/views/vocabularies/index.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-center my-4">NihongoPlay</h1>
    <h2 class="text-center mb-4">Pilih Kategori</h2>
    
    <div class="row">
        @foreach($categories as $category)
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{ ucfirst($category) }}</h5>
                    <p class="card-text">Belajar kosakata Bahasa Jepang tentang {{ $category }}</p>
                    <a href="{{ route('vocabularies.category', $category) }}" class="btn btn-primary">Pilih</a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    
    <div class="text-center mt-4">
        <a href="{{ route('vocabularies.create') }}" class="btn btn-success">Tambah Kosakata Baru</a>
        <a href="{{ route('vocabularies.cards') }}" class="btn btn-info ml-2">Cetak Kartu</a>
    </div>
    
    <div class="mt-5">
        <h3>Scan Barcode</h3>
        <div class="row">
            <div class="col-md-6">
                <div id="reader" style="width: 100%;"></div>
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

<script src="https://unpkg.com/html5-qrcode"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/howler/2.2.3/howler.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const html5QrCode = new Html5Qrcode("reader");
        const qrConfig = { fps: 10, qrbox: 250 };
        let sound = null;

        html5QrCode.start({ facingMode: "environment" }, qrConfig, onScanSuccess);

        function onScanSuccess(decodedText) {
            // Stop scanning
            html5QrCode.stop();
            
            // Send the barcode data to the server
            fetch('{{ route('vocabularies.scan') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
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
            
            html5QrCode.start({ facingMode: "environment" }, qrConfig, onScanSuccess);
        });
    });
</script>
@endsection