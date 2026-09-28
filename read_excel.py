import pandas as pd

file_path = r'd:\xampp\htdocs\AlphaMindz\alpha_tests\MBTI  SELF SCORE.xls'
try:
    xls = pd.ExcelFile(file_path)
    
    for sheet in xls.sheet_names:
        print(f'\n--- Sheet: {sheet} ---')
        df = pd.read_excel(xls, sheet_name=sheet)
        with open(f'd:/xampp/htdocs/AlphaMindz/{sheet}.txt', 'w', encoding='utf-8') as f:
            f.write(df.to_string())
except Exception as e:
    print(f'Error: {e}')
