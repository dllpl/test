import sys
import subprocess

def docx_to_pdf(input_file, output_file):
    try:
        env = os.environ.copy()
        env['PATH'] = '/bin:/usr/bin:/usr/local/bin'  # Add necessary paths to your PATH variable
        subprocess.run(["unoconv", "-f", "pdf", "-o", output_file, input_file], env=env)
        print(f"File {input_file} converted to {output_file} successfully!")
    except Exception as e:
        print(f"Error converting {input_file} to PDF: {e}")

input_docx_file = sys.argv[1]
output_pdf_file = sys.argv[2]
docx_to_pdf(input_docx_file, output_pdf_file)
