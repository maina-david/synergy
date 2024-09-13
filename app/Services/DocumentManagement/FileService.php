<?php

namespace App\Services\DocumentManagement;

use App\Jobs\ProcessFileDelete;
use App\Jobs\ProcessFileMove;
use App\Jobs\ProcessFileUpload;
use App\Jobs\ProcessFolderMove;
use App\Models\Organization\Organization;
use App\Models\DocumentManagement\File;
use App\Models\DocumentManagement\Folder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Storage;
use Exception;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FileService
{
    /**
     * Generate a tree view of folders and files.
     *
     * @param Organization|null $organization
     * @param Folder|null $parentFolder
     * @return array
     */
    public function generateTreeView(?Organization $organization = null, ?Folder $parentFolder = null): array
    {
        if ($organization && $parentFolder && $parentFolder->organization_id !== $organization->id) {
            throw new Exception('Parent folder does not belong to the specified organization.');
        }

        $tree = [];

        $folders = Folder::where('organization_id', $organization->id ?? null)
            ->where('parent_id', $parentFolder?->id)
            ->get();

        $files = File::where('organization_id', $organization->id ?? null)
            ->where('folder_id', $parentFolder?->id)
            ->get();

        foreach ($folders as $folder) {
            $tree[] = [
                'type' => 'folder',
                'id' => $folder->id,
                'name' => $folder->name,
                'children' => $this->generateTreeView($organization, $folder),
            ];
        }

        foreach ($files as $file) {
            $tree[] = [
                'type' => 'file',
                'id' => $file->id,
                'name' => $file->file_name,
                'description' => $file->description,
                'file_path' => $file->file_path,
                'file_type' => $file->file_type,
                'file_size' => $file->file_size,
            ];
        }

        return $tree;
    }

    /**
     * Create or update a folder if it exists.
     *
     * @param Organization|null $organization
     * @param string $folderName
     * @param Folder|null $parentFolder
     * @return Folder
     */
    public function createOrUpdateFolder(?Organization $organization = null, string $folderName, ?Folder $parentFolder = null): Folder
    {
        if ($parentFolder && $organization && $parentFolder->organization_id !== $organization->id) {
            throw new Exception('Parent folder does not belong to the specified organization.');
        }

        $attributes = [
            'name' => $folderName,
            'parent_id' => $parentFolder?->id,
        ];

        if ($organization) {
            $attributes['organization_id'] = $organization->id;
        }

        if (!$organization && $parentFolder) {
            $attributes['organization_id'] = $parentFolder->organization_id;
        }

        return Folder::updateOrCreate(
            $attributes,
            $attributes
        );
    }

    /**
     * Upload a file and store its metadata.
     * If no folder is provided, the file is stored in the organization's root directory.
     *
     * @param UploadedFile $file
     * @param Organization|null $organization
     * @param Folder|null $folder
     * @param string|null $description
     * @return void
     * @throws ValidationException
     */
    public function uploadFile(UploadedFile $file, ?Organization $organization = null, ?Folder $folder = null, ?string $description = null): void
    {
        $this->validateFile($file);

        ProcessFileUpload::dispatch($file, $organization, $folder, $description);
    }

    /**
     * Validate the uploaded file.
     *
     * @param UploadedFile $file
     * @return void
     * @throws ValidationException
     */
    protected function validateFile(UploadedFile $file): void
    {
        $rules = [
            'file' => 'required|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:20480',
        ];

        $validator = Validator::make(['file' => $file], $rules);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }

    /**
     * Store the file in the specified folder or organization's root directory.
     *
     * @param UploadedFile $file
     * @param Organization|null $organization
     * @param Folder|null $folder
     * @return string
     */
    public function storeFile(UploadedFile $file, ?Organization $organization = null, ?Folder $folder = null): string
    {
        $user = Auth::user();
        $organizationId = $organization?->id ?? $user?->organization_id;

        $basePath = 'files';

        if ($organizationId) {
            $basePath = "organizations/{$organizationId}/{$basePath}";
        }

        if ($folder && $folder->id) {
            $basePath = "{$basePath}/folders/{$folder->id}";
        }

        if (!Storage::exists($basePath)) {
            Storage::makeDirectory($basePath);
        }

        return $file->store($basePath, 'local');
    }

    /**
     * Create a file record and save it to the database.
     *
     * @param UploadedFile $file
     * @param Organization|null $organization
     * @param Folder|null $folder
     * @param string $filePath
     * @param string|null $description
     * @return File
     */
    public function createFileRecord(UploadedFile $file, ?Organization $organization = null, ?Folder $folder, string $filePath, ?string $description = null): File
    {
        return File::create([
            'folder_id' => $folder?->id,
            'file_name' => $file->getClientOriginalName(),
            'file_path' => $filePath,
            'file_type' => $file->getClientMimeType(),
            'file_size' => $file->getSize(),
            'description' => $description,
            'organization_id' => $organization?->id ?? Auth::user()->organization_id,
        ]);
    }

    /**
     * Delete a file from storage and database.
     *
     * @param File $file
     * @return void
     * @throws Exception
     */
    public function deleteFile(File $file): void
    {
        ProcessFileDelete::dispatch($file);
    }

    /**
     * Rename an existing file.
     *
     * @param File $file
     * @param string $newName
     * @return File
     */
    public function renameFile(File $file, string $newName): File
    {
        try {
            $file->file_name = $newName;
            $file->save();
            return $file;
        } catch (Exception $e) {
            Log::error('Error renaming file: ' . $e->getMessage());
            throw new Exception('Failed to rename file.');
        }
    }

    /**
     * Move a file to a different folder.
     *
     * @param File $file
     * @param Folder $newFolder
     * @return void
     */
    public function moveFile(File $file, Folder $newFolder): void
    {
        ProcessFileMove::dispatch($file, $newFolder);
    }

    /**
     * Move a folder to another folder.
     *
     * @param Folder $folder
     * @param Folder $newParentFolder
     * @return void
     * @throws Exception
     */
    public function moveFolder(Folder $folder, Folder $newParentFolder): void
    {
        ProcessFolderMove::dispatch($folder, $newParentFolder);
    }

    /**
     * Store the file in a new location based on the folder.
     *
     * @param File $file
     * @param Folder $folder
     * @return string
     */
    public function storeFileAs(File $file, Folder $folder): string
    {
        $oldPath = $file->file_path;
        $newFolderPath = $folder->id ? "folders/{$folder->id}" : 'files';
        $newPath = $newFolderPath . '/' . basename($oldPath);

        if (!Storage::exists($newFolderPath)) {
            Storage::makeDirectory($newFolderPath);
        }

        Storage::move($oldPath, $newPath);

        return $newPath;
    }

    /**
     * Generate a temporary download URL for a file.
     *
     * @param File $file
     * @return string
     * @throws Exception
     */
    public function generateTemporaryUrl(File $file, int $expirationMinutes = 60): string
    {
        try {
            return Storage::temporaryUrl($file->file_path, now()->addMinutes($expirationMinutes));
        } catch (Exception $e) {
            Log::error('Error generating temporary URL: ' . $e->getMessage());
            throw new Exception('Failed to generate temporary URL.');
        }
    }

    /**
     * Download a file.
     *
     * @param File $file
     * @return StreamedResponse
     * @throws Exception
     */
    public function downloadFile(File $file): StreamedResponse
    {
        try {
            return Storage::download($file->file_path, $file->file_name);
        } catch (Exception $e) {
            Log::error('Error downloading file: ' . $e->getMessage());
            throw new Exception('Failed to download file.');
        }
    }
}