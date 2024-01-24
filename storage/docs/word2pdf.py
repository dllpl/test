from docx2pdf import convert
import sys

def convert_to_pdf(input_docx, output_pdf):
    try:
        convert(input_docx, output_pdf)
        print(f"File {input_docx} converted to {output_pdf} successfully!")
    except Exception as e:
        print(f"Error converting {input_docx} to PDF: {e}")

if __name__ == "__main__":
    input_docx_file = sys.argv[1]
    output_pdf_file = sys.argv[2]
    convert_to_pdf(input_docx_file, output_pdf_file)
