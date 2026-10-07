import glob
from PIL import Image
import os

files = glob.glob('questions-images/10th_img_*')
files.sort()

print("=== 10TH_IMG FILES IN QUESTIONS-IMAGES ===")
for f in files:
    try:
        im = Image.open(f)
        print(f"{os.path.basename(f)}: Format={im.format}, Size={im.size}, Mode={im.mode}")
    except Exception as e:
        print(f"{os.path.basename(f)}: Error {e}")
