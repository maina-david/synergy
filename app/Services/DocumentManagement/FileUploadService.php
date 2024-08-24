<?php

namespace App\Services\DocumentManagement;

use App\Models\DocumentManagement\File;
use App\Models\DocumentManagement\Folder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Storage;
use Exception;

class FileUploadService
{
    /**
     * Create or update a folder if it exists.
     *
     * @param  string $folderName
     * @param  Folder|null $parentFolder
     * @return \App\Models\DocumentManagement\Folder
     */
    protected function createOrUpdateFolder(string $folderName, ?Folder $parentFolder = null): Folder
    {
        return Folder::updateOrCreate(
            [
                'name' => $folderName,
                'parent_id' => $parentFolder?->id,
            ]
        );
    }

    /**
     * Upload a file and store its metadata.
     * If no folder is provided, the file is stored in the organization's root directory.
     *
     * @param  \Illuminate\Http\UploadedFile $file
     * @param  Folder|null $folder
     * @param  string|null $description
     * @return \App\Models\DocumentManagement\File
     * @throws \Illuminate\Validation\ValidationException
     */
    public function uploadFile(UploadedFile $file, ?Folder $folder = null, ?string $description = null): File
    {
        $this->validateFile($file);

        $filePath = $this->storeFile($file, $folder);

        return $this->createFileRecord($file, $folder, $filePath, $description);
    }

    /**
     * Validate the uploaded file.
     *
     * @param  \Illuminate\Http\UploadedFile $file
     * @return void
     * @throws \Illuminate\Validation\ValidationException
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
     * @param  \Illuminate\Http\UploadedFile $file
     * @param  Folder|null $folder
     * @return string
     */
    protected function storeFile(UploadedFile $file, ?Folder $folder = null): string
    {
        $user = Auth::user();
        $organizationId = $user && $user->organization_id ? $user->organization_id : null;

        $folderPath = $folder && $folder->id ? "folders/{$folder->id}" : 'files';

        if ($organizationId) {
            $folderPath = $folder
                ? "organizations/{$organizationId}/{$folderPath}"
                : "organizations/{$organizationId}/files";
        }

        $disk = Storage::disk('public');

        if (!$disk->exists($folderPath)) {
            $disk->makeDirectory($folderPath);
        }

        return $file->store($folderPath, 'public');
    }

    /**
     * Create a file record and save it to the database.
     *
     * @param  \Illuminate\Http\UploadedFile $file
     * @param  Folder|null $folder
     * @param  string $filePath
     * @param  string|null $description
     * @return \App\Models\DocumentManagement\File
     */
    protected function createFileRecord(UploadedFile $file, ?Folder $folder = null, string $filePath, ?string $description = null): File
    {
        return File::create([
            'folder_id' => $folder?->id,
            'file_name' => $file->getClientOriginalName(),
            'file_path' => $filePath,
            'file_type' => $file->getClientMimeType(),
            'file_size' => $file->getSize(),
            'description' => $description,
        ]);
    }

    /**
     * Delete a file from storage and database.
     *
     * @param  \App\Models\DocumentManagement\File $file
     * @return bool
     * @throws \Exception
     */
    public function deleteFile(File $file): bool
    {
        if (Storage::disk('public')->exists($file->file_path)) {
            Storage::disk('public')->delete($file->file_path);
        }

        return $file->delete();
    }

    /**
     * Rename an existing file.
     *
     * @param  \App\Models\DocumentManagement\File $file
     * @param  string $newName
     * @return \App\Models\DocumentManagement\File
     * @throws \Exception
     */
    public function renameFile(File $file, string $newName): File
    {
        $file->file_name = $newName;
        $file->save();

        return $file;
    }

    /**
     * Move a file to a different folder.
     *
     * @param  \App\Models\DocumentManagement\File $file
     * @param  Folder $newFolder
     * @return \App\Models\DocumentManagement\File
     * @throws \Exception
     */
    public function moveFile(File $file, Folder $newFolder): File
    {
        $newPath = $this->storeFileAs($file, $newFolder);

        $file->file_path = $newPath;
        $file->folder_id = $newFolder->id; 
        $file->save();

        return $file;
    }

    /**
     * Store the file in a new location based on the folder.
     *
     * @param  \App\Models\DocumentManagement\File $file
     * @param  Folder $folder
     * @return string
     */
    protected function storeFileAs(File $file, Folder $folder): string
    {
        $disk = Storage::disk('public');
        $oldPath = $file->file_path;
        $newFolderPath = $folder->id ? "folders/{$folder->id}" : 'files';
        $newPath = $newFolderPath . '/' . basename($oldPath);

        if (!$disk->exists($newFolderPath)) {
            $disk->makeDirectory($newFolderPath);
        }

        $disk->move($oldPath, $newPath);

        return $newPath;
    }
}