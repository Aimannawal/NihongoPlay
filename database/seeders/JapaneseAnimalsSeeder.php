<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class JapaneseAnimalsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $animals = [
            [
                'japanese_word' => 'エビ',
                'romaji' => 'ebi',
                'meaning' => 'shrimp',
                'category' => 'Hewan',
                'audio_path' => 'voice/shrimp.mp3',
                'card_image' => 'animal/shrimp.png',
                'barcode_data' => 'nihongo_' . time() . '_' . rand(1000, 9999)
            ],
            [
                'japanese_word' => 'タコ',
                'romaji' => 'tako',
                'meaning' => 'octopus',
                'category' => 'Hewan',
                'audio_path' => 'voice/octopus.mp3',
                'card_image' => 'animal/octopus.png',
                'barcode_data' => 'nihongo_' . time() . '_' . rand(1000, 9999)
            ],
            [
                'japanese_word' => 'カニ',
                'romaji' => 'kani',
                'meaning' => 'crab',
                'category' => 'Hewan',
                'audio_path' => 'voice/crab.mp3',
                'card_image' => 'animal/crab.png',
                'barcode_data' => 'nihongo_' . time() . '_' . rand(1000, 9999)
            ],
            [
                'japanese_word' => '魚',
                'romaji' => 'sakana',
                'meaning' => 'fish',
                'category' => 'Hewan',
                'audio_path' => 'voice/fish.mp3',
                'card_image' => 'animal/fish.png',
                'barcode_data' => 'nihongo_' . time() . '_' . rand(1000, 9999)
            ],
            [
                'japanese_word' => 'タツノオトシゴ',
                'romaji' => 'tatsunotoshigo',
                'meaning' => 'seahorse',
                'category' => 'Hewan',
                'audio_path' => 'voice/seahorse.mp3',
                'card_image' => 'animal/seahorse.png',
                'barcode_data' => 'nihongo_' . time() . '_' . rand(1000, 9999)
            ],
            [
                'japanese_word' => 'ヒトデ',
                'romaji' => 'hitode',
                'meaning' => 'starfish',
                'category' => 'Hewan',
                'audio_path' => 'voice/starfish.mp3',
                'card_image' => 'animal/starfish.png',
                'barcode_data' => 'nihongo_' . time() . '_' . rand(1000, 9999)
            ],
            [
                'japanese_word' => 'カメ',
                'romaji' => 'kame',
                'meaning' => 'turtle',
                'category' => 'Hewan',
                'audio_path' => 'voice/turtle.mp3',
                'card_image' => 'animal/turtle.png',
                'barcode_data' => 'nihongo_' . time() . '_' . rand(1000, 9999)
            ],
            [
                'japanese_word' => 'クジラ',
                'romaji' => 'kujira',
                'meaning' => 'whale',
                'category' => 'Hewan',
                'audio_path' => 'voice/whale.mp3',
                'card_image' => 'animal/whale.png',
                'barcode_data' => 'nihongo_' . time() . '_' . rand(1000, 9999)
            ],
        ];

        foreach ($animals as $animal) {
            DB::table('vocabularies')->insert($animal);
        }
    }
}