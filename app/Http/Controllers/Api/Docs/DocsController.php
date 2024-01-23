<?php

namespace App\Http\Controllers\Api\Docs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PhpOffice\PhpWord\IOFactory;
use Ramsey\Uuid\Uuid;

class DocsController extends Controller
{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     * @throws \PhpOffice\PhpWord\Exception\CopyFileException
     * @throws \PhpOffice\PhpWord\Exception\CreateTemporaryFileException
     */
    public function makeDkp(Request $request)
    {
        $file_name = Uuid::uuid4();
        $dir_name = 'docs/dkp/';

        $path = $dir_name . $file_name;

        $domPdfPath = realpath(PHPWORD_BASE_DIR . '/../vendor/mpdf/mpdf');

        \PhpOffice\PhpWord\Settings::setPdfRendererPath($domPdfPath);
        \PhpOffice\PhpWord\Settings::setPdfRendererName(\PhpOffice\PhpWord\Settings::PDF_RENDERER_MPDF);

        $doc  = new \PhpOffice\PhpWord\TemplateProcessor(storage_path('templates/dkp.docx'));

        $doc->setValue('test', $request->test);
        $doc->saveAs(storage_path("$path.docx"));

        $phpWord = IOFactory::load(storage_path("$path.docx"));

        $phpWord->save(storage_path("$path.pdf"), 'PDF');

//        \Illuminate\Support\Facades\File::delete(storage_path("$path.docx"));

        return response()->download(storage_path("$path.pdf"), 'Договор.pdf', [
            'Content-Type' => 'application/pdf',
        ]);
    }
}
