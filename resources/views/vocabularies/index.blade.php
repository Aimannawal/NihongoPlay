@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Game-like Header -->
    <div class="text-center mb-12">
        <img src="{{ asset('logo.png') }}" alt="NihongoPlay Logo" class="mx-auto h-24 mb-4">
        <h1 class="text-4xl font-bold text-primary mb-2">NihongoPlay</h1>
        <p class="text-lg text-gray-600">Belajar Bahasa Jepang dengan Cara Menyenangkan</p>
    </div>
    
    <!-- Main Menu Options -->
    <div class="max-w-4xl mx-auto">
        <h2 class="text-2xl font-bold text-center mb-8 bg-primary text-white py-3 rounded-lg shadow-md">Pilih Kosakata</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($categories as $category)
            <div class="bg-white rounded-xl shadow-lg overflow-hidden transform transition duration-300 hover:scale-105 hover:shadow-xl">
                <div class="p-6">
                    <h3 class="text-xl font-semibold text-primary mb-2">{{ ucfirst($category) }}</h3>
                    <p class="text-gray-600 mb-4">Belajar kosakata Bahasa Jepang tentang {{ $category }}</p>
                    <a href="{{ route('vocabularies.category', $category) }}" 
                       class="block w-full text-center bg-primary hover:bg-primary-dark text-white font-bold py-2 px-4 rounded-lg transition duration-300">
                        Pilih
                    </a>
                </div>
            </div>
            @endforeach
        </div>
        
        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row justify-center gap-4 mt-10">
            <a href="{{ route('vocabularies.create') }}" 
               class="bg-accent hover:bg-amber-600 text-white font-bold py-3 px-6 rounded-lg shadow-md transition duration-300 text-center">
                Tambah Kosakata Baru
            </a>
            <a href="{{ route('vocabularies.cards') }}" 
               class="bg-secondary hover:bg-green-400 text-white font-bold py-3 px-6 rounded-lg shadow-md transition duration-300 text-center">
                Cetak Kartu
            </a>
            <a href="{{ route('vocabularies.quiz') }}" 
               class="bg-primary-dark hover:bg-green-800 text-white font-bold py-3 px-6 rounded-lg shadow-md transition duration-300 text-center animate-pulse-slow">
                Mulai Quiz
            </a>
        </div>
        
        <!-- QR Scanner Section -->
        <div class="mt-12 bg-white p-6 rounded-xl shadow-lg">
            <h3 class="text-2xl font-bold text-primary mb-6 text-center">Scan Kartu</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <div id="reader" class="w-full h-64 border-2 border-primary rounded-lg"></div>
                    <button id="rescan" class="mt-4 bg-primary hover:bg-primary-dark text-white font-bold py-2 px-4 rounded-lg hidden w-full">
                        Scan Ulang
                    </button>
                </div>
                <div>
                    <div id="result" class="bg-gray-100 p-6 rounded-lg min-h-[250px] flex flex-col items-center justify-center">
                        <div id="scan-placeholder" class="text-center text-gray-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                            </svg>
                            <p>Scan kartu untuk melihat kosakata</p>
                        </div>
                        <div id="scan-result" class="w-full hidden">
                            <h4 id="japanese-word" class="text-2xl font-bold text-primary mb-2 text-center"></h4>
                            <p id="romaji" class="text-lg text-center mb-1"></p>
                            <p id="meaning" class="text-lg font-medium text-center mb-4"></p>
                            <button id="play-audio" class="mt-4 bg-accent hover:bg-amber-600 text-white font-bold py-2 px-4 rounded-lg hidden w-full">
                                Putar Suara
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const html5QrCode = new Html5Qrcode("reader");
        const qrConfig = { fps: 10, qrbox: 250 };
        let sound = null;

        html5QrCode.start({ facingMode: "environment" }, qrConfig, onScanSuccess);

        function onScanSuccess(decodedText) {
            html5QrCode.stop();
            
            document.getElementById('rescan').classList.remove('hidden');
            
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
                    document.getElementById('scan-placeholder').classList.add('hidden');
                    document.getElementById('scan-result').classList.remove('hidden');
                    
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
            
            html5QrCode.start({ facingMode: "environment" }, qrConfig, onScanSuccess);
            
            this.classList.add('hidden');
        });
    });
</script>
@endsection

