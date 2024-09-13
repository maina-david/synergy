<?php

namespace App\Jobs;

use App\Models\DocumentManagement\Folder;
use App\Models\Organization\Organization;
use App\Services\DocumentManagement\FileService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Http\UploadedFile;
use Illuminate\Queue\Middleware\WithoutOverlapping;

class ProcessFileUpload implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(protected UploadedFile $file, protected ?Organization $organization = null, protected  ?Folder $folder = null, protected ?string $description = null)
    {
        $this->file = $file;
        $this->organization = $organization;
        $this->folder = $folder;
        $this->description = $description;
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
        $fileService = new FileService();

        $filePath = $fileService->storeFile($this->file, $this->organization, $this->folder);

        $fileService->createFileRecord($this->file, $this->organization, $this->folder, $filePath, $this->description);
    }
}