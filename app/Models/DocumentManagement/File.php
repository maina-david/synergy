<?php

namespace App\Models\DocumentManagement;

use App\Models\DocumentManagement\Folder;
use App\Models\User;
use App\Traits\Users\AssociatedToUser;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class File extends Model
{
    use HasFactory, HasUuids, SoftDeletes, AssociatedToUser;

    protected $fillable = [
        'folder_id',
        'user_id',
        'file_name',
        'file_path',
        'file_type',
        'file_size',
        'description'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'file_size' => 'integer',
    ];

    /**
     * Get the folder that owns the File.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function folder(): BelongsTo
    {
        return $this->belongsTo(Folder::class);
    }

    /**
     * Get the user who uploaded the File.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function uploadedBy(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}