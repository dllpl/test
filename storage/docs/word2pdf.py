import os
import pdfkit
from docx import Document
import sys

def docx_to_pdf(input_file, output_file):
    # Read the content of the docx file
    doc = Document(input_file)
    html_content = ""
    for paragraph in doc.paragraphs:
        html_content += f"<p>{paragraph.text}</p>"

    # Create a temporary HTML file
    tmp_html_file = "/tmp/temp.html"
    with open(tmp_html_file, "w", encoding="utf-8") as file:
        file.write(html_content)

    # Convert the HTML to PDF
    pdfkit.from_file(tmp_html_file, output_file, options={'enable-local-file-access': None})

    # Clean up the temporary HTML file
    os.remove(tmp_html_file)

# Example usage
input_docx_file = sys.argv[1]
output_pdf_file = sys.argv[2]
docx_to_pdf(input_docx_file, output_pdf_file)
