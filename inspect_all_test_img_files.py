import glob
from PIL import Image
import os

prefixes = ['10th_img', '8th_img', '9th_img', 'Arts_img', 'Commerce_img', 'Science_img']

for prefix in prefixes:
    files = glob.glob(f'questions-images/{prefix}_*')
    files.sort(key=lambda x: int(os.path.basename(x).split('_')[-1].split('.')[0]))
    print(f"\n=== {prefix} FILES ===")
    for f in files:
        try:
            im = Image.open(f)
            print(f"  {os.path.basename(f)}: Size={im.size}")
        except Exception as e:
            print(f"  {os.path.basename(f)}: Error {e}")
