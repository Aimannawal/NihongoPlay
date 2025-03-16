@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-3xl mx-auto">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="bg-primary py-4">
                <h1 class="text-2xl font-bold text-white text-center">Tambah Kosakata Baru</h1>
            </div>
            
            <div class="p-6">
                <form action="{{ route('vocabularies.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    
                    <div>
                        <label for="japanese_word" class="block text-sm font-medium text-gray-700 mb-1">Kata Bahasa Jepang</label>
                        <input type="text" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary @error('japanese_word') border-red-500 @enderror" 
                               id="japanese_word" name="japanese_word" required>
                        @error('japanese_word')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="romaji" class="block text-sm font-medium text-gray-700 mb-1">Romaji</label>
                        <input type="text" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary @error('romaji') border-red-500 @enderror" 
                               id="romaji" name="romaji" required>
                        @error('romaji')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="meaning" class="block text-sm font-medium text-gray-700 mb-1">Arti</label>
                        <input type="text" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary @error('meaning') border-red-500 @enderror" 
                               id="meaning" name="meaning" required>
                        @error('meaning')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="category" class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                        <input type="text" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary @error('category') border-red-500 @enderror" 
                               id="category" name="category" required>
                        <p class="text-sm text-gray-500 mt-1">Contoh: hewan, buah, angka, dll.</p>
                        @error('category')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="audio_file" class="block text-sm font-medium text-gray-700 mb-1">File Audio</label>
                        <input type="file" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary @error('audio_file') border-red-500 @enderror" 
                               id="audio_file" name="audio_file" required>
                        <p class="text-sm text-gray-500 mt-1">File audio untuk kata ini (format: mp3, wav)</p>
                        @error('audio_file')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="card_image" class="block text-sm font-medium text-gray-700 mb-1">Gambar Kartu (Opsional)</label>
                        <input type="file" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary @error('card_image') border-red-500 @enderror" 
                               id="card_image" name="card_image">
                        <p class="text-sm text-gray-500 mt-1">Gambar ilustrasi untuk kartu (opsional)</p>
                        @error('card_image')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="flex justify-between pt-4">
                        <a href="{{ route('vocabularies.index') }}" class="px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition duration-300">
                            Kembali
                        </a>
                        <button type="submit" class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition duration-300">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

