import mysql.connector
import os
import sys

sys.stdout.reconfigure(encoding='utf-8')

DB_CONFIG = {
    'host': 'localhost',
    'user': 'root',
    'password': '',
    'database': 'alphamindz',
    'charset': 'utf8mb4'
}

def export_sql():
    db = mysql.connector.connect(**DB_CONFIG)
    cursor = db.cursor(dictionary=True)
    
    sql_lines = []
    sql_lines.append("-- AlphaMindz Production Test Import Dump")
    sql_lines.append("-- Generated on 2026-10-08\n")
    sql_lines.append("SET NAMES utf8mb4;\n")
    sql_lines.append("ALTER TABLE questions MODIFY COLUMN correct_option VARCHAR(20) NOT NULL;\n")
    
    # 1. Assessments
    cursor.execute("SELECT * FROM assessments WHERE id NOT IN (33, 34)")
    assessments = cursor.fetchall()
    ass_ids = [a['id'] for a in assessments]
    ass_ids_str = ', '.join(str(i) for i in ass_ids)
    
    sql_lines.append(f"-- Delete old aptitude assessments if re-importing")
    sql_lines.append(f"DELETE FROM test_answers WHERE question_id IN (SELECT id FROM questions WHERE assessment_id IN ({ass_ids_str}));")
    sql_lines.append(f"DELETE FROM questions WHERE assessment_id IN ({ass_ids_str});")
    sql_lines.append(f"DELETE FROM assessment_parts WHERE assessment_id IN ({ass_ids_str});")
    sql_lines.append(f"DELETE FROM assessments WHERE id IN ({ass_ids_str});\n")
    
    for a in assessments:
        title = a['title'].replace("'", "\\'")
        slug = a['slug'].replace("'", "\\'")
        desc = a['description'].replace("'", "\\'") if a['description'] else ''
        sql_lines.append(f"INSERT INTO assessments (id, title, slug, description, time_limit, status, created_at, updated_at) VALUES ({a['id']}, '{title}', '{slug}', '{desc}', {a['time_limit']}, '{a['status']}', NOW(), NOW());")
        
    sql_lines.append("\n-- Assessment Parts")
    cursor.execute(f"SELECT * FROM assessment_parts WHERE assessment_id IN ({ass_ids_str})")
    parts = cursor.fetchall()
    for p in parts:
        pname = p['part_name'].replace("'", "\\'")
        pdesc = p['description'].replace("'", "\\'") if p['description'] else ''
        sql_lines.append(f"INSERT INTO assessment_parts (id, assessment_id, part_name, description, created_at, updated_at) VALUES ({p['id']}, {p['assessment_id']}, '{pname}', '{pdesc}', NOW(), NOW());")
        
    sql_lines.append("\n-- Questions")
    cursor.execute(f"SELECT * FROM questions WHERE assessment_id IN ({ass_ids_str})")
    questions = cursor.fetchall()
    for q in questions:
        q_text = q['question_text'].replace("'", "\\'") if q['question_text'] else ''
        op_a = q['option_a'].replace("'", "\\'") if q['option_a'] else ''
        op_b = q['option_b'].replace("'", "\\'") if q['option_b'] else ''
        op_c = q['option_c'].replace("'", "\\'") if q['option_c'] else ''
        op_d = q['option_d'].replace("'", "\\'") if q['option_d'] else ''
        subj = q['subject'].replace("'", "\\'") if q['subject'] else ''
        correct = q['correct_option'].replace("'", "\\'") if q['correct_option'] else ''
        
        sql_lines.append(
            f"INSERT INTO questions (id, assessment_id, part_id, subject, question_number, question_text, "
            f"option_a, option_a_image, option_b, option_b_image, option_c, option_c_image, option_d, option_d_image, "
            f"image_path, image_path_2, correct_option, created_at, updated_at) VALUES ("
            f"{q['id']}, {q['assessment_id']}, {q['part_id']}, '{subj}', {q['question_number']}, '{q_text}', "
            f"'{op_a}', '{q['option_a_image'] or ''}', '{op_b}', '{q['option_b_image'] or ''}', '{op_c}', '{q['option_c_image'] or ''}', '{op_d}', '{q['option_d_image'] or ''}', "
            f"'{q['image_path'] or ''}', '{q['image_path_2'] or ''}', '{correct}', NOW(), NOW());"
        )
        
    out_path = r"d:\xampp\htdocs\AlphaMindz\alpha_tests_production_import.sql"
    with open(out_path, "w", encoding="utf-8") as f:
        f.write("\n".join(sql_lines))
        
    print(f"Generated SQL Dump: {out_path} ({len(sql_lines)} SQL statements)")

if __name__ == '__main__':
    export_sql()

