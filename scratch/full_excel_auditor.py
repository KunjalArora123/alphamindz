import os
import openpyxl
import glob
import sys
import json
import re

sys.stdout.reconfigure(encoding='utf-8')

alpha_dir = r"d:\xampp\htdocs\AlphaMindz\alpha_tests"
excel_files = sorted(glob.glob(os.path.join(alpha_dir, "*.xlsx")))

audit_results = {}

for fpath in excel_files:
    fname = os.path.basename(fpath)
    wb = openpyxl.load_workbook(fpath, data_only=True)
    audit_results[fname] = {}
    
    for sname in wb.sheetnames:
        ws = wb[sname]
        
        # Build image map for this sheet: (row, col) -> image_obj
        # Note: row & col are 1-indexed
        img_map = {}
        images = ws._images if hasattr(ws, '_images') else []
        for idx, img in enumerate(images):
            r = None
            c = None
            if hasattr(img.anchor, '_from'):
                r = img.anchor._from.row + 1
                c = img.anchor._from.col + 1
            elif hasattr(img.anchor, 'row'):
                r = img.anchor.row
                c = img.anchor.col
            
            if r is not None and c is not None:
                if (r, c) not in img_map:
                    img_map[(r, c)] = []
                img_map[(r, c)].append(img)
        
        sheet_data = {
            'max_row': ws.max_row,
            'max_col': ws.max_column,
            'questions': []
        }
        
        # Track current direction/instruction text if any
        current_direction = ""
        
        for r in range(1, ws.max_row + 1):
            val1 = ws.cell(r, 1).value
            val2 = ws.cell(r, 2).value # often question number or label
            val3 = ws.cell(r, 3).value # often question text or main image
            val4 = ws.cell(r, 4).value # Option A
            val5 = ws.cell(r, 5).value # Option B
            val6 = ws.cell(r, 6).value # Option C
            val7 = ws.cell(r, 7).value # Option D
            val8 = ws.cell(r, 8).value # Fig/Graph or Answer
            val9 = ws.cell(r, 9).value # Answer
            val10 = ws.cell(r, 10).value
            
            # Check if direction text in row
            for cell_v in [val1, val2, val3]:
                if cell_v and isinstance(cell_v, str) and ("Direction:" in cell_v or "Directions:" in cell_v or "In Questions" in cell_v or "In each one" in cell_v):
                    current_direction = cell_v.strip()
            
            # Try to see if this row has a question number in col 2 or col 1
            q_num = None
            for candidate in [val2, val1]:
                if candidate is not None:
                    try:
                        q_num = int(str(candidate).strip())
                        break
                    except ValueError:
                        pass
            
            if q_num is not None:
                # Find images for this row across columns 1..10
                row_imgs = {}
                for c in range(1, 12):
                    if (r, c) in img_map:
                        row_imgs[c] = len(img_map[(r, c)])
                
                sheet_data['questions'].append({
                    'row': r,
                    'question_number': q_num,
                    'direction': current_direction,
                    'val1': str(val1) if val1 is not None else '',
                    'val2': str(val2) if val2 is not None else '',
                    'val3': str(val3) if val3 is not None else '',
                    'val4': str(val4) if val4 is not None else '',
                    'val5': str(val5) if val5 is not None else '',
                    'val6': str(val6) if val6 is not None else '',
                    'val7': str(val7) if val7 is not None else '',
                    'val8': str(val8) if val8 is not None else '',
                    'val9': str(val9) if val9 is not None else '',
                    'val10': str(val10) if val10 is not None else '',
                    'image_cols': row_imgs
                })
                
        audit_results[fname][sname] = sheet_data

with open(r"d:\xampp\htdocs\AlphaMindz\scratch\full_audit_results.json", "w", encoding="utf-8") as f:
    json.dump(audit_results, f, indent=2, ensure_ascii=False)

print("Full Excel Audit Completed!")
for fname, sheets in audit_results.items():
    print(f"\n==========================================")
    print(f"FILE: {fname}")
    for sname, sinfo in sheets.items():
        qs = sinfo['questions']
        q_with_imgs = [q for q in qs if q['image_cols']]
        print(f"  [{sname}]: {len(qs)} questions found. Questions with images: {len(q_with_imgs)}")

