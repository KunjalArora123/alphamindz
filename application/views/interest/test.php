<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Interest Inventory Test | AlphaMindz</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; }
        .slide-in { animation: slideIn 0.3s ease-out forwards; }
        @keyframes slideIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</head>
<body class="flex flex-col min-h-screen text-slate-800">

    <!-- Header -->
    <header class="bg-white border-b border-slate-200 sticky top-0 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center shadow-lg shadow-indigo-200">
                        <span class="text-white font-bold text-xl">A</span>
                    </div>
                    <h1 class="text-xl font-extrabold bg-clip-text text-transparent bg-gradient-to-r from-indigo-700 to-blue-600 hidden sm:block">Alpha Mindz - <?php echo htmlspecialchars($subject); ?></h1>
                </div>
                <div class="flex items-center gap-4">
                    <div class="text-right hidden md:block">
                        <p class="text-sm font-bold text-slate-700"><?php echo htmlspecialchars($this->session->userdata('first_name') . ' ' . $this->session->userdata('last_name')); ?></p>
                    </div>
                    <div class="flex items-center gap-2 px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl">
                        <span class="font-mono text-sm font-bold text-slate-500">No Time Limit</span>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <main class="flex-grow max-w-4xl w-full mx-auto p-4 md:p-8">
        
        <!-- Form Container -->
        <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden">
            <form id="interest-form" action="<?php echo base_url('assessments/submit_test'); ?>" method="POST" class="p-6 md:p-10">
                <input type="hidden" name="test" value="<?php echo htmlspecialchars($subject); ?>">
                
                <!-- Section 1 -->
                <div id="section-1" class="slide-in">
                    <div class="mb-8 border-b border-slate-200 pb-4">
                        <h2 class="text-2xl font-extrabold text-indigo-900">Section 1</h2>
                        <p class="text-slate-500 mt-1">Please complete the following sentences</p>
                    </div>
                    
                    <div class="space-y-8">
                        <?php foreach($questions as $q): if($q['section'] !== 'Section 1') continue; ?>
                            <div>
                                <label class="block text-base font-semibold text-slate-800 mb-2"><?php echo htmlspecialchars($q['text']); ?></label>
                                <textarea name="answers[<?php echo $q['id']; ?>]" rows="3" class="w-full rounded-xl border border-slate-300 p-4 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-slate-700 transition-shadow" placeholder="Type your answer here..." required></textarea>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <div class="mt-10 flex justify-end">
                        <button type="button" onclick="nextSection(2)" class="px-8 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-lg shadow-indigo-200 transition-all">Next: Section 2 &rarr;</button>
                    </div>
                </div>
                
                <!-- Section 2 -->
                <div id="section-2" class="hidden slide-in">
                    <div class="mb-8 border-b border-slate-200 pb-4">
                        <h2 class="text-2xl font-extrabold text-indigo-900">Section 2</h2>
                        <p class="text-slate-500 mt-1">Preliminary Career Interest & Background Profile</p>
                    </div>
                    
                    <div class="space-y-8">
                        <?php foreach($questions as $q): if($q['section'] !== 'Section 2') continue; ?>
                            <div class="p-6 bg-slate-50 border border-slate-100 rounded-2xl">
                                <?php if($q['type'] == 'text'): ?>
                                    <h3 class="font-bold text-lg text-slate-800 mb-2"><?php echo nl2br(htmlspecialchars($q['text'])); ?></h3>
                                    <input type="text" name="answers[<?php echo $q['id']; ?>]" class="w-full rounded-xl border border-slate-300 p-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" required>
                                
                                <?php elseif($q['type'] == 'textarea'): ?>
                                    <label class="block text-base font-semibold text-slate-800 mb-3"><?php echo nl2br(htmlspecialchars($q['text'])); ?></label>
                                    <textarea name="answers[<?php echo $q['id']; ?>]" rows="3" class="w-full rounded-xl border border-slate-300 p-4 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-slate-700" placeholder="Type your answer here..." required></textarea>
                                
                                <?php elseif($q['type'] == 'radio'): ?>
                                    <label class="block text-base font-semibold text-slate-800 mb-4"><?php echo nl2br(htmlspecialchars($q['text'])); ?></label>
                                    <div class="flex gap-6">
                                        <?php foreach($q['options'] as $opt): ?>
                                            <label class="flex items-center gap-2 cursor-pointer p-2 rounded-lg hover:bg-slate-100">
                                                <input type="radio" name="answers[<?php echo $q['id']; ?>]" value="<?php echo htmlspecialchars($opt); ?>" class="w-5 h-5 text-indigo-600 focus:ring-indigo-500" required>
                                                <span class="text-slate-700 font-medium"><?php echo htmlspecialchars($opt); ?></span>
                                            </label>
                                        <?php endforeach; ?>
                                    </div>

                                <?php elseif($q['type'] == 'checkbox'): ?>
                                    <label class="block text-base font-semibold text-slate-800 mb-4"><?php echo nl2br(htmlspecialchars($q['text'])); ?></label>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                        <?php foreach($q['options'] as $opt): ?>
                                            <label class="flex items-center gap-3 cursor-pointer p-3 rounded-xl border border-slate-200 bg-white hover:border-indigo-300 hover:bg-indigo-50 transition-colors">
                                                <input type="checkbox" name="answers[<?php echo $q['id']; ?>][]" value="<?php echo htmlspecialchars($opt); ?>" class="w-5 h-5 rounded text-indigo-600 focus:ring-indigo-500">
                                                <span class="text-slate-700 font-medium"><?php echo htmlspecialchars($opt); ?></span>
                                            </label>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <div class="mt-10 flex justify-between items-center">
                        <button type="button" onclick="nextSection(1)" class="px-6 py-3 bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 font-bold rounded-xl transition-all">&larr; Back to Section 1</button>
                        <button type="submit" class="px-8 py-3 bg-green-600 hover:bg-green-700 text-white font-bold rounded-xl shadow-lg shadow-green-200 transition-all">Submit Test</button>
                    </div>
                </div>
            </form>
        </div>
    </main>

    <script>
        function nextSection(section) {
            if (section === 2) {
                // Validate Section 1
                const inputs = document.querySelectorAll('#section-1 [required]');
                let valid = true;
                inputs.forEach(input => {
                    if (!input.value.trim()) {
                        valid = false;
                        input.classList.add('border-red-500', 'bg-red-50');
                    } else {
                        input.classList.remove('border-red-500', 'bg-red-50');
                    }
                });
                
                if (!valid) {
                    alert('Please answer all questions in Section 1 before proceeding.');
                    return;
                }
                
                document.getElementById('section-1').classList.add('hidden');
                document.getElementById('section-2').classList.remove('hidden');
                window.scrollTo(0, 0);
            } else {
                document.getElementById('section-2').classList.add('hidden');
                document.getElementById('section-1').classList.remove('hidden');
                window.scrollTo(0, 0);
            }
        }
        
        document.getElementById('interest-form').addEventListener('submit', function(e) {
            // Validate Section 2
            const textInputs = document.querySelectorAll('#section-2 input[type="text"][required], #section-2 textarea[required]');
            let valid = true;
            textInputs.forEach(input => {
                if (!input.value.trim()) {
                    valid = false;
                    input.classList.add('border-red-500', 'bg-red-50');
                } else {
                    input.classList.remove('border-red-500', 'bg-red-50');
                }
            });
            
            // Validate Radios
            const radioGroups = new Set();
            document.querySelectorAll('#section-2 input[type="radio"][required]').forEach(r => radioGroups.add(r.name));
            radioGroups.forEach(name => {
                const checked = document.querySelector(`input[name="${name}"]:checked`);
                if (!checked) {
                    valid = false;
                    document.querySelectorAll(`input[name="${name}"]`).forEach(r => r.closest('.flex').classList.add('p-2', 'border', 'border-red-500', 'rounded', 'bg-red-50'));
                } else {
                    document.querySelectorAll(`input[name="${name}"]`).forEach(r => r.closest('.flex').classList.remove('p-2', 'border', 'border-red-500', 'rounded', 'bg-red-50'));
                }
            });
            
            if (!valid) {
                e.preventDefault();
                alert('Please answer all required questions in Section 2 before submitting.');
            }
        });
    </script>
</body>
</html>
