<?php

namespace App\Models\DocumentManagement;

use App\Traits\BelongsToOrganization;
use App\Traits\Users\AssociatedToUser;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Folder extends Model
{
    use HasFactory, HasUuids, SoftDeletes, BelongsToOrganization, AssociatedToUser;

    protected $fillable = [
        'organization_id',
        'user_id',
        'name',
        'parent_id',
        'is_being_moved'
    ];

    /**
     * Get the parent folder for this Folder.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Folder::class, 'parent_id');
    }

    /**
     * Get all of the child folders for this Folder.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function children(): HasMany
    {
        return $this->hasMany(Folder::class, 'parent_id');
    }

    /**
     * Get all of the files contained in this Folder.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function files(): HasMany
    {
        return $this->hasMany(File::class);
    }

    /**
     * Check if the folder is currently being moved.
     *
     * @return bool
     */
    public function isBeingMoved(): bool
    {
        return $this->is_being_moved;
    }

    /**
     * Set the folder as being moved.
     *
     * @return void
     */
    public function setBeingMoved(): void
    {
        $this->is_being_moved = true;
        $this->save();
    }

    /**
     * Clear the folder's moving status.
     *
     * @return void
     */
    public function clearBeingMoved(): void
    {
        $this->is_being_moved = false;
        $this->save();
    }
}