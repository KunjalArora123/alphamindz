import zipfile
import xml.etree.ElementTree as ET
import os
import glob
import openpyxl

alpha_dir = r"d:\xampp\htdocs\AlphaMindz\alpha_tests"
excel_files = sorted(glob.glob(os.path.join(alpha_dir, "*.xlsx")))

ns = {
    'xdr': 'http://schemas.openxmlformats.org/drawingml/2006/spreadsheetdrawing',
    'a': 'http://schemas.openxmlformats.org/drawingml/2006/main',
    'r': 'http://schemas.openxmlformats.org/officeDocument/2006/relationships'
}

for fpath in excel_files:
    fname = os.path.basename(fpath)
    print("\n========================================================")
    print(f"FILE: {fname}")
    
    with zipfile.ZipFile(fpath, 'r') as z:
        # Load workbook to get sheet -> drawing mapping
        wb = openpyxl.load_workbook(fpath, data_only=True)
        
        # Read sheet rels to match drawing xmls to sheet names
        sheet_drawing_map = {}
        for sname in wb.sheetnames:
            ws = wb[sname]
            # openpyxl sheet drawing
            images = ws._images if hasattr(ws, '_images') else []
            print(f"Sheet '{sname}': {len(images)} openpyxl images")
            
            # Map images by row/col
            row_col_map = {}
            for img in images:
                r, c = None, None
                if hasattr(img.anchor, '_from'):
                    r = img.anchor._from.row + 1
                    c = img.anchor._from.col + 1
                elif hasattr(img.anchor, 'row'):
                    r = img.anchor.row
                    c = img.anchor.col
                row_col_map[(r, c)] = row_col_map.get((r, c), 0) + 1
            if row_col_map:
                print(f"   Image positions (row, col): {sorted(list(row_col_map.keys()))[:10]}...")

