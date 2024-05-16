<?php

namespace Tests\Feature;

use App\Http\Controllers\FileController;
use Tests\TestCase;
use App\Models\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Queue;
use App\Jobs\ProcessImportJob;
use App\Services\FileProcessService;
use Illuminate\Foundation\Testing\WithFaker;
use Mockery;
use ZipArchive;

class FileControllerTest extends TestCase
{
    use WithFaker;

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

    public function test_index_should_return_200_with_all_files()
    {
        $filesMock = Mockery::mock(File::class)->makePartial();
        $filesMock->shouldReceive('orderBy->get')->andReturn(collect([
            ['id' => 1, 'name' => 'file1', 'original_name' => 'file1.jpg', 'status' => 'pending', 'rows' => 10, 'execution' => ''],
            ['id' => 2, 'name' => 'file2', 'original_name' => 'file2.jpg', 'status' => 'completed', 'rows' => 20, 'execution' => '']
        ]));

        $this->app->instance(File::class, $filesMock);

        $response = $this->getJson('/api/v1/file');

        $response->assertStatus(200)
            ->assertJson([
                ['id' => 1, 'name' => 'file1', 'original_name' => 'file1.jpg', 'status' => 'pending', 'rows' => 10, 'execution' => ''],
                ['id' => 2, 'name' => 'file2', 'original_name' => 'file2.jpg', 'status' => 'completed', 'rows' => 20, 'execution' => '']
            ]);
    }

    public function test_index_should_return_200_with_no_files()
    {
        $filesMock = Mockery::mock(File::class)->makePartial();
        $filesMock->shouldReceive('orderBy->get')->andReturn(collect([]));

        $this->app->instance(File::class, $filesMock);

        $response = $this->getJson('/api/v1/file');

        $response->assertStatus(200)
            ->assertJson([]);
    }

    public function test_store_function_should_return_400_when_uploading_a_non_zip_file()
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->image('animal.jpg');

        $response = $this->postJson('/api/v1/file', [
            'file_name' => 'animal.jpg',
            'file_upload' => $file,
        ]);

        $response->assertStatus(400);
    }

    public function test_store_function_should_return_201_on_success()
    {
        $zipFileName = 'test.zip';
        $zipFilePath = $this->createTestZipFile($zipFileName);

        $file = new UploadedFile($zipFilePath, $zipFileName, 'application/zip', null, true);

        $this->fileService->shouldReceive('process')
            ->once()
            ->withArgs(function ($path, $name) {
                return $path === 'uploads/' . basename($path) && $name === 'file1.csv';
            })
            ->andReturn();

        $this->app->instance(FileProcessService::class, $this->fileService);

        $response = $this->postJson('/api/v1/file', [
            'file_name' => 'file1.csv',
            'file_upload' => $file,
        ]);

        $response->assertStatus(201);
    }
}
