<?php

namespace App\Jobs;

use App\Models\DocumentManagement\File;
use App\Models\DocumentManagement\Folder;
use App\Services\DocumentManagement\FileService;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\Middleware\Skip;
use Illuminate\Support\Facades\Log;

class ProcessFileMove implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(protected File $file, protected Folder $newFolder)
    {
        $this->file = $file;
        $this->newFolder = $newFolder;
    }

    /**
     * Get the middleware the job should pass through.
     *
     * @return array
     */
    public function middleware(): array
    {
        return [
            Skip::when(function (): bool {
                return $this->shouldSkip();
            }),
        ];
    }

    private function shouldSkip(): bool
    {
        return $this->file->folder && $this->file->folder->isBeingMoved() && $this->newFolder->isBeingMoved();
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $fileService = new FileService();

            $newPath = $fileService->storeFileAs($this->file, $this->newFolder);

            $this->file->file_path = $newPath;
            $this->file->folder_id = $this->newFolder->id;
            $this->file->save();
        } catch (Exception $e) {
            Log::error('Error moving file: ' . $e->getMessage());
            throw new Exception('Failed to move file.');
        }
    }
}