<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    use HasFactory;

    protected $table = 'project';

    public function files(): HasMany
    {
        return $this->hasMany(ProjectFile::class, 'project_id', 'id');
    }
}
