<?php

namespace App\Jobs;

use App\Models\DocumentManagement\Folder;
use App\Services\DocumentManagement\FileService;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\Middleware\WithoutOverlapping;

use Illuminate\Support\Facades\Log;

class ProcessFolderMove implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(protected Folder $folder, protected Folder $newParentFolder)
    {
        $this->folder = $folder;
        $this->newParentFolder = $newParentFolder;
    }

    /**
     * Get the middleware the job should pass through.
     *
     * @return array
     */
    public function middleware(): array
    {
        return [
            (new WithoutOverlapping("folder:{$this->folder}"))->shared(),
        ];
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if ($this->folder->id === $this->newParentFolder->id) {
            throw new Exception('A folder cannot be moved into itself.');
        }

        try {
            $fileService = new FileService();

            $newFolder = Folder::create([
                'name' => $this->folder->name,
                'parent_id' => $this->newParentFolder->id,
                'organization_id' => $this->folder->organization_id,
            ]);

            if ($this->folder->files()->exists()) {
                foreach ($this->folder->files as $file) {
                    if ($file->exists) {
                        $fileService->moveFile($file, $newFolder);
                    } else {
                        Log::warning('File does not exist: ' . $file->id);
                    }
                }
            }

            if ($this->folder->children()->exists()) {
                foreach ($this->folder->children as $subFolder) {
                    if ($subFolder->exists) {
                        ProcessFolderMove::dispatch($subFolder, $newFolder);
                    } else {
                        Log::warning('Subfolder does not exist: ' . $subFolder->id);
                    }
                }
            }

            $this->folder->delete();
        } catch (Exception $e) {
            Log::error('Error moving folder: ' . $e->getMessage());
            throw new Exception('Failed to move folder.');
        }
    }
}