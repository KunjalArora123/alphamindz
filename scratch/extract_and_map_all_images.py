import os
import openpyxl
import glob
import sys
import json
from PIL import Image
import io

sys.stdout.reconfigure(encoding='utf-8')

alpha_dir = r"d:\xampp\htdocs\AlphaMindz\alpha_tests"
output_img_dir = r"d:\xampp\htdocs\AlphaMindz\questions-images"
os.makedirs(output_img_dir, exist_ok=True)

excel_files = sorted(glob.glob(os.path.join(alpha_dir, "*.xlsx")))

file_test_key = {
    'Qusetion Paper 10th AAT 2026.xlsx': '10th',
    'Qusetion Paper 8th AAT 2026 (3).xlsx': '8th',
    'Qusetion Paper 9th AAT 2026.xlsx': '9th',
    'Qusetion Paper ARTS 2026.xlsx': 'arts',
    'Qusetion Paper COMMERCE 2026.xlsx': 'commerce',
    'Qusetion Paper SCIENCE 2026.xlsx': 'science'
}

all_extracted_images = {}

for fpath in excel_files:
    fname = os.path.basename(fpath)
    test_key = file_test_key.get(fname, 'test')
    wb = openpyxl.load_workbook(fpath, data_only=True)
    all_extracted_images[fname] = {}
    
    print(f"\n========================================================")
    print(f"EXTRACTING IMAGES FROM: {fname}")
    
    for sname in wb.sheetnames:
        ws = wb[sname]
        sheet_slug = sname.lower().replace(' ', '_').replace('\t', '')
        all_extracted_images[fname][sname] = {}
        
        # Build image list from ws._images
        images = ws._images if hasattr(ws, '_images') else []
        print(f"Sheet [{sname}]: Found {len(images)} images in worksheet")
        
        # We need to associate images with rows
        # In openpyxl, img.anchor contains anchor info
        for idx, img in enumerate(images):
            r = None
            c = None
            if hasattr(img.anchor, '_from'):
                r = img.anchor._from.row + 1 # 1-indexed row
                c = img.anchor._from.col + 1 # 1-indexed col
            elif hasattr(img.anchor, 'row'):
                r = img.anchor.row
                c = img.anchor.col
                
            if r is None or c is None:
                continue
                
            # Find closest question row for this image
            # A question row r_q usually has B or A cell as question number
            # Let's search in rows r-2 .. r+2
            best_q_num = None
            best_q_row = None
            for check_r in range(max(1, r - 2), min(ws.max_row + 1, r + 3)):
                val2 = ws.cell(check_r, 2).value
                val1 = ws.cell(check_r, 1).value
                for candidate in [val2, val1]:
                    if candidate is not None:
                        try:
                            v_str = str(candidate).strip()
                            if v_str.isdigit() and 1 <= int(v_str) <= 30:
                                best_q_num = int(v_str)
                                best_q_row = check_r
                                break
                        except ValueError:
                            pass
                if best_q_num is not None:
                    break
                    
            if best_q_num is None:
                print(f"  WARNING: Image at row {r}, col {c} could not be matched to a question row!")
                continue
                
            # Save image file
            # Get image bytes
            img_bytes = img._data()
            im = Image.open(io.BytesIO(img_bytes))
            ext = im.format.lower() if im.format else 'png'
            if ext == 'jpeg': ext = 'jpg'
            
            # Determine target field based on col:
            # col 3 (C) or col 8 (H) or col 9 (I) -> question main image
            # col 4 (D) -> option A
            # col 5 (E) -> option B
            # col 6 (F) -> option C
            # col 7 (G) -> option D
            field_name = 'main'
            if c == 4: field_name = 'opt_a'
            elif c == 5: field_name = 'opt_b'
            elif c == 6: field_name = 'opt_c'
            elif c == 7: field_name = 'opt_d'
            elif c in [3, 8, 9]: field_name = 'main'
            
            img_filename = f"{test_key}_{sheet_slug}_q{best_q_num}_{field_name}_{c}_{r}_{idx}.{ext}"
            img_rel_path = f"questions-images/{img_filename}"
            full_out_path = os.path.join(output_img_dir, img_filename)
            
            with open(full_out_path, 'wb') as f_out:
                f_out.write(img_bytes)
                
            if best_q_num not in all_extracted_images[fname][sname]:
                all_extracted_images[fname][sname][best_q_num] = []
                
            all_extracted_images[fname][sname][best_q_num].append({
                'col': c,
                'row': r,
                'field': field_name,
                'path': img_rel_path,
                'size': im.size
            })

with open(r"d:\xampp\htdocs\AlphaMindz\scratch\extracted_images_map.json", "w", encoding="utf-8") as f:
    json.dump(all_extracted_images, f, indent=2, ensure_ascii=False)

print("\nImage extraction and mapping completed!")

