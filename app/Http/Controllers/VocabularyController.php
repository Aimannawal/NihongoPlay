<?php

namespace App\Http\Controllers;

use App\Models\Vocabulary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class VocabularyController extends Controller
{
    public function index()
    {
        $categories = Vocabulary::select('category')->distinct()->pluck('category');
        return view('vocabularies.index', compact('categories'));
    }

    public function showCategory($category)
    {
        $vocabularies = Vocabulary::where('category', $category)->get();
        return view('vocabularies.category', compact('vocabularies', 'category'));
    }

    public function create()
    {
        return view('vocabularies.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'japanese_word' => 'required|string|max:255',
            'romaji' => 'required|string|max:255',
            'meaning' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'audio_file' => 'required|file|mimes:mp3,wav',
            'card_image' => 'nullable|image',
        ]);

        // Generate a unique barcode data
        $barcodeData = 'nihongo_' . time() . '_' . mt_rand(1000, 9999);

        // Store audio file correctly
        $audioFile = $request->file('audio_file');
        $audioPath = $audioFile->store('public/audio'); // Simpan di storage/app/public/audio

        // Store card image if provided
        $cardImagePath = null;
        if ($request->hasFile('card_image')) {
            $cardImagePath = $request->file('card_image')->store('public/cards'); // Simpan di storage/app/public/cards
        }

        // Simpan ke database
        Vocabulary::create([
            'japanese_word' => $validated['japanese_word'],
            'romaji' => $validated['romaji'],
            'meaning' => $validated['meaning'],
            'category' => $validated['category'],
            'audio_path' => str_replace('public/', '', $audioPath), // Hapus 'public/' agar bisa diakses dengan asset()
            'card_image' => $cardImagePath ? str_replace('public/', '', $cardImagePath) : null,
            'barcode_data' => $barcodeData,
        ]);

        return redirect()->route('vocabularies.index')->with('success', 'Vocabulary berhasil ditambahkan!');
    }

    public function getBarcode($id)
    {
        $vocabulary = Vocabulary::findOrFail($id);
        $barcode = QrCode::format('png')
                         ->size(200)
                         ->generate($vocabulary->barcode_data);

        return response($barcode)->header('Content-Type', 'image/png');
    }

    public function scanBarcode(Request $request)
    {
        $barcodeData = $request->input('barcode_data');
        $vocabulary = Vocabulary::where('barcode_data', $barcodeData)->first();

        if (!$vocabulary) {
            return response()->json(['success' => false, 'message' => 'Barcode tidak ditemukan!']);
        }

        // Pastikan file audio ada
        if (!Storage::exists('public/' . $vocabulary->audio_path)) {
            return response()->json([
                'success' => false,
                'message' => 'Audio file tidak ditemukan!',
                'path' => 'public/' . $vocabulary->audio_path
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => $vocabulary,
            'audio_url' => asset('storage/' . $vocabulary->audio_path)
        ]);
    }

    public function generateCards()
    {
        $vocabularies = Vocabulary::all();
        return view('vocabularies.cards', compact('vocabularies'));
    }

    public function debugStorage()
    {
        $audioFiles = Storage::files('public/audio');
        $cardFiles = Storage::files('public/cards');
        $publicLink = file_exists(public_path('storage')) ? 'Exists' : 'Does not exist';

        return view('debug.storage', compact('audioFiles', 'cardFiles', 'publicLink'));
    }
}
