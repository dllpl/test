<?php

namespace App\Http\Controllers\Api\Docs;

use App\Http\Controllers\Controller;
use File;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;
use PhpOffice\PhpWord\TemplateProcessor;

class DocsController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Symfony\Component\HttpFoundation\BinaryFileResponse
     * @throws \PhpOffice\PhpWord\Exception\CopyFileException
     * @throws \PhpOffice\PhpWord\Exception\CreateTemporaryFileException
     */
    public function makeDkp(Request $request)
    {

        $path_to_python = env('PATH_TO_PYTHON');

        $file_name = Uuid::uuid4();
        $dir_name = 'docs/dkp/';

        $path = $dir_name . $file_name;

        $doc = new TemplateProcessor(storage_path('templates/dkp.docx'));

        $doc->setValue('name', $request->name);
        $doc->setValue('last_name', $request->last_name);
        $doc->saveAs(storage_path("$path.docx"));

        $cmd = $path_to_python . ' ' . storage_path('docs/word2pdf.py') . ' ' .
            storage_path("$path.docx") . ' ' .
            storage_path("$path.pdf") . ' 2>&1';

        exec($cmd, $output, $returnVar);

        if (!$returnVar) {
            File::delete(storage_path("$path.docx"));

            return response()->download(storage_path("$path.pdf"), 'Договор.pdf', [
                'Content-Type' => 'application/pdf',
            ]);
        } else {
            return response()->json([
                'msg' => 'Ошибка при создании pdf файла'
            ], 400);
        }
    }
}
