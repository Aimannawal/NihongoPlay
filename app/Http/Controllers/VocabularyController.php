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
        $audioPath = $audioFile->store('audio'); // Simpan di storage/app/audio

        // Store card image if provided
        $cardImagePath = null;
        if ($request->hasFile('card_image')) {
            $cardImagePath = $request->file('card_image')->store('cards'); // Simpan di storage/app/cards
        }

        // Simpan ke database
        Vocabulary::create([
            'japanese_word' => $validated['japanese_word'],
            'romaji' => $validated['romaji'],
            'meaning' => $validated['meaning'],
            'category' => $validated['category'],
            'audio_path' => $audioPath, // Tidak ada 'public/' di depan
            'card_image' => $cardImagePath,
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
        if (!Storage::exists($vocabulary->audio_path)) {
            return response()->json([
                'success' => false,
                'message' => 'Audio file tidak ditemukan!',
                'path' => $vocabulary->audio_path
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

    public function quiz()
    {
        $categories = Vocabulary::select('category')->distinct()->pluck('category');
        return view('vocabularies.quiz', compact('categories'));
    }

    public function getQuizQuestions(Request $request)
    {
        $quizType = $request->input('quiz_type');
        $categories = $request->input('categories', []);
        $count = $request->input('count', 10);
        
        // Validate input
        if (empty($categories)) {
            return response()->json([
                'success' => false,
                'message' => 'Pilih minimal satu kategori!'
            ]);
        }
        
        // Get vocabularies from selected categories
        $vocabularies = Vocabulary::whereIn('category', $categories)->inRandomOrder()->take($count)->get();
        
        if ($vocabularies->count() < $count) {
            $count = $vocabularies->count();
        }
        
        if ($vocabularies->count() === 0) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada kosakata untuk kategori yang dipilih!'
            ]);
        }
        
        $questions = [];
        
        switch ($quizType) {
            case 'multiple-choice':
                foreach ($vocabularies as $vocabulary) {
                    $wrongOptions = Vocabulary::where('id', '!=', $vocabulary->id)
                                            ->whereIn('category', $categories)
                                            ->inRandomOrder()
                                            ->take(3)
                                            ->pluck('meaning')
                                            ->toArray();
                    
                    while (count($wrongOptions) < 3) {
                        $wrongOptions[] = 'Opsi ' . mt_rand(1, 100);
                    }

                    $options = array_merge([$vocabulary->meaning], $wrongOptions);
                    shuffle($options);
                    $correctOption = array_search($vocabulary->meaning, $options);
                    
                    $questions[] = [
                        'japanese_word' => $vocabulary->japanese_word,
                        'romaji' => $vocabulary->romaji,
                        'meaning' => $vocabulary->meaning,
                        'options' => $options,
                        'correct_option' => $correctOption
                    ];
                }
                break;
                
            case 'matching':
                $pairs = [];
                foreach ($vocabularies as $vocabulary) {
                    $pairs[] = [
                        'japanese_word' => $vocabulary->japanese_word,
                        'romaji' => $vocabulary->romaji,
                        'meaning' => $vocabulary->meaning
                    ];
                }
                
                $questions[] = [
                    'pairs' => $pairs
                ];
                break;
                
            case 'listening':
                foreach ($vocabularies as $vocabulary) {
                    $wrongOptions = Vocabulary::where('id', '!=', $vocabulary->id)
                                            ->whereIn('category', $categories)
                                            ->inRandomOrder()
                                            ->take(3)
                                            ->pluck('japanese_word')
                                            ->toArray();
                    
                    while (count($wrongOptions) < 3) {
                        $wrongOptions[] = 'オプション' . mt_rand(1, 100);
                    }
                    
                    $options = array_merge([$vocabulary->japanese_word], $wrongOptions);
                    shuffle($options);
                    $correctOption = array_search($vocabulary->japanese_word, $options);
                    
                    $questions[] = [
                        'japanese_word' => $vocabulary->japanese_word,
                        'romaji' => $vocabulary->romaji,
                        'meaning' => $vocabulary->meaning,
                        'audio_url' => asset('storage/' . $vocabulary->audio_path),
                        'options' => $options,
                        'correct_option' => $correctOption
                    ];
                }
                break;
                
            case 'writing':
                foreach ($vocabularies as $vocabulary) {
                    $questions[] = [
                        'japanese_word' => $vocabulary->japanese_word,
                        'romaji' => $vocabulary->romaji,
                        'meaning' => $vocabulary->meaning
                    ];
                }
                break;
                
            default:
                return response()->json([
                    'success' => false,
                    'message' => 'Tipe quiz tidak valid!'
                ]);
        }
        
        return response()->json([
            'success' => true,
            'questions' => $questions
        ]);
    }
}
