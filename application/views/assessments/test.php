<?php
$elapsed = time() - $start_time;
$remainingTime = $time_limit - $elapsed;
if ($remainingTime < 0) $remainingTime = 0;

function getQuestionImage($subject, $qNum) {
    $prefix = '';
    if ($subject === 'Spatial Ability') {
        $prefix = 'spatial';
    } elseif ($subject === 'Numerical Ability') {
        $prefix = 'numerical';
    } else {
        $parts = explode(' ', strtolower($subject));
        $prefix = $parts[0];
    }
    
    $extensions = ['png', 'jpg', 'jpeg', 'svg', 'gif'];
    foreach ($extensions as $ext) {
        // Path relative to index.php
        $filename = "assets/images/assessment_images/{$prefix}_q{$qNum}.{$ext}";
        if (file_exists(FCPATH . $filename)) {
            return base_url($filename);
        }
    }
    return null;
}

$jsQuestions = [];
foreach ($questions as $q) {
    $jsQuestions[] = [
        'id' => (int)$q->id,
        'number' => (int)$q->question_number,
        'subject' => $q->subject,
        'text' => $q->question_text,
        'image_path' => getQuestionImage($q->subject, $q->question_number),
        'options' => array_filter([
            'A' => $q->option_a,
            'B' => $q->option_b,
            'C' => $q->option_c,
            'D' => $q->option_d,
        ]),
    ];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alpha Mindz - <?php echo htmlspecialchars($subject); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap');
        body {
            font-family: 'Outfit', sans-serif;
            background: linear-gradient(135deg, #f8fafc 0%, #eff6ff 100%);
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }
        .card-panel {
            background: #ffffff;
            border: 1px solid rgba(226, 232, 240, 0.8);
        }
        .option-card {
            transition: all 0.25s ease;
        }
        .option-card:hover {
            background: #f1f5f9;
            border-color: #cbd5e1;
        }
        .option-selected {
            background: #eff6ff !important;
            border-color: #2563eb !important;
        }
        .slide-in {
            animation: slideIn 0.35s ease-out forwards;
        }
        @keyframes slideIn {
            from { opacity: 0; transform: translateX(10px); }
            to { opacity: 1; transform: translateX(0); }
        }
        .pulsing-timer {
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.4); }
            70% { box-shadow: 0 0 0 8px rgba(239, 68, 68, 0); }
            100% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0); }
        }
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
</head>
<body class="min-h-screen text-slate-800 flex flex-col relative overflow-x-hidden">
    
    <!-- Background wrapper to prevent absolute elements from expanding document width -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none -z-10">
        <div class="absolute -top-40 -left-40 w-96 h-96 bg-blue-600/5 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-40 -right-40 w-96 h-96 bg-indigo-600/5 rounded-full blur-3xl"></div>
    </div>

    <header class="w-full card-panel border-b border-slate-200 sticky top-0 z-40 px-4 py-3.5 md:px-8">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <div class="flex items-center gap-3">
                <img src="<?php echo base_url('assets/images/logo.png'); ?>" alt="Alpha Mindz Logo" class="h-10 w-auto">
                <div class="hidden sm:block border-l-2 border-slate-200 pl-3">
                    <h2 class="text-sm font-bold text-slate-800 tracking-wide">Alpha Mindz - <?php echo htmlspecialchars($subject); ?></h2>
                </div>
            </div>
            
            <div class="hidden md:flex flex-col items-center w-1/3 mx-4">
                <div class="flex justify-between w-full text-xs text-slate-500 mb-1 font-medium">
                    <span>PROGRESS</span>
                    <span id="progress-text">0 / <?php echo count($questions); ?> Answered</span>
                </div>
                <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden border border-slate-200">
                    <div id="progress-bar" class="bg-blue-600 h-full w-0 transition-all duration-500 rounded-full"></div>
                </div>
            </div>

            <div class="flex items-center gap-4">
                <div class="text-right hidden sm:block">
                    <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider">Candidate</p>
                    <p class="text-sm font-bold text-slate-700"><?php echo htmlspecialchars($this->session->userdata('first_name') . ' ' . $this->session->userdata('last_name')); ?></p>
                </div>
                
                <div id="timer-box" class="flex items-center gap-2 px-4 py-2 bg-blue-50/60 border border-blue-100 rounded-xl">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 text-blue-600" id="timer-icon">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span id="countdown" class="font-mono text-base font-extrabold text-blue-600">00:00</span>
                </div>
            </div>
        </div>
    </header>

    <main class="flex-grow max-w-7xl w-full mx-auto p-4 md:p-6 grid grid-cols-1 lg:grid-cols-4 gap-6 items-start">
        <div class="lg:col-span-1 card-panel rounded-2xl p-5 w-full order-last lg:order-first shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider">Questions Grid</h3>
                <span class="text-xs bg-slate-50 px-2.5 py-1 border border-slate-200 rounded-lg text-slate-500 font-semibold" id="mobile-progress">0/<?php echo count($questions); ?></span>
            </div>

            <div class="grid grid-cols-5 md:grid-cols-8 lg:grid-cols-5 gap-2 max-h-48 lg:max-h-[60vh] overflow-y-auto pr-2 custom-scrollbar" id="nav-grid">
                <!-- Rendered dynamically by JS -->
            </div>

            <div class="mt-6 pt-5 border-t border-slate-200 grid grid-cols-3 gap-2 text-[10px] uppercase font-bold tracking-wider text-slate-400 text-center">
                <div class="flex flex-col items-center gap-1.5">
                    <span class="w-5 h-5 rounded-md bg-white border border-slate-200 inline-block"></span>
                    <span>Unvisited</span>
                </div>
                <div class="flex flex-col items-center gap-1.5">
                    <span class="w-5 h-5 rounded-md bg-blue-50 border border-blue-500 inline-block"></span>
                    <span>Current</span>
                </div>
                <div class="flex flex-col items-center gap-1.5">
                    <span class="w-5 h-5 rounded-md bg-emerald-550 border border-emerald-500 inline-block" style="background-color: #ecfdf5; border-color: #10b981;"></span>
                    <span>Answered</span>
                </div>
            </div>

            <button onclick="confirmSubmit()" class="w-full mt-6 py-3.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-md shadow-blue-500/10 transition-all duration-300 transform hover:-translate-y-0.5 active:translate-y-0 text-sm tracking-wide">
                Submit Assessment
            </button>
        </div>

        <div class="lg:col-span-3 flex flex-col gap-6">
            <div class="card-panel rounded-3xl p-6 md:p-8 min-h-[400px] flex flex-col justify-between shadow-sm" id="question-window">
                <!-- Rendered dynamically by JS -->
            </div>
        </div>
    </main>

    <div id="submit-confirm-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm hidden">
        <div class="w-full max-w-md bg-white border border-slate-200 rounded-3xl p-6 shadow-2xl text-center transform scale-95 transition-transform duration-300" id="modal-card">
            <div class="w-16 h-16 mx-auto mb-4 bg-blue-500/10 rounded-full flex items-center justify-center text-blue-600">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-8 h-8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                </svg>
            </div>
            <h3 class="text-xl font-bold text-slate-800 mb-2">Submit Your Assessment?</h3>
            <p class="text-slate-500 text-sm mb-6" id="modal-details">You have answered 0 of <?php echo count($questions); ?> questions. Are you sure you want to finish the exam?</p>
            <div class="flex gap-4">
                <button onclick="closeSubmitModal()" class="flex-1 py-3 bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold rounded-xl transition duration-300">
                    Go Back
                </button>
                <button onclick="executeSubmit()" class="flex-1 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg shadow-blue-500/20 transition duration-300">
                    Yes, Submit
                </button>
            </div>
        </div>
    </div>

    <form id="submission-form" action="<?php echo site_url('assessments/submit_test'); ?>" method="POST" style="display: none;">
        <!-- Hidden inputs generated dynamically -->
    </form>

    <script>
        const questions = <?php echo json_encode($jsQuestions); ?>;
        let userAnswers = {};
        let currentIndex = 0;
        
        let remainingSeconds = <?php echo $remainingTime; ?>;
        let isSubmitting = false;

        const sessionUser = <?php echo json_encode($this->session->userdata('user_id')); ?>;
        const storagePrefix = `alphamindz_${sessionUser}_`;
        
        const cachedAnswers = localStorage.getItem(storagePrefix + 'answers');
        if (cachedAnswers) {
            try {
                userAnswers = JSON.parse(cachedAnswers);
            } catch(e) {
                userAnswers = {};
            }
        }

        const timerText = document.getElementById('countdown');
        const timerBox = document.getElementById('timer-box');
        const timerIcon = document.getElementById('timer-icon');
        
        const timerInterval = setInterval(() => {
            remainingSeconds--;
            
            if (remainingSeconds <= 0) {
                clearInterval(timerInterval);
                timerText.innerText = "00:00";
                autoSubmit();
                return;
            }

            const mins = Math.floor(remainingSeconds / 60);
            const secs = remainingSeconds % 60;
            timerText.innerText = `${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;

            if (remainingSeconds <= 120) {
                timerBox.classList.remove('bg-blue-50/60', 'border-blue-100');
                timerBox.classList.add('border-red-200', 'bg-red-50');
                timerText.classList.remove('text-blue-600');
                timerText.classList.add('text-red-600');
                timerIcon.classList.remove('text-blue-600');
                timerIcon.classList.add('text-red-600', 'pulsing-timer');
            }
        }, 1000);

        function renderQuestion() {
            const question = questions[currentIndex];
            const qWindow = document.getElementById('question-window');
            
            const formattedText = question.text.replace(/\n/g, '<br>');
            let optionsHtml = '';
            const selectedOpt = userAnswers[question.id] || null;

            for (const [letter, text] of Object.entries(question.options)) {
                const isSelected = selectedOpt === letter;
                optionsHtml += `
                    <div onclick="selectOption(${question.id}, '${letter}')" 
                         class="option-card px-5 py-4 bg-white border ${isSelected ? 'option-selected' : 'border-slate-200'} rounded-2xl cursor-pointer flex items-center gap-4 relative">
                        <span class="flex items-center justify-center min-w-[2rem] w-8 h-8 rounded-xl ${isSelected ? 'bg-blue-600 text-white' : 'bg-slate-100 border border-slate-200 text-slate-500'} font-bold text-sm transition-all duration-300">
                            ${letter}
                        </span>
                        <span class="text-slate-700 text-sm md:text-base leading-relaxed">${text.replace(/</g, '&lt;').replace(/>/g, '&gt;')}</span>
                    </div>
                `;
            }

            let imageHtml = '';
            if (question.image_path) {
                imageHtml = `
                    <div class="my-6 p-4 bg-white border border-slate-200 rounded-2xl flex justify-center items-center shadow-sm">
                        <img src="${question.image_path}" alt="Question Figure" class="max-h-64 object-contain rounded-xl">
                    </div>
                `;
            }

            qWindow.innerHTML = `
                <div class="slide-in">
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-xs font-bold uppercase tracking-wider text-blue-600 bg-blue-50 border border-blue-100 px-3 py-1 rounded-full">
                            Question ${currentIndex + 1} of ${questions.length}
                        </span>
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-500 bg-slate-100 border border-slate-200 px-3 py-1 rounded-full">
                            ${question.subject}
                        </span>
                    </div>
                    
                    <p class="text-base md:text-lg font-medium text-slate-800 leading-relaxed mb-6">
                        ${formattedText}
                    </p>
                    
                    ${imageHtml}
                    
                    <div class="space-y-3.5">
                        ${optionsHtml}
                    </div>
                </div>
                
                <div class="mt-8 pt-6 border-t border-slate-200 flex items-center justify-between gap-4">
                    <button onclick="prevQuestion()" 
                            class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 border border-slate-200 text-slate-600 font-bold rounded-xl transition duration-300 disabled:opacity-20 disabled:cursor-not-allowed text-sm"
                            ${currentIndex === 0 ? 'disabled' : ''}>
                        Previous
                    </button>
                    
                    ${selectedOpt ? `
                        <button onclick="clearResponse(${question.id})" class="text-xs font-semibold text-slate-400 hover:text-rose-600 transition duration-300">
                            Clear Response
                        </button>
                    ` : ''}
                    
                    <button onclick="nextQuestion()" 
                            class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition duration-300 text-sm">
                        ${currentIndex === questions.length - 1 ? 'Finish Review' : 'Next'}
                    </button>
                </div>
            `;

            updateNavGrid();
            updateProgress();
        }

        function nextQuestion() {
            if (currentIndex < questions.length - 1) {
                currentIndex++;
                renderQuestion();
            } else {
                confirmSubmit();
            }
        }

        function prevQuestion() {
            if (currentIndex > 0) {
                currentIndex--;
                renderQuestion();
            }
        }

        function jumpToQuestion(index) {
            currentIndex = index;
            renderQuestion();
        }

        function selectOption(qId, letter) {
            userAnswers[qId] = letter;
            localStorage.setItem(storagePrefix + 'answers', JSON.stringify(userAnswers));
            renderQuestion();
        }

        function clearResponse(qId) {
            delete userAnswers[qId];
            localStorage.setItem(storagePrefix + 'answers', JSON.stringify(userAnswers));
            renderQuestion();
        }

        function updateNavGrid() {
            const grid = document.getElementById('nav-grid');
            let gridHtml = '';
            
            questions.forEach((q, idx) => {
                const isCurrent = idx === currentIndex;
                const isAnswered = userAnswers[q.id] !== undefined;
                
                let stateClass = 'bg-white border-slate-200 text-slate-500 hover:bg-slate-50';
                if (isCurrent) {
                    stateClass = 'bg-blue-50 border-blue-500 text-blue-600 font-extrabold ring-1 ring-blue-500/30';
                } else if (isAnswered) {
                    stateClass = 'bg-emerald-50 border-emerald-500 text-emerald-600';
                }

                gridHtml += `
                    <button type="button" onclick="jumpToQuestion(${idx})" 
                            class="w-full h-10 border rounded-xl flex items-center justify-center text-sm font-semibold transition-all duration-300 ${stateClass}">
                        ${idx + 1}
                    </button>
                `;
            });
            
            grid.innerHTML = gridHtml;
        }

        function updateProgress() {
            const total = questions.length;
            const answeredCount = Object.keys(userAnswers).length;
            const percent = (answeredCount / total) * 100;
            
            document.getElementById('progress-bar').style.width = `${percent}%`;
            document.getElementById('progress-text').innerText = `${answeredCount} / ${total} Answered`;
            document.getElementById('mobile-progress').innerText = `${answeredCount}/${total}`;
        }

        function confirmSubmit() {
            const total = questions.length;
            const answeredCount = Object.keys(userAnswers).length;
            
            document.getElementById('modal-details').innerText = `You have answered ${answeredCount} of ${total} questions. Are you sure you want to finish the exam?`;
            
            const modal = document.getElementById('submit-confirm-modal');
            const card = document.getElementById('modal-card');
            
            modal.classList.remove('hidden');
            setTimeout(() => {
                card.classList.remove('scale-95');
                card.classList.add('scale-100');
            }, 50);
        }

        function closeSubmitModal() {
            const modal = document.getElementById('submit-confirm-modal');
            const card = document.getElementById('modal-card');
            
            card.classList.remove('scale-100');
            card.classList.add('scale-95');
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 150);
        }

        function executeSubmit() {
            if (isSubmitting) return;
            isSubmitting = true;
            
            localStorage.removeItem(storagePrefix + 'answers');
            
            const form = document.getElementById('submission-form');
            form.innerHTML = '';
            
            questions.forEach(q => {
                const ans = userAnswers[q.id] || '';
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = `answers[${q.id}]`;
                input.value = ans;
                form.appendChild(input);
            });
            
            form.submit();
        }

        function autoSubmit() {
            if (isSubmitting) return;
            isSubmitting = true;
            
            localStorage.removeItem(storagePrefix + 'answers');
            
            const form = document.getElementById('submission-form');
            form.innerHTML = '';
            
            questions.forEach(q => {
                const ans = userAnswers[q.id] || '';
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = `answers[${q.id}]`;
                input.value = ans;
                form.appendChild(input);
            });
            
            form.submit();
        }

        renderQuestion();
    </script>
</body>
</html>
