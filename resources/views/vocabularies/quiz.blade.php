@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Quiz Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-primary mb-2">NihongoPlay Quiz</h1>
            <p class="text-gray-600">Uji pengetahuan bahasa Jepang Anda!</p>
        </div>
        
        <!-- Quiz Selection -->
        <div id="quiz-selection" class="mb-12">
            <h2 class="text-2xl font-bold text-center mb-8 bg-primary text-white py-3 rounded-lg shadow-md">Pilih Jenis Quiz</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Multiple Choice Quiz -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden transform transition duration-300 hover:scale-105 hover:shadow-xl">
                    <div class="bg-primary-dark text-white p-4">
                        <h3 class="text-xl font-bold">Quiz Pilihan Ganda</h3>
                    </div>
                    <div class="p-6">
                        <p class="text-gray-600 mb-4">Pilih arti yang benar dari kata bahasa Jepang yang ditampilkan.</p>
                        <button onclick="startQuiz('multiple-choice')" class="w-full bg-primary hover:bg-primary-dark text-white font-bold py-2 px-4 rounded-lg transition duration-300">
                            Mulai Quiz
                        </button>
                    </div>
                </div>
                
                <!-- Matching Quiz -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden transform transition duration-300 hover:scale-105 hover:shadow-xl">
                    <div class="bg-accent text-white p-4">
                        <h3 class="text-xl font-bold">Quiz Mencocokkan</h3>
                    </div>
                    <div class="p-6">
                        <p class="text-gray-600 mb-4">Cocokkan kata bahasa Jepang dengan artinya yang benar.</p>
                        <button onclick="startQuiz('matching')" class="w-full bg-accent hover:bg-amber-600 text-white font-bold py-2 px-4 rounded-lg transition duration-300">
                            Mulai Quiz
                        </button>
                    </div>
                </div>
                
                <!-- Listening Quiz -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden transform transition duration-300 hover:scale-105 hover:shadow-xl">
                    <div class="bg-secondary text-white p-4">
                        <h3 class="text-xl font-bold">Quiz Mendengarkan</h3>
                    </div>
                    <div class="p-6">
                        <p class="text-gray-600 mb-4">Dengarkan audio dan pilih kata bahasa Jepang yang benar.</p>
                        <button onclick="startQuiz('listening')" class="w-full bg-secondary hover:bg-green-400 text-white font-bold py-2 px-4 rounded-lg transition duration-300">
                            Mulai Quiz
                        </button>
                    </div>
                </div>
                
                <!-- Writing Quiz -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden transform transition duration-300 hover:scale-105 hover:shadow-xl">
                    <div class="bg-primary text-white p-4">
                        <h3 class="text-xl font-bold">Quiz Menulis</h3>
                    </div>
                    <div class="p-6">
                        <p class="text-gray-600 mb-4">Tulis kata bahasa Jepang berdasarkan arti yang diberikan.</p>
                        <button onclick="startQuiz('writing')" class="w-full bg-primary hover:bg-primary-dark text-white font-bold py-2 px-4 rounded-lg transition duration-300">
                            Mulai Quiz
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Category Selection -->
            <div class="mt-8 bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-xl font-bold text-primary mb-4">Pilih Kategori</h3>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    @foreach($categories as $category)
                    <div class="flex items-center">
                        <input type="checkbox" id="category-{{ $category }}" name="categories[]" value="{{ $category }}" class="w-4 h-4 text-primary focus:ring-primary border-gray-300 rounded" checked>
                        <label for="category-{{ $category }}" class="ml-2 text-gray-700 capitalize">{{ $category }}</label>
                    </div>
                    @endforeach
                </div>
                <div class="mt-4">
                    <label for="question-count" class="block text-sm font-medium text-gray-700 mb-1">Jumlah Pertanyaan</label>
                    <select id="question-count" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                        <option value="5">5 Pertanyaan</option>
                        <option value="10" selected>10 Pertanyaan</option>
                        <option value="15">15 Pertanyaan</option>
                        <option value="20">20 Pertanyaan</option>
                    </select>
                </div>
            </div>
        </div>
        
        <!-- Quiz Container (Hidden initially) -->
        <div id="quiz-container" class="hidden">
            <!-- Quiz Header -->
            <div class="flex justify-between items-center mb-6">
                <h2 id="quiz-title" class="text-2xl font-bold text-primary"></h2>
                <div class="flex items-center">
                    <span class="text-gray-600 mr-2">Pertanyaan:</span>
                    <span id="question-number" class="font-bold text-primary">1</span>
                    <span class="text-gray-600 mx-1">/</span>
                    <span id="total-questions" class="text-gray-600">10</span>
                </div>
            </div>
            
            <!-- Progress Bar -->
            <div class="w-full bg-gray-200 rounded-full h-2.5 mb-6">
                <div id="progress-bar" class="bg-primary h-2.5 rounded-full" style="width: 10%"></div>
            </div>
            
            <!-- Multiple Choice Quiz -->
            <div id="multiple-choice-quiz" class="quiz-type hidden">
                <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
                    <h3 id="mc-question" class="text-2xl font-bold text-center mb-6">日本語の単語</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4" id="mc-options">
                        <!-- Options will be inserted here -->
                    </div>
                </div>
            </div>
            
            <!-- Matching Quiz -->
            <div id="matching-quiz" class="quiz-type hidden">
                <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-xl font-bold text-primary mb-4">Kata Jepang</h3>
                            <div id="matching-japanese" class="space-y-3">
                                <!-- Japanese words will be inserted here -->
                            </div>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-primary mb-4">Arti</h3>
                            <div id="matching-meanings" class="space-y-3">
                                <!-- Meanings will be inserted here -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Listening Quiz -->
            <div id="listening-quiz" class="quiz-type hidden">
                <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
                    <div class="text-center mb-6">
                        <button id="play-question-audio" class="bg-accent hover:bg-amber-600 text-white font-bold py-3 px-6 rounded-full transition duration-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z" />
                            </svg>
                            Putar Audio
                        </button>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4" id="listening-options">
                        <!-- Options will be inserted here -->
                    </div>
                </div>
            </div>
            
            <!-- Writing Quiz -->
            <div id="writing-quiz" class="quiz-type hidden">
                <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
                    <h3 id="writing-question" class="text-xl font-bold text-center mb-6">Arti: <span class="text-primary">Kucing</span></h3>
                    <div class="mb-4">
                        <label for="writing-answer" class="block text-sm font-medium text-gray-700 mb-1">Tulis kata dalam bahasa Jepang:</label>
                        <input type="text" id="writing-answer" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary" placeholder="Ketik jawaban Anda...">
                    </div>
                    <div class="text-center">
                        <button id="check-writing" class="bg-primary hover:bg-primary-dark text-white font-bold py-2 px-6 rounded-lg transition duration-300">
                            Periksa Jawaban
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Quiz Navigation -->
            <div class="flex justify-between">
                <button id="prev-question" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-6 rounded-lg transition duration-300 disabled:opacity-50 disabled:cursor-not-allowed">
                    Sebelumnya
                </button>
                <button id="next-question" class="bg-primary hover:bg-primary-dark text-white font-bold py-2 px-6 rounded-lg transition duration-300">
                    Selanjutnya
                </button>
            </div>
        </div>
        
        <!-- Quiz Results (Hidden initially) -->
        <div id="quiz-results" class="hidden">
            <div class="bg-white rounded-xl shadow-lg p-6 text-center">
                <h2 class="text-2xl font-bold text-primary mb-4">Hasil Quiz</h2>
                <div class="mb-6">
                    <div class="text-5xl font-bold text-primary mb-2"><span id="correct-count">0</span>/<span id="total-count">10</span></div>
                    <p class="text-gray-600">Jawaban benar</p>
                </div>
                <div class="w-48 h-48 mx-auto mb-6 relative">
                    <svg class="w-full h-full" viewBox="0 0 100 100">
                        <circle class="text-gray-200" stroke-width="10" stroke="currentColor" fill="transparent" r="40" cx="50" cy="50" />
                        <circle id="result-circle" class="text-primary" stroke-width="10" stroke="currentColor" fill="transparent" r="40" cx="50" cy="50" stroke-dasharray="251.2" stroke-dashoffset="251.2" />
                    </svg>
                    <div class="absolute top-0 left-0 w-full h-full flex items-center justify-center">
                        <span id="percentage" class="text-2xl font-bold text-primary">0%</span>
                    </div>
                </div>
                <div class="space-y-4">
                    <button id="show-answers" class="bg-accent hover:bg-amber-600 text-white font-bold py-2 px-6 rounded-lg transition duration-300 w-full sm:w-auto">
                        Lihat Jawaban
                    </button>
                    <button id="retry-quiz" class="bg-primary hover:bg-primary-dark text-white font-bold py-2 px-6 rounded-lg transition duration-300 w-full sm:w-auto">
                        Coba Lagi
                    </button>
                    <a href="{{ route('vocabularies.quiz') }}" class="inline-block bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-6 rounded-lg transition duration-300 w-full sm:w-auto">
                        Kembali ke Menu Quiz
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Quiz Answers (Hidden initially) -->
        <div id="quiz-answers" class="hidden mt-8">
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h2 class="text-2xl font-bold text-primary mb-4">Jawaban Quiz</h2>
                <div id="answers-list" class="space-y-4">
                    <!-- Answers will be inserted here -->
                </div>
                <div class="mt-6 text-center">
                    <button id="back-to-results" class="bg-primary hover:bg-primary-dark text-white font-bold py-2 px-6 rounded-lg transition duration-300">
                        Kembali ke Hasil
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Quiz variables
        let currentQuiz = '';
        let questions = [];
        let currentQuestionIndex = 0;
        let userAnswers = [];
        let correctAnswers = 0;
        let quizSound = null;
        
        // Quiz elements
        const quizSelection = document.getElementById('quiz-selection');
        const quizContainer = document.getElementById('quiz-container');
        const quizResults = document.getElementById('quiz-results');
        const quizAnswers = document.getElementById('quiz-answers');
        
        // Quiz navigation
        const prevButton = document.getElementById('prev-question');
        const nextButton = document.getElementById('next-question');
        
        // Start quiz function
        window.startQuiz = function(quizType) {
            currentQuiz = quizType;
            
            // Get selected categories
            const selectedCategories = [];
            document.querySelectorAll('input[name="categories[]"]:checked').forEach(checkbox => {
                selectedCategories.push(checkbox.value);
            });
            
            if (selectedCategories.length === 0) {
                alert('Pilih minimal satu kategori!');
                return;
            }
            
            // Get question count
            const questionCount = document.getElementById('question-count').value;
            
            // Fetch questions from server
            fetch('{{ route("vocabularies.getQuizQuestions") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    quiz_type: quizType,
                    categories: selectedCategories,
                    count: questionCount
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    questions = data.questions;
                    
                    // Initialize quiz
                    currentQuestionIndex = 0;
                    userAnswers = Array(questions.length).fill(null);
                    
                    // Update UI
                    document.getElementById('quiz-title').textContent = getQuizTitle(quizType);
                    document.getElementById('total-questions').textContent = questions.length;
                    document.getElementById('total-count').textContent = questions.length;
                    
                    // Hide quiz selection, show quiz container
                    quizSelection.classList.add('hidden');
                    quizContainer.classList.remove('hidden');
                    
                    // Show current quiz type
                    document.querySelectorAll('.quiz-type').forEach(element => {
                        element.classList.add('hidden');
                    });
                    document.getElementById(`${quizType}-quiz`).classList.remove('hidden');
                    
                    // Load first question
                    loadQuestion(0);
                    
                    // Disable prev button on first question
                    prevButton.disabled = true;
                } else {
                    alert(data.message || 'Gagal memuat pertanyaan quiz.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat memuat pertanyaan quiz.');
            });
        };
        
        // Get quiz title based on quiz type
        function getQuizTitle(quizType) {
            switch (quizType) {
                case 'multiple-choice':
                    return 'Quiz Pilihan Ganda';
                case 'matching':
                    return 'Quiz Mencocokkan';
                case 'listening':
                    return 'Quiz Mendengarkan';
                case 'writing':
                    return 'Quiz Menulis';
                default:
                    return 'Quiz';
            }
        }
        
        // Load question function
        function loadQuestion(index) {
            // Update question number and progress bar
            document.getElementById('question-number').textContent = index + 1;
            const progressPercentage = ((index + 1) / questions.length) * 100;
            document.getElementById('progress-bar').style.width = `${progressPercentage}%`;
            
            // Load question based on quiz type
            const question = questions[index];
            
            switch (currentQuiz) {
                case 'multiple-choice':
                    loadMultipleChoiceQuestion(question, index);
                    break;
                case 'matching':
                    loadMatchingQuestion(question, index);
                    break;
                case 'listening':
                    loadListeningQuestion(question, index);
                    break;
                case 'writing':
                    loadWritingQuestion(question, index);
                    break;
            }
            
            // Update navigation buttons
            prevButton.disabled = index === 0;
            nextButton.textContent = index === questions.length - 1 ? 'Selesai' : 'Selanjutnya';
        }
        
        // Load multiple choice question
        function loadMultipleChoiceQuestion(question, index) {
            document.getElementById('mc-question').textContent = question.japanese_word;
            
            const optionsContainer = document.getElementById('mc-options');
            optionsContainer.innerHTML = '';
            
            question.options.forEach((option, optionIndex) => {
                const optionButton = document.createElement('button');
                optionButton.className = 'p-4 border rounded-lg text-center transition duration-300';
                
                // Check if user has already answered this question
                if (userAnswers[index] !== null && userAnswers[index] === optionIndex) {
                    optionButton.classList.add('bg-primary-light', 'text-white', 'border-primary');
                } else {
                    optionButton.classList.add('bg-white', 'hover:bg-gray-100', 'border-gray-300');
                }
                
                optionButton.textContent = option;
                optionButton.addEventListener('click', () => {
                    // Remove selected class from all options
                    optionsContainer.querySelectorAll('button').forEach(btn => {
                        btn.classList.remove('bg-primary-light', 'text-white', 'border-primary');
                        btn.classList.add('bg-white', 'hover:bg-gray-100', 'border-gray-300');
                    });
                    
                    // Add selected class to clicked option
                    optionButton.classList.remove('bg-white', 'hover:bg-gray-100', 'border-gray-300');
                    optionButton.classList.add('bg-primary-light', 'text-white', 'border-primary');
                    
                    // Save user answer
                    userAnswers[index] = optionIndex;
                });
                
                optionsContainer.appendChild(optionButton);
            });
        }
        
        // Load matching question
        function loadMatchingQuestion(question, index) {
            const japaneseContainer = document.getElementById('matching-japanese');
            const meaningsContainer = document.getElementById('matching-meanings');
            
            japaneseContainer.innerHTML = '';
            meaningsContainer.innerHTML = '';
            
            question.pairs.forEach((pair, pairIndex) => {
                const japaneseButton = document.createElement('button');
                japaneseButton.className = 'w-full p-3 border rounded-lg text-center transition duration-300';
                japaneseButton.textContent = pair.japanese_word;
                japaneseButton.dataset.index = pairIndex;
                
                const meaningButton = document.createElement('button');
                meaningButton.className = 'w-full p-3 border rounded-lg text-center transition duration-300';
                meaningButton.textContent = pair.meaning;
                meaningButton.dataset.index = pairIndex;
                
                // Check if user has already matched this pair
                if (userAnswers[index] && userAnswers[index][pairIndex]) {
                    if (userAnswers[index][pairIndex].selected) {
                        japaneseButton.classList.add('bg-primary-light', 'text-white', 'border-primary');
                    }
                    if (userAnswers[index][pairIndex].matched !== null) {
                        meaningButton.classList.add('bg-primary-light', 'text-white', 'border-primary');
                    }
                } else {
                    japaneseButton.classList.add('bg-white', 'hover:bg-gray-100', 'border-gray-300');
                    meaningButton.classList.add('bg-white', 'hover:bg-gray-100', 'border-gray-300');
                }
                
                japaneseContainer.appendChild(japaneseButton);
                meaningsContainer.appendChild(meaningButton);
            });
            
            // Initialize user answers for matching if not already done
            if (!userAnswers[index]) {
                userAnswers[index] = question.pairs.map(() => ({
                    selected: false,
                    matched: null
                }));
            }
            
            // Add event listeners for matching
            const japaneseButtons = japaneseContainer.querySelectorAll('button');
            const meaningButtons = meaningsContainer.querySelectorAll('button');
            
            japaneseButtons.forEach(button => {
                button.addEventListener('click', () => {
                    // Deselect all Japanese buttons
                    japaneseButtons.forEach(btn => {
                        btn.classList.remove('bg-primary-light', 'text-white', 'border-primary');
                        btn.classList.add('bg-white', 'hover:bg-gray-100', 'border-gray-300');
                    });
                    
                    // Select clicked button
                    button.classList.remove('bg-white', 'hover:bg-gray-100', 'border-gray-300');
                    button.classList.add('bg-primary-light', 'text-white', 'border-primary');
                    
                    // Save selected state
                    const pairIndex = parseInt(button.dataset.index);
                    userAnswers[index].forEach((pair, i) => {
                        userAnswers[index][i].selected = i === pairIndex;
                    });
                });
            });
            
            meaningButtons.forEach(button => {
                button.addEventListener('click', () => {
                    // Find selected Japanese button
                    const selectedIndex = userAnswers[index].findIndex(pair => pair.selected);
                    
                    if (selectedIndex !== -1) {
                        // Match with meaning
                        const meaningIndex = parseInt(button.dataset.index);
                        userAnswers[index][selectedIndex].matched = meaningIndex;
                        
                        // Update UI
                        japaneseButtons[selectedIndex].classList.remove('bg-primary-light');
                        japaneseButtons[selectedIndex].classList.add('bg-green-500');
                        
                        button.classList.remove('bg-white', 'hover:bg-gray-100', 'border-gray-300');
                        button.classList.add('bg-green-500', 'text-white', 'border-green-500');
                        
                        // Deselect all
                        userAnswers[index].forEach((pair, i) => {
                            userAnswers[index][i].selected = false;
                        });
                    } else {
                        alert('Pilih kata bahasa Jepang terlebih dahulu!');
                    }
                });
            });
        }
        
        // Load listening question
        function loadListeningQuestion(question, index) {
            const playButton = document.getElementById('play-question-audio');
            const optionsContainer = document.getElementById('listening-options');
            
            // Setup audio
            if (quizSound) {
                quizSound.unload();
            }
            quizSound = new Howl({
                src: [question.audio_url],
                html5: true
            });
            
            // Play button event
            playButton.addEventListener('click', () => {
                quizSound.play();
            });
            
            // Load options
            optionsContainer.innerHTML = '';
            
            question.options.forEach((option, optionIndex) => {
                const optionButton = document.createElement('button');
                optionButton.className = 'p-4 border rounded-lg text-center transition duration-300';
                
                // Check if user has already answered this question
                if (userAnswers[index] !== null && userAnswers[index] === optionIndex) {
                    optionButton.classList.add('bg-primary-light', 'text-white', 'border-primary');
                } else {
                    optionButton.classList.add('bg-white', 'hover:bg-gray-100', 'border-gray-300');
                }
                
                optionButton.textContent = option;
                optionButton.addEventListener('click', () => {
                    // Remove selected class from all options
                    optionsContainer.querySelectorAll('button').forEach(btn => {
                        btn.classList.remove('bg-primary-light', 'text-white', 'border-primary');
                        btn.classList.add('bg-white', 'hover:bg-gray-100', 'border-gray-300');
                    });
                    
                    // Add selected class to clicked option
                    optionButton.classList.remove('bg-white', 'hover:bg-gray-100', 'border-gray-300');
                    optionButton.classList.add('bg-primary-light', 'text-white', 'border-primary');
                    
                    // Save user answer
                    userAnswers[index] = optionIndex;
                });
                
                optionsContainer.appendChild(optionButton);
            });
            
            // Auto play audio when question loads
            quizSound.play();
        }
        
        // Load writing question
        function loadWritingQuestion(question, index) {
            document.getElementById('writing-question').innerHTML = `Arti: <span class="text-primary">${question.meaning}</span>`;
            
            const answerInput = document.getElementById('writing-answer');
            const checkButton = document.getElementById('check-writing');
            
            // Set input value if user has already answered
            answerInput.value = userAnswers[index] || '';
            
            // Save answer on input change
            answerInput.addEventListener('input', () => {
                userAnswers[index] = answerInput.value;
            });
            
            // Check answer button
            checkButton.addEventListener('click', () => {
                const userAnswer = answerInput.value.trim();
                
                if (userAnswer === '') {
                    alert('Masukkan jawaban terlebih dahulu!');
                    return;
                }
                
                // Save user answer
                userAnswers[index] = userAnswer;
                
                // Show feedback
                const isCorrect = userAnswer.toLowerCase() === question.japanese_word.toLowerCase() || 
                                 userAnswer.toLowerCase() === question.romaji.toLowerCase();
                
                if (isCorrect) {
                    answerInput.classList.add('border-green-500', 'bg-green-50');
                    alert('Jawaban benar!');
                } else {
                    answerInput.classList.add('border-red-500', 'bg-red-50');
                    alert(`Jawaban salah. Jawaban yang benar adalah: ${question.japanese_word} (${question.romaji})`);
                }
            });
        }
        
        // Navigation event listeners
        prevButton.addEventListener('click', () => {
            if (currentQuestionIndex > 0) {
                currentQuestionIndex--;
                loadQuestion(currentQuestionIndex);
            }
        });
        
        nextButton.addEventListener('click', () => {
            if (currentQuestionIndex < questions.length - 1) {
                currentQuestionIndex++;
                loadQuestion(currentQuestionIndex);
            } else {
                // End of quiz
                finishQuiz();
            }
        });
        
        // Finish quiz function
        function finishQuiz() {
            // Calculate score
            correctAnswers = 0;
            
            questions.forEach((question, index) => {
                const userAnswer = userAnswers[index];
                
                switch (currentQuiz) {
                    case 'multiple-choice':
                    case 'listening':
                        if (userAnswer !== null && question.correct_option === userAnswer) {
                            correctAnswers++;
                        }
                        break;
                    case 'matching':
                        if (userAnswer) {
                            let allCorrect = true;
                            userAnswer.forEach((pair, pairIndex) => {
                                if (pair.matched !== pairIndex) {
                                    allCorrect = false;
                                }
                            });
                            if (allCorrect) correctAnswers++;
                        }
                        break;
                    case 'writing':
                        if (userAnswer && (userAnswer.toLowerCase() === question.japanese_word.toLowerCase() || 
                                          userAnswer.toLowerCase() === question.romaji.toLowerCase())) {
                            correctAnswers++;
                        }
                        break;
                }
            });
            
            // Update results UI
            document.getElementById('correct-count').textContent = correctAnswers;
            
            const percentage = Math.round((correctAnswers / questions.length) * 100);
            document.getElementById('percentage').textContent = `${percentage}%`;
            
            // Update result circle
            const circumference = 2 * Math.PI * 40;
            const offset = circumference - (percentage / 100) * circumference;
            document.getElementById('result-circle').style.strokeDashoffset = offset;
            
            // Show results
            quizContainer.classList.add('hidden');
            quizResults.classList.remove('hidden');
        }
        
        // Show answers button
        document.getElementById('show-answers').addEventListener('click', () => {
            // Generate answers list
            const answersList = document.getElementById('answers-list');
            answersList.innerHTML = '';
            
            questions.forEach((question, index) => {
                const answerItem = document.createElement('div');
                answerItem.className = 'p-4 border rounded-lg';
                
                const userAnswer = userAnswers[index];
                let isCorrect = false;
                let userAnswerText = 'Tidak dijawab';
                let correctAnswerText = '';
                
                switch (currentQuiz) {
                    case 'multiple-choice':
                        correctAnswerText = question.options[question.correct_option];
                        if (userAnswer !== null) {
                            userAnswerText = question.options[userAnswer];
                            isCorrect = question.correct_option === userAnswer;
                        }
                        answerItem.innerHTML = `
                            <div class="flex justify-between items-center mb-2">
                                <span class="font-bold">${index + 1}. ${question.japanese_word}</span>
                                <span class="px-2 py-1 rounded-full text-xs ${isCorrect ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">
                                    ${isCorrect ? 'Benar' : 'Salah'}
                                </span>
                            </div>
                            <p class="text-sm text-gray-600 mb-1">Jawaban Anda: ${userAnswerText}</p>
                            <p class="text-sm font-medium text-primary">Jawaban Benar: ${correctAnswerText}</p>
                        `;
                        break;
                    case 'listening':
                        correctAnswerText = question.options[question.correct_option];
                        if (userAnswer !== null) {
                            userAnswerText = question.options[userAnswer];
                            isCorrect = question.correct_option === userAnswer;
                        }
                        answerItem.innerHTML = `
                            <div class="flex justify-between items-center mb-2">
                                <span class="font-bold">${index + 1}. Audio: ${question.japanese_word}</span>
                                <span class="px-2 py-1 rounded-full text-xs ${isCorrect ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">
                                    ${isCorrect ? 'Benar' : 'Salah'}
                                </span>
                            </div>
                            <p class="text-sm text-gray-600 mb-1">Jawaban Anda: ${userAnswerText}</p>
                            <p class="text-sm font-medium text-primary">Jawaban Benar: ${correctAnswerText}</p>
                        `;
                        break;
                    case 'matching':
                        // For matching, we need to check each pair
                        if (userAnswer) {
                            let allCorrect = true;
                            const matchingResults = question.pairs.map((pair, pairIndex) => {
                                const matchedIndex = userAnswer[pairIndex].matched;
                                const isMatch = matchedIndex === pairIndex;
                                if (!isMatch) allCorrect = false;
                                
                                return `
                                    <div class="flex justify-between items-center mb-1">
                                        <span>${pair.japanese_word}</span>
                                        <span class="mx-2">→</span>
                                        <span>${matchedIndex !== null ? question.pairs[matchedIndex].meaning : 'Tidak dijawab'}</span>
                                        <span class="ml-2 ${isMatch ? 'text-green-500' : 'text-red-500'}">
                                            ${isMatch ? '✓' : '✗'}
                                        </span>
                                    </div>
                                `;
                            }).join('');
                            
                            answerItem.innerHTML = `
                                <div class="flex justify-between items-center mb-2">
                                    <span class="font-bold">${index + 1}. Mencocokkan</span>
                                    <span class="px-2 py-1 rounded-full text-xs ${allCorrect ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">
                                        ${allCorrect ? 'Benar' : 'Salah'}
                                    </span>
                                </div>
                                <div class="text-sm">
                                    ${matchingResults}
                                </div>
                                <p class="text-sm font-medium text-primary mt-2">Jawaban Benar:</p>
                                <div class="text-sm">
                                    ${question.pairs.map(pair => `
                                        <div class="flex items-center mb-1">
                                            <span>${pair.japanese_word}</span>
                                            <span class="mx-2">→</span>
                                            <span>${pair.meaning}</span>
                                        </div>
                                    `).join('')}
                                </div>
                            `;
                        } else {
                            answerItem.innerHTML = `
                                <div class="flex justify-between items-center mb-2">
                                    <span class="font-bold">${index + 1}. Mencocokkan</span>
                                    <span class="px-2 py-1 rounded-full text-xs bg-red-100 text-red-800">Salah</span>
                                </div>
                                <p class="text-sm text-gray-600 mb-1">Jawaban Anda: Tidak dijawab</p>
                                <p class="text-sm font-medium text-primary mt-2">Jawaban Benar:</p>
                                <div class="text-sm">
                                    ${question.pairs.map(pair => `
                                        <div class="flex items-center mb-1">
                                            <span>${pair.japanese_word}</span>
                                            <span class="mx-2">→</span>
                                            <span>${pair.meaning}</span>
                                        </div>
                                    `).join('')}
                                </div>
                            `;
                        }
                        break;
                    case 'writing':
                        correctAnswerText = `${question.japanese_word} (${question.romaji})`;
                        if (userAnswer) {
                            userAnswerText = userAnswer;
                            isCorrect = userAnswer.toLowerCase() === question.japanese_word.toLowerCase() || 
                                       userAnswer.toLowerCase() === question.romaji.toLowerCase();
                        }
                        answerItem.innerHTML = `
                            <div class="flex justify-between items-center mb-2">
                                <span class="font-bold">${index + 1}. Arti: ${question.meaning}</span>
                                <span class="px-2 py-1 rounded-full text-xs ${isCorrect ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">
                                    ${isCorrect ? 'Benar' : 'Salah'}
                                </span>
                            </div>
                            <p class="text-sm text-gray-600 mb-1">Jawaban Anda: ${userAnswerText}</p>
                            <p class="text-sm font-medium text-primary">Jawaban Benar: ${correctAnswerText}</p>
                        `;
                        break;
                }
                
                answersList.appendChild(answerItem);
            });
            
            quizResults.classList.add('hidden');
            quizAnswers.classList.remove('hidden');
        });

        document.getElementById('back-to-results').addEventListener('click', () => {
            quizAnswers.classList.add('hidden');
            quizResults.classList.remove('hidden');
        });
        
        document.getElementById('retry-quiz').addEventListener('click', () => {
            currentQuestionIndex = 0;
            userAnswers = Array(questions.length).fill(null);
            
            quizResults.classList.add('hidden');
            quizContainer.classList.remove('hidden');
            
            loadQuestion(0);
        });
    });
</script>
@endsection
