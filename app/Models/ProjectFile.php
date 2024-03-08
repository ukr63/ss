<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectFile extends Model
{
    use HasFactory;

    protected $fillable = ['project_id', 'file_path'];
    protected $table = 'project_file';

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id', 'id', 'project');
    }
}
