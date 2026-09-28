import pandas as pd
import json

df_q = pd.read_csv('d:/xampp/htdocs/AlphaMindz/questions.csv')
df_a = pd.read_csv('d:/xampp/htdocs/AlphaMindz/answers.csv')

questions = []
current_q = None

for idx, row in df_q.iterrows():
    col1 = str(row.iloc[1]).strip() if pd.notna(row.iloc[1]) else ''
    col2 = str(row.iloc[2]).strip() if pd.notna(row.iloc[2]) else ''
    
    if col1.isdigit():
        if current_q:
            questions.append(current_q)
        current_q = {
            'id': int(col1),
            'text': col2,
            'option_a': '',
            'option_b': '',
            'dimension_a': '',
            'dimension_b': ''
        }
    elif col1 == '( a )' and current_q:
        current_q['option_a'] = col2
    elif col1 == '( b )' and current_q:
        current_q['option_b'] = col2

if current_q:
    questions.append(current_q)

# Now map dimensions from answers.csv
# answers.csv columns: Unnamed: 2 is Q_id, Unnamed: 3-10 are E,I,S,N,T,F,J,P
# row 3 is the header for E, I, S, N, T, F, J, P

dimensions = ['E', 'I', 'S', 'N', 'T', 'F', 'J', 'P']
# Row 4 onwards are data
for idx, row in df_a.iterrows():
    if idx < 4: continue
    q_id = row.iloc[2]
    if pd.isna(q_id): continue
    try:
        q_id = int(float(q_id))
    except:
        continue
        
    # Find question
    q = next((q for q in questions if q['id'] == q_id), None)
    if not q: continue
    
    # Check which dimension gets '1' or '0'. Wait, answers in sheet were dummy answers.
    # We need to figure out which dimension corresponds to A and B.
    # Ah! The dummy answers sheet only has the user's answers. How do we know if A is E or I?
    # Actually, in the MBTI SELF SCORE.xls, the user places '1' or '0' in the column.
    # No, the answers.csv has predefined formulas? Let's check answers.csv
