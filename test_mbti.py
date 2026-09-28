import pandas as pd

df_q = pd.read_csv('d:/xampp/htdocs/AlphaMindz/questions.csv')
print(df_q.head(10).to_string())
