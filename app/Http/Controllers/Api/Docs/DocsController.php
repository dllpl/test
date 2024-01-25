<?php

namespace App\Http\Controllers\Api\Docs;

use App\Http\Controllers\Api\Docs\Traits\DocsTrait;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PhpOffice\PhpWord\TemplateProcessor;
use Ramsey\Uuid\Uuid;

class DocsController extends Controller
{

    use DocsTrait;

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

        foreach ($request->all() as $key_1 => $value_1) {
            if (is_iterable($value_1)) {
                foreach ($value_1 as $key_2 => $value_2) {
                    if (is_iterable($value_2)) {
                        foreach ($value_2 as $key_3 => $value_3) {
                            if (is_iterable($value_3)) {
                                foreach ($value_3 as $key_4 => $value_4) {
                                    $doc->setValue("$key_1.$key_2.$key_3.$key_4", $value_4);
                                }
                            } else {
                                $doc->setValue("$key_1.$key_2.$key_3", $value_3);
                            }
                        }
                    } else {
                        if ($key_2 === 'price') {
                            $doc->setValue("$key_1.$key_2",  !empty($value_2) ? number_format($value_2, 2, ',', ' ') . ' руб.' : '');
                            if(!empty($value_2)) {
                                $doc->setValue("$key_1.price.bukov", '(' . $this->num2str($value_2) . ')');
                            } else {
                                $doc->setValue("$key_1.price.bukov", '');
                            }
                        } else {
                            $doc->setValue("$key_1.$key_2", $value_2);
                        }
                    }
                }
            } else {
                $doc->setValue("$key_1", $value_1);
            }
        }

        $doc->saveAs(storage_path("$path.docx"));

        $cmd = $path_to_python . ' ' . storage_path('docs/word2pdf.py') . ' ' .
            storage_path("$path.docx") . ' ' .
            storage_path("$path.pdf") . ' 2>&1';

        exec($cmd, $output, $returnVar);

        if (!$returnVar) {
//            File::delete(storage_path("$path.docx"));

            return response()->download(storage_path("$path.pdf"), 'Договор.pdf', [
                'Content-Type' => 'application/pdf',
            ]);
        } else {
            return response()->json([
                'msg' => 'Ошибка при создании pdf файла',
                'cmd' => $cmd
            ], 400);
        }
    }
}
