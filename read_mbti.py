import pandas as pd
import json

file_path = r'd:\xampp\htdocs\AlphaMindz\alpha_tests\MBTI  SELF SCORE.xls'
try:
    xls = pd.ExcelFile(file_path)
    df_q = pd.read_excel(xls, sheet_name='Questions')
    df_a = pd.read_excel(xls, sheet_name='Answers')
    
    # Process Questions
    questions = []
    # Looks like each question has 2 parts: (a) and (b)
    # The dataframe has rows like:
    # 2: 1, text, option_a_val, option_b_val
    # 3: (a), text, ...
    # 4: (b), text, ...
    
    # The structure:
    # row 3: q_num=1, text="When you go somewhere...", a=NaN, b=NaN
    # row 4: (a), text="Plan what you will do", a=1, b=NaN
    # row 5: (b), text="Just go", a=NaN, b=NaN (wait, a=1 is in col 4, etc)
    
    # Let's just iterate over df_q using plain index to build questions list
    q_list = []
    current_q = None
    
    # Actually, df_a contains the exact mapping.
    # df_a row 4 -> index 4 corresponds to question 1.
    # Let's write the raw dataframe to CSV so we can parse it easily, or just parse it here.
    df_q.to_csv('d:/xampp/htdocs/AlphaMindz/questions.csv', index=False)
    df_a.to_csv('d:/xampp/htdocs/AlphaMindz/answers.csv', index=False)
except Exception as e:
    print(f'Error: {e}')
