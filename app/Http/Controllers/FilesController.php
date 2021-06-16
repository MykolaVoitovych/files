<?php

namespace App\Http\Controllers;


use App\Http\Requests\File\Store;
use App\Models\File;
use App\Models\Tag;

class FilesController extends Controller
{
    public function store(Store $request)
    {
        $tagsIds = Tag::whereIn('name', $request->get('tags'))
            ->get()
            ->pluck('id')
            ->toArray();

        $fileName = $request->file->getClientOriginalName();
        $request->file->storeAs('', $fileName, 'public');
        $file = File::create([
            'name' => $fileName
        ]);
        $file->tags()->sync($tagsIds);

        return $file;
    }

    public function search($query)
    {
        $execTags = explode('-', preg_replace('/\+\w+/', '', $query));
        $onlyTags = explode('+', preg_replace('/-\w+/', '', $query));
        $doesntHaveTags = array_filter($execTags, fn ($value) => !is_null($value) && $value !== '');
        $mustHaveTags = array_filter($onlyTags, fn ($value) => !is_null($value) && $value !== '');

        $files = File::doesntHaveTags($doesntHaveTags)
            ->mustHaveTags($mustHaveTags)
            ->get();

        $fileIds = $files->pluck('id')->toArray();

        $tags = Tag::withCount(['files' => function ($query) use ($fileIds) {
            $query->whereIn('id', $fileIds);
        }])->whereHas('files', function ($query) use ($fileIds) {
            $query->whereIn('id', $fileIds);
        })->whereNotIn('name', $onlyTags)
            ->get();

        return response()->json([
            'files' => $files->pluck('name'),
            'tags' => $tags
        ]);
    }
}
