<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{CourseSection, User};

class SectionChapter extends Model
{
    use HasFactory;

    protected $table = 'section_chapters';
    protected $fillable = [
        'title',
        'description',
        'chapter_order',
        'duration',
        'section_id',
        'content'
    ];

    public function courseSection()
    {
        return $this->belongsTo(CourseSection::class, 'section_id', 'id');
    }

    public function progressUsers()
    {
        return $this->belongsToMany(User::class, 'user_course_progress', 'section_chapter_id', 'user_id')
                                    ->withPivot(['id', 'progress_time', 'last_seen', 'status'])->withTimestamps();
    }

    public function files()
    {
        return $this->morphMany(File::class, 'fileable');
    }

    public function file()
    {
        return $this->morphOne(File::class, 'fileable');
    }

    public function fileVideo()
    {
        return $this->morphOne(File::class, 'fileable');
    }

    public function loadRelationships()
    {
        return $this->load([
            'courseSection',
            'file' => fn ($query) =>
                $query->where('file_type', 'videos')
        ]);
    }

    public function loadVideo()
    {
        return $this->load(
            [
                'fileVideo' => fn ($query) =>
                $query->where('file_type', 'videos')
            ]
        );
    }

    public function loadFiles()
    {
        return $this->load([
            'files' => fn ($q) =>
                $q->where('file_type', 'archivos')
        ]);
    }
}
