import pandas as pd
import json

df_q = pd.read_csv('d:/xampp/htdocs/AlphaMindz/questions.csv')
df_a = pd.read_csv('d:/xampp/htdocs/AlphaMindz/answers.csv')

questions = []
current_q = None

for idx, row in df_q.iterrows():
    col_idx = str(row.iloc[2]).strip() if pd.notna(row.iloc[2]) else ''
    text = str(row.iloc[3]).strip() if pd.notna(row.iloc[3]) else ''
    
    if col_idx.isdigit():
        if current_q:
            questions.append(current_q)
        current_q = {
            'id': int(col_idx),
            'text': text,
            'option_a': '',
            'option_b': '',
            'pair': ''
        }
    elif col_idx == '( a )' and current_q:
        current_q['option_a'] = text
    elif col_idx == '( b )' and current_q:
        current_q['option_b'] = text

if current_q:
    questions.append(current_q)

# Map from answers
dim_cols = {
    'E': 3, 'I': 4,
    'S': 5, 'N': 6,
    'T': 7, 'F': 8,
    'J': 9, 'P': 10
}

for idx, row in df_a.iterrows():
    if idx < 4: continue
    q_id = row.iloc[2]
    if pd.isna(q_id): continue
    try:
        q_id = int(float(q_id))
    except:
        continue
        
    q = next((q for q in questions if q['id'] == q_id), None)
    if not q: continue
    
    for p_name, p_cols in {'E/I': (3,4), 'S/N': (5,6), 'T/F': (7,8), 'J/P': (9,10)}.items():
        val1 = row.iloc[p_cols[0]]
        val2 = row.iloc[p_cols[1]]
        if pd.notna(val1) or pd.notna(val2):
            q['pair'] = p_name
            break

with open('d:/xampp/htdocs/AlphaMindz/mbti_questions.json', 'w') as f:
    json.dump(questions, f, indent=4)
