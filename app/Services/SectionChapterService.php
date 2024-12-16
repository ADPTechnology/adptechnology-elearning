<?php

namespace App\Services;


use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;

use App\Models\{CourseSection, File, SectionChapter};
use Datatables;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Owenoj\LaravelGetId3\GetId3;
use Pion\Laravel\ChunkUpload\Handler\SingleUploadHandler;

class SectionChapterService
{
    public function getDataTable($section_id)
    {
        $query = SectionChapter::where('section_id', $section_id)
            ->with([
                'file' => fn ($q) =>
                $q->where('file_type', 'videos')
            ])
            ->withCount([
                'files as files_general_count' => fn ($q) =>
                $q->where('file_type', 'archivos')
            ]);

        $allChapters = Datatables::of($query)
            ->editColumn('duration', function ($chapter) {
                return $chapter->duration . ' minutos';
            })
            ->editColumn('description', function ($chapter) {
                $description = $chapter->description;
                if (strlen($chapter->description) > 100) {
                    $description =  mb_substr($chapter->description, 0, 100, 'UTF-8') . ' ...';
                }
                return $description;
            })
            ->addColumn('view', function ($chapter) {
                if ($chapter->file) {
                    $btn = '<a href="javascript:void(0);" class="preview-chapter-video-button"
                                data-url="' . route('admin.freeCourses.chapters.getVideoData', $chapter) . '">
                                <i class="fa-solid fa-video"></i>
                            </a>';
                }

                return $btn ?? '-';
            })
            ->addColumn('content', function ($chapter) {

                $btn = '<button data-id="' . $chapter->id . '"
                            data-send="' . route('admin.freeCourses.chapters.getContentDetail', $chapter) . '"
                            data-url="' . route('admin.freeCourses.chapters.updateContent', $chapter) . '"
                            data-original-title="edit" class="me-3 edit btn btn-dark btn-sm
                            showContentChapter-btn">
                                <i class="fa-solid fa-person-chalkboard"></i>
                        </button>';

                $btn .= '<button data-id="' . $chapter->id . '"
                            data-send="' . route('admin.freeCourses.chapters.getFilesData', $chapter) . '"
                            data-url="' . route('admin.freeCourses.chapters.storeFiles', $chapter) . '"
                            data-original-title="edit" class="me-3 edit btn btn-primary btn-sm
                            showDocsChapter">
                            <i class="fa-solid fa-file-lines"></i>
                        </button>';

                return $btn;
            })
            ->addColumn('action', function ($chapter) {
                $btn = '<button data-id="' . $chapter->id . '"
                                    data-url="' . route('admin.freeCourses.chapters.update', $chapter) . '"
                                    data-send="' . route('admin.freeCourses.chapters.edit', $chapter) . '"
                                    data-original-title="edit" class="me-3 edit btn btn-warning btn-sm
                                    editChapter"><i class="fa-solid fa-pen-to-square"></i>
                        </button>';

                if ($chapter->files_general_count == 0) {

                    $btn .= '<button href="javascript:void(0)" data-id="' .
                        $chapter->id . '" data-original-title="delete"
                                    data-url="' . route('admin.freeCourses.chapters.delete', $chapter) .
                        '" class="ms-3 delete btn btn-danger btn-sm
                                    deleteChapter"><i class="fa-solid fa-trash-can"></i></button>';
                }

                return $btn;
            })
            ->rawColumns(['view', 'content', 'action'])
            ->make(true);

        return $allChapters;
    }

    public function store($request, CourseSection $section, $storage)
    {
        $lastOrder = $section->getChapterLastOrder();

        $data = $request->all();

        $chapter = SectionChapter::create($data + [
            "chapter_order" => $lastOrder + 1,
            "duration" => 0,
            "section_id" => $section->id
        ]);

        $receiver = $request->hasFile('file') ? new FileReceiver("file", $request, SingleUploadHandler::class) : false;

        if ($receiver) {

            $save = $receiver->receive();

            if ($save->isFinished()) {

                $file = $save->getFile();

                $videoId3 = new GetId3($file);
                $duration = round($videoId3->getPlaytimeSeconds() / 60);

                $chapter->update([
                    'duration' => $duration
                ]);

                if ($chapter) {
                    $file_type = 'videos';
                    $category = 'cursoslibres';
                    $belongsTo = 'cursoslibres';
                    $relation = 'one_one';

                    app(FileService::class)->store(
                        $chapter,
                        $file_type,
                        $category,
                        $file,
                        $storage,
                        $belongsTo,
                        $relation
                    );
                }
            }
        }

        return $chapter;

        throw new Exception(config('parameters.exception_message'));
    }

    public function update($request, SectionChapter $chapter, $storage)
    {
        $duration = $chapter->duration;
        $data = $request->all();
        $order = $data['chapter_order'];

        if ($order != $chapter->chapter_order) {
            SectionChapter::where('section_id', $chapter->section_id)
                ->where('chapter_order', $order)
                ->update([
                    "chapter_order" => $chapter->chapter_order
                ]);
        }

        $receiver = $request->hasFile('file') ? new FileReceiver("file", $request, SingleUploadHandler::class) : false;

        if ($receiver) {

            $save = $receiver->receive();

            if ($save->isFinished()) {

                $file = $save->getFile();

                app(FileService::class)->destroy($chapter->file, $storage);

                $videoId3 = new GetId3($file);
                $duration = round($videoId3->getPlaytimeSeconds() / 60);

                $file_type = 'videos';
                $category = 'cursoslibres';
                $belongsTo = 'cursoslibres';
                $relation = 'one_one';

                app(FileService::class)->store(
                    $chapter,
                    $file_type,
                    $category,
                    $file,
                    $storage,
                    $belongsTo,
                    $relation
                );
            }
        }

        $isUpdated = $chapter->update($data + [
            "duration" => $duration
        ]);

        if ($isUpdated) {
            return true;
        }

        throw new Exception(config('parameters.exception_message'));
    }

