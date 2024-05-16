<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use App\Jobs\ProcessImportJob;
use App\Models\File;
use App\Services\FileProcessService;
use Illuminate\Http\Request;

class FileController extends Controller
{

    public function __construct(private File $files, private FileProcessService $fileService)
    {
        $this->files = $files;
        $this->fileService = $fileService;
    }

    public function index()
    {

        $animals = $this->files->orderBy('id')->get();
        return response()->json($animals, 200);
    }

    public function store(Request $request)
    {
        $validation = Validator::make(
            $request->all(),
            [
                'file_upload' => 'mimes:zip'
            ],
            [
                'file_upload.mimes' => 'The file should be a .zip!'
            ]
        );

        if ($validation->fails()) return response()->json($validation->messages(), 400);

        $file = $request->file('file_upload');
        $fileName = $request->input('file_name');
        $filePath = $file->store('uploads', 'public');

        $this->fileService->process($filePath, $fileName);
        // dispatch(new ProcessImportJob($filePath, $fileName));

        return response()->json([], 201);
    }
}
