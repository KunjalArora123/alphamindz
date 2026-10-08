import os
import openpyxl
import glob
import sys
import json

sys.stdout.reconfigure(encoding='utf-8')

alpha_dir = r"d:\xampp\htdocs\AlphaMindz\alpha_tests"
excel_files = sorted(glob.glob(os.path.join(alpha_dir, "*.xlsx")))

with open(r"d:\xampp\htdocs\AlphaMindz\scratch\full_audit_results.json", "r", encoding="utf-8") as f:
    audit = json.load(f)

for fname, sheets in audit.items():
    print(f"\n========================================================")
    print(f"FILE: {fname}")
    for sname, sinfo in sheets.items():
        qs = sinfo['questions']
        empty_q_text = []
        empty_options = []
        no_answer = []
        for q in qs:
            qn = q['question_number']
            qtext = q['val3']
            op_a, op_b, op_c, op_d = q['val4'], q['val5'], q['val6'], q['val7']
            ans = q['val8'] if q['val8'] in ['A','B','C','D','a','b','c','d'] or len(q['val8'].strip())==1 else q['val9']
            
            # check image cols
            img_cols = q['image_cols']
            
            if not qtext and 3 not in img_cols and 8 not in img_cols:
                empty_q_text.append(qn)
            if not op_a and 4 not in img_cols:
                empty_options.append((qn, 'A'))
            if not op_b and 5 not in img_cols:
                empty_options.append((qn, 'B'))
            if not op_c and 6 not in img_cols:
                empty_options.append((qn, 'C'))
            if not op_d and 7 not in img_cols:
                empty_options.append((qn, 'D'))
            if not ans or str(ans).strip() == '':
                no_answer.append(qn)
                
        print(f"  Sheet: [{sname}] - Total Qs: {len(qs)}")
        if empty_q_text:
            print(f"    WARNING: Questions without text or image in col 3/8: {empty_q_text}")
        if empty_options:
            print(f"    WARNING: Empty options (no text, no image): {empty_options}")
        if no_answer:
            print(f"    WARNING: Questions with no answer key: {no_answer}")

