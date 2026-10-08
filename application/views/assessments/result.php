<?php
if (!function_exists('formatMathSigns')) {
    function formatMathSigns($str) {
        if (empty($str)) return '';
        $text = htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
        // Degree signs
        $text = preg_replace('/\b(\d+)\s*(\^?o|deg|°)\b/i', '$1°', $text);
        // Superscripts
        $text = preg_replace('/(\b[a-zA-Z0-9\)]+)\^([\-\+]?\d+)/', '$1<sup>$2</sup>', $text);
        $text = str_replace(['²', '³', '¹', '⁰', '⁴', '⁵', '⁶', '⁷', '⁸', '⁹'], ['<sup>2</sup>', '<sup>3</sup>', '<sup>1</sup>', '<sup>0</sup>', '<sup>4</sup>', '<sup>5</sup>', '<sup>6</sup>', '<sup>7</sup>', '<sup>8</sup>', '<sup>9</sup>'], $text);
        // Subscripts
        $text = preg_replace('/([a-zA-Z])_(\d+)/', '$1<sub>$2</sub>', $text);
        $text = str_replace(['₀', '₁', '₂', '₃', '₄', '₅'], ['<sub>0</sub>', '<sub>1</sub>', '<sub>2</sub>', '<sub>3</sub>', '<sub>4</sub>', '<sub>5</sub>'], $text);
        // Multiplication sign
        $text = preg_replace('/(\d+)\s*\*\s*(\d+)/', '$1 × $2', $text);
        return $text;
    }
}
?>
<div class="page-header" style="background: #f8f9fa; padding: 40px 0; border-bottom: 1px solid #dee2e6;">
    <div class="container" style="max-width: 1000px; margin: 0 auto; padding: 0 20px;">
        <h1 style="font-family: 'Playfair Display', serif; font-size: 2.2rem; margin-bottom: 10px; color: #2c3e50;">Assessment Result Overview</h1>
        <p style="color: #6c757d;">Detailed breakdown of your performance in <strong><?php echo htmlspecialchars($attempt->subject); ?></strong></p>
    </div>
</div>

<div class="container" style="padding: 40px 20px; max-width: 1000px; margin: 0 auto;">
    
    <!-- Summary Card -->
    <div style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0; padding: 30px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); margin-bottom: 30px;">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; text-align: center;">
            <div style="padding: 15px; background: #f8fafc; border-radius: 12px; border: 1px solid #e2e8f0;">
                <div style="font-size: 0.85rem; color: #64748b; font-weight: 700; text-transform: uppercase; margin-bottom: 6px;">Total Score</div>
                <div style="font-size: 2.2rem; font-weight: 800; color: #1e293b;"><?php echo $attempt->score; ?> <span style="font-size: 1.2rem; color: #94a3b8;">/ <?php echo $attempt->total_questions; ?></span></div>
            </div>

            <div style="padding: 15px; background: #f8fafc; border-radius: 12px; border: 1px solid #e2e8f0;">
                <div style="font-size: 0.85rem; color: #64748b; font-weight: 700; text-transform: uppercase; margin-bottom: 6px;">Percentage</div>
                <div style="font-size: 2.2rem; font-weight: 800; color: <?php echo ($attempt->percentage >= 50) ? '#16a34a' : '#dc2626'; ?>;"><?php echo number_format($attempt->percentage, 2); ?>%</div>
            </div>

            <div style="padding: 15px; background: #f8fafc; border-radius: 12px; border: 1px solid #e2e8f0;">
                <div style="font-size: 0.85rem; color: #64748b; font-weight: 700; text-transform: uppercase; margin-bottom: 6px;">Completed On</div>
                <div style="font-size: 1rem; font-weight: 700; color: #334155; margin-top: 10px;"><?php echo date('M d, Y - h:i A', strtotime($attempt->completed_at)); ?></div>
            </div>
        </div>

        <div style="margin-top: 25px; text-align: center;">
            <a href="<?php echo site_url('assessments'); ?>" class="btn btn-primary" style="padding: 10px 24px; font-weight: 700; border-radius: 10px;">
                <i class="ri-arrow-left-line me-1"></i> Back to Assessments Portal
            </a>
        </div>
    </div>

    <!-- Question Response Analysis -->
    <?php if(!empty($answers)): ?>
        <h3 style="font-size: 1.4rem; font-weight: 800; color: #1e293b; margin-bottom: 20px;">Question Wise Analysis</h3>
        <div style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);">
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; font-size: 0.9rem;">
                    <thead>
                        <tr style="background: #f1f5f9; border-bottom: 2px solid #e2e8f0; text-align: left; color: #475569; font-weight: 700;">
                            <th style="padding: 14px 16px;">#</th>
                            <th style="padding: 14px 16px;">Subject / Part</th>
                            <th style="padding: 14px 16px;">Question</th>
                            <th style="padding: 14px 16px; text-align: center;">Your Choice</th>
                            <th style="padding: 14px 16px; text-align: center;">Answer Key</th>
                            <th style="padding: 14px 16px; text-align: center;">Result</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($answers as $idx => $ans): ?>
                            <tr style="border-bottom: 1px solid #f1f5f9; background: <?php echo ($idx % 2 == 0) ? '#ffffff' : '#fafafa'; ?>;">
                                <td style="padding: 14px 16px; font-weight: 700; color: #64748b;"><?php echo ($idx + 1); ?></td>
                                <td style="padding: 14px 16px; color: #475569; font-weight: 600; text-transform: uppercase; font-size: 0.8rem; max-width: 150px;"><?php echo htmlspecialchars($ans->q_subject ? $ans->q_subject : 'Section Question'); ?></td>
                                <td style="padding: 14px 16px; color: #1e293b; font-weight: 500; max-width: 350px;"><?php echo formatMathSigns($ans->question_text); ?></td>
                                <td style="padding: 14px 16px; text-align: center; font-weight: 700; color: #334155;">
                                    <?php echo $ans->selected_option ? strtoupper(htmlspecialchars($ans->selected_option)) : '<span style="color: #94a3b8; font-weight: 400; font-style: italic;">Not Answered</span>'; ?>
                                </td>
                                <td style="padding: 14px 16px; text-align: center; font-weight: 700; color: #2563eb;">
                                    <?php echo strtoupper(htmlspecialchars($ans->correct_option)); ?>
                                </td>
                                <td style="padding: 14px 16px; text-align: center;">
                                    <?php if($ans->is_correct == 1): ?>
                                        <span style="background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; padding: 4px 10px; border-radius: 20px; font-size: 0.8rem; font-weight: 700; display: inline-flex; align-items: center; gap: 4px;">
                                            <i class="ri-checkbox-circle-fill"></i> Correct
                                        </span>
                                    <?php else: ?>
                                        <span style="background: #fee2e2; color: #b91c1c; border: 1px solid #fca5a5; padding: 4px 10px; border-radius: 20px; font-size: 0.8rem; font-weight: 700; display: inline-flex; align-items: center; gap: 4px;">
                                            <i class="ri-close-circle-fill"></i> Incorrect
                                        </span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>

</div>