    public function destroy(SectionChapter $chapter, $storage)
    {
        $section_id = $chapter->courseSection->id;
        $chapter->progressUsers()->detach();

        if ($chapter->file) {
            app(FileService::class)->destroy($chapter->file, $storage);
        }

        $isDeleted = $chapter->delete();

        if ($isDeleted) {
            return $this->updateAllOrders($section_id);
        }

        throw new Exception(config('parameters.exception_message'));
    }

    public function updateAllOrders($section_id)
    {
        $chapters = SectionChapter::where('section_id', $section_id)
            ->orderBy('chapter_order', 'ASC')->get();

        $order = 1;
        foreach ($chapters as $remanentChapter) {
            $remanentChapter->update([
                "chapter_order" => $order
            ]);
            $order++;
        }

        return true;
    }


    public function deleteVideo(SectionChapter $chapter, $storage)
    {
        if ($chapter->file) {
            app(FileService::class)->destroy($chapter->file, $storage);

            return true;
        }

        throw new Exception(config('parameters.exception_message'));
    }

    public function updateContent($request, SectionChapter $chapter)
    {
        return $chapter->update([
            'content' => $request['content']
        ]);
    }

    public function storeFiles($request, SectionChapter $chapter, $storage)
    {
        if ($request->hasFile('files')) {

            $file_type = 'archivos';
            $category = 'cursoslibres';
            $belongsTo = 'cursoslibres';
            $relation = 'one_many';

            $success = [];

            $files = $request->file('files');

            foreach ($files as $file) {
                if (
                    app(FileService::class)->store(
                        $chapter,
                        $file_type,
                        $category,
                        $file,
                        $storage,
                        $belongsTo,
                        $relation
                    )
                ) {
                    array_push($success, $file);
                };
            }

            return count($files) == count($success);
        }

        throw new Exception(config('parameters.exception_message'));
    }


    public function uploadChunk(Request $request, CourseSection $section)
    {
        //
        $receiver = new FileReceiver("file", $request, SingleUploadHandler::class);

        if ($receiver->isUploaded() === false) {
            return response()->json([
                "done" => false,
                "error" => "Upload failed"
            ]);
        }

        $save = $receiver->receive();

        if ($save->isFinished()) {
            $file = $save->getFile();

            $fileName = $file->getClientOriginalName();
            $folder = 'uploads/videos';
            $path = Storage::disk('public')->putFileAs($folder, $file, $fileName);

            return response()->json([
                "done" => true,
                "path" => $path,
                "filename" => $fileName
            ]);
        }

        return response()->json([
            "done" => false,
            "percentage" => $save->handler()->getPercentageDone()
        ]);
    }



    // public function uploadChunk(Request $request, CourseSection $section)
    // {

    //     $storage = env('FILESYSTEM_DRIVER');

    //     $file = $request->file('chunk');
    //     $chunkIndex = $request->input('chunk_number');
    //     $totalChunks = $request->input('total_chunks');
    //     $fileName = $request->input('file_name');
    //     $filePath = 'uploads/videos/';
    //     $directory = 'uploads/videos/';

    //     $chunkPath = $fileName . '.part' . $chunkIndex;
    //     Storage::disk($storage)->putFileAs($directory, $file, $chunkPath);

    //     if ($chunkIndex == $totalChunks) {
    //         $finalFilePath = $this->combineChunks($directory, $fileName, $totalChunks);
    //         $this->uploadToS3($finalFilePath, $fileName, $request, $section);
    //     }

    //     return response()->json(['success' => true]);
    // }

    // private function combineChunks($filePath, $fileName, $totalChunks)
    // {
    //     $storage = env('FILESYSTEM_DRIVER');
    //     $disk = Storage::disk($storage);

    //     $finalFilePath = $filePath . $fileName;

    //     $combinedChunks = '';

    //     for ($i = 1; $i <= $totalChunks; $i++) {
    //         $chunkPath = $filePath . $fileName . '.part' . $i;
    //         $chunk = $disk->get($chunkPath);
    //         $combinedChunks .= $chunk;

    //         $disk->delete($chunkPath);
    //     }

    //     $disk->put($finalFilePath, $combinedChunks);

    //     return $finalFilePath;
    // }


    // private function uploadToS3($finalFilePath, $fileName, Request $request, CourseSection $section)
    // {
    //     $lastOrder = $section->getChapterLastOrder();
    //     $storage = env('FILESYSTEM_DRIVER');


    //     $chapter = SectionChapter::create([
    //         'title' => 'xs2',
    //         'description' => 'xs2',
    //         "chapter_order" => $lastOrder + 1,
    //         "duration" => 0,
    //         "section_id" => $section->id
    //     ]);

    //     $file_type = 'videos';
    //     $category = 'cursoslibres';
    //     $belongsTo = 'cursoslibres';
    //     $relation = 'one_one';

    //     $newFilePath = "$file_type/$category/" . $fileName;
    //     Storage::disk($storage)->move($finalFilePath, $newFilePath);
    //     $file_url = Storage::disk($storage)->url($newFilePath);


    //     $stored_file = new File([
    //         "file_path" => $newFilePath,
    //         "file_url" => $file_url,
    //         "file_type" => $file_type,
    //         "category" => $category,
    //     ]);

    //     $chapter->files()->save($stored_file);
    // }


    public function destroyFile(File $file, $storage)
    {
        return app(FileService::class)->destroy($file, $storage);
    }
}
