import json
import sys

sys.stdout.reconfigure(encoding='utf-8')

with open(r"d:\xampp\htdocs\AlphaMindz\scratch\detailed_question_report.json", "r", encoding="utf-8") as f:
    report = json.load(f)

print(f"Total questions loaded: {len(report)}")

no_ans = []
no_content = []

for q in report:
    file = q['file']
    sheet = q['sheet']
    qnum = q['q_num']
    ans = q['ans']
    qtext = q['q_text']
    img_cols = q['img_cols']
    
    # Check if answer key is missing
    if not ans:
        no_ans.append(f"{file} -> [{sheet}] Q{qnum} (val8='{q['val8']}', val9='{q['val9']}')")
        
    # Check if question content (text or image) is missing
    if not qtext and '3' not in img_cols and '8' not in img_cols and '2' not in img_cols:
        no_content.append(f"{file} -> [{sheet}] Q{qnum}")

print(f"\n--- Questions without detected Answer Key ({len(no_ans)}) ---")
for item in no_ans:
    print("  ", item)

print(f"\n--- Questions without Text or Image Content ({len(no_content)}) ---")
for item in no_content:
    print("  ", item)

