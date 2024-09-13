<?php

namespace App\Jobs;

use App\Models\DocumentManagement\File;
use App\Models\DocumentManagement\Folder;
use App\Models\Organization\Organization;
use App\Notifications\FileUploaded;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class ProcessFileUpload implements ShouldQueue
{
    use Queueable;

    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public $timeout = 180;

    /**
     * Maximum number of retry attempts.
     *
     * @var int
     */

    public $tries = 3;

    /**
     * The maximum number of unhandled exceptions to allow before failing.
     *
     * @var int
     */
    public $maxExceptions = 3;

    /**
     * Indicate if the job should be marked as failed on timeout.
     *
     * @var bool
     */
    public $failOnTimeout = true;

    /**
     * Create a new job instance.
     */
    public function __construct(
        protected UploadedFile $file,
        protected ?Organization $organization,
        protected ?Folder $folder,
        protected ?string $description
    ) {
        $this->file = $file;
        $this->organization = $organization;
        $this->folder = $folder;
        $this->description = $description;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        try {
            $user = Auth::user();
            $organizationId = $this->organization?->id ?? $user?->organization_id;

            if (!$organizationId) {
                throw new Exception('No organization provided for file upload.');
            }

            if ($this->folder && !$this->folder->exists()) {
                throw new Exception('Invalid folder provided.');
            }

            $basePath = 'files';
            if ($organizationId) {
                $basePath = "organizations/{$organizationId}/{$basePath}";
            }

            if ($this->folder && $this->folder->id) {
                $basePath = "{$basePath}/folders/{$this->folder->id}";
            }

            if (!Storage::exists($basePath)) {
                Storage::makeDirectory($basePath);
            }

            $filePath = $this->file->store($basePath, 'local');

            DB::transaction(function () use ($filePath, $organizationId, $user) {
                $file = File::create([
                    'folder_id' => $this->folder?->id,
                    'file_name' => $this->file->getClientOriginalName(),
                    'file_path' => $filePath,
                    'file_type' => $this->file->getClientMimeType(),
                    'file_size' => $this->file->getSize(),
                    'description' => $this->description,
                    'organization_id' => $organizationId,
                ]);

                Notification::sendNow($user, new FileUploaded($file));
            });
        } catch (Exception $e) {
            Log::error('File upload failed: ' . $e->getMessage());

            throw $e;
        }
    }
}