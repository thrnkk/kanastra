<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\File;
use App\Services\FileProcessService;
use Illuminate\Http\Request;
use App\Http\Controllers\FileController;
use Mockery;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Queue;
use ZipArchive;

class FileControllerTest extends TestCase
{
    protected $files;
    protected $fileService;
    protected $controller;

    public function setUp(): void
    {
        parent::setUp();

        $this->files = Mockery::mock(File::class);
        $this->fileService = Mockery::mock(FileProcessService::class);
        $this->controller = new FileController($this->files, $this->fileService);

        Storage::fake('public');
    }

    public function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function createTestZipFile($fileName)
    {
        $zipFilePath = storage_path('app/public/' . $fileName);

        $zip = new ZipArchive;
        if ($zip->open($zipFilePath, ZipArchive::CREATE) === TRUE) {
            $zip->addFromString('file1.csv', 'Content of file 1');
            $zip->close();
        }

        return $zipFilePath;
    }

    public function test_index_function_should_return_200_with_files_if_there_are_files()
    {
        $files = collect([
            new File(['id' => 1, 'name' => 'file1', 'original_name' => 'file1.jpg', 'status' => 'pending', 'rows' => 10, 'execution' => '']),
            new File(['id' => 2, 'name' => 'file2', 'original_name' => 'file2.jpg', 'status' => 'completed', 'rows' => 20, 'execution' => ''])
        ]);

        $this->files->shouldReceive('orderBy->get')->andReturn($files);
        $response = $this->controller->index();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(json_encode([
            new File(['id' => 1, 'name' => 'file1', 'original_name' => 'file1.jpg', 'status' => 'pending', 'rows' => 10, 'execution' => '']),
            new File(['id' => 2, 'name' => 'file2', 'original_name' => 'file2.jpg', 'status' => 'completed', 'rows' => 20, 'execution' => ''])
        ]), $response->getContent());
    }

    public function test_index_function_should_return_200_with_no_files_if_there_arent_files()
    {
        $this->files->shouldReceive('orderBy->get')->andReturn([]);
        $response = $this->controller->index();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(json_encode([]), $response->getContent());
    }

    public function test_store_function_should_return_201_on_success()
    {
        $zipFileName = 'test.zip';
        $zipFilePath = $this->createTestZipFile($zipFileName);

        $file = new UploadedFile($zipFilePath, $zipFileName, 'application/zip', null, true);

        $request = Request::create('/store', 'POST', [
            'file_name' => 'file1.csv'
        ], [], [
            'file_upload' => $file
        ]);

        $this->fileService->shouldReceive('process')
            ->once()
            ->with('uploads/' . $file->hashName(), 'file1.csv');

        $response = $this->controller->store($request);

        $this->assertEquals(201, $response->getStatusCode());
    }

    public function test_store_function_should_return_400_when_uploading_a_non_zip_file()
    {
        $file = UploadedFile::fake()->image('animal.jpg');

        $request = Request::create('/store', 'POST', [
            'file_name' => 'animal.jpg'
        ], [], [
            'file_upload' => $file
        ]);

        $response = $this->controller->store($request);

        $this->assertEquals(400, $response->getStatusCode());
    }

    public function test_store_function_should_store_the_file_on_uploads_folder()
    {
        $zipFileName = 'test.zip';
        $zipFilePath = $this->createTestZipFile($zipFileName);

        $file = new UploadedFile($zipFilePath, $zipFileName, 'application/zip', null, true);

        $request = Request::create('/store', 'POST', [
            'file_name' => 'animal.jpg'
        ], [], [
            'file_upload' => $file
        ]);

        $this->fileService->shouldReceive('process')
            ->once()
            ->with('uploads/' . $file->hashName(), 'animal.jpg');

        $response = $this->controller->store($request);

        Storage::disk('public')->assertExists('uploads/' . $file->hashName());
    }

    public function test_store_function_should_call_file_service_process_method()
    {
        $zipFileName = 'test.zip';
        $zipFilePath = $this->createTestZipFile($zipFileName);

        $file = new UploadedFile($zipFilePath, $zipFileName, 'application/zip', null, true);

        $request = Request::create('/store', 'POST', [
            'file_name' => 'animal.jpg'
        ], [], [
            'file_upload' => $file
        ]);

        $this->fileService->shouldReceive('process')
            ->once()
            ->with('uploads/' . $file->hashName(), 'animal.jpg');

        $this->controller->store($request);

        $this->fileService->shouldHaveReceived('process')
            ->once()
            ->with('uploads/' . $file->hashName(), 'animal.jpg');
    }
}
