<!-- resources/views/vocabularies/create.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-center my-4">Tambah Kosakata Baru</h1>
    
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('vocabularies.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="form-group">
                            <label for="japanese_word">Kata Bahasa Jepang</label>
                            <input type="text" class="form-control @error('japanese_word') is-invalid @enderror" id="japanese_word" name="japanese_word" required>
                            @error('japanese_word')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="romaji">Romaji</label>
                            <input type="text" class="form-control @error('romaji') is-invalid @enderror" id="romaji" name="romaji" required>
                            @error('romaji')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="meaning">Arti</label>
                            <input type="text" class="form-control @error('meaning') is-invalid @enderror" id="meaning" name="meaning" required>
                            @error('meaning')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="category">Kategori</label>
                            <input type="text" class="form-control @error('category') is-invalid @enderror" id="category" name="category" required>
                            <small class="form-text text-muted">Contoh: hewan, buah, angka, dll.</small>
                            @error('category')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="audio_file">File Audio</label>
                            <input type="file" class="form-control-file @error('audio_file') is-invalid @enderror" id="audio_file" name="audio_file" required>
                            <small class="form-text text-muted">File audio untuk kata ini (format: mp3, wav)</small>
                            @error('audio_file')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="card_image">Gambar Kartu (Opsional)</label>
                            <input type="file" class="form-control-file @error('card_image') is-invalid @enderror" id="card_image" name="card_image">
                            <small class="form-text text-muted">Gambar ilustrasi untuk kartu (opsional)</small>
                            @error('card_image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                            <a href="{{ route('vocabularies.index') }}" class="btn btn-secondary ml-2">Kembali</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection