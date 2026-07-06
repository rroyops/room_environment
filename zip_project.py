import os
import zipfile

def zip_directory(folder_path, zip_path):
    with zipfile.ZipFile(zip_path, 'w', zipfile.ZIP_DEFLATED) as zipf:
        for root, dirs, files in os.walk(folder_path):
            for file in files:
                # Calculate relative path to maintain directory structure inside zip
                abs_path = os.path.join(root, file)
                rel_path = os.path.relpath(abs_path, os.path.dirname(folder_path))
                zipf.write(abs_path, rel_path)

if __name__ == '__main__':
    source_folder = '/Room-No-320-Environment'
    # Fallback to local relative if absolute path structure varies
    if not os.path.exists(source_folder):
        source_folder = './Room-No-320-Environment'
        
    output_zip = './Room-No-320-Environment.zip'
    
    print(f"Zipping {source_folder} into {output_zip}...")
    if os.path.exists(source_folder):
        zip_directory(source_folder, output_zip)
        print("Success!")
    else:
        print(f"Error: Source folder {source_folder} not found.")
