import json
import sys

sys.stdout.reconfigure(encoding='utf-8')

with open(r"d:\xampp\htdocs\AlphaMindz\scratch\extracted_images_map.json", "r", encoding="utf-8") as f:
    extracted = json.load(f)

for fname, sheets in extracted.items():
    print(f"\n==========================================")
    print(f"FILE: {fname}")
    for sname, qs in sheets.items():
        if not qs:
            continue
        print(f"  Sheet [{sname}]: {len(qs)} questions with extracted images:")
        for qnum, img_list in sorted(qs.items(), key=lambda x: int(x[0])):
            fields = [f"{img['field']}(col{img['col']})" for img in img_list]
            print(f"    Q{qnum}: {', '.join(fields)}")

