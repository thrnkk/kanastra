<?php

namespace App\Http\Controllers;

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
        $file = $request->file('file_upload');
        $fileName = $request->input('file_name');
        $filePath = $file->store('uploads', 'public');

        $this->fileService->process($filePath, $fileName);
        dispatch(new ProcessImportJob($filePath, $fileName));

        return response()->json([], 201);
    }
}
