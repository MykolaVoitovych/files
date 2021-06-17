<?php

namespace App\Http\Controllers;


use App\Http\Requests\File\Store;
use App\Models\File;
use App\Models\Tag;

class FilesController extends Controller
{
    /**
     * @OA\Post(
     *      path="/file",
     *      operationId="storeFile",
     *      tags={"Store File"},
     *      description="Create a new file in the application",
     *      @OA\Parameter(
     *          name="file",
     *          description="The file",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="string",
     *              format="base64",
     *              example="data:image/jpeg;base64, binaryString"
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="tags",
     *          description="A list of tags associate with the file",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="array",
     *              format="array",
     *              @OA\Items(type="string", example={"Tag1", "Tag2"})
     *          )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              required={"id"},
     *              @OA\Property(property="id", type="integer", format="integer", example=1)
     *          ),
     *       ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Validation error",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="File property is required")
     *          )
     *      )
     * )
     */
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

        return response()->json([
            'id' => $file->id
        ])->setStatusCode(201);
    }

    /**
     * @OA\Get(
     *      path="/files/{search_query}",
     *      operationId="search",
     *      tags={"Search"},
     *      summary="Search",
     *      description="Search for files",
     *      @OA\Parameter(
     *          description="A string containing a list of tags. Tags prefixed with either + or - sign.
     *              The search should return all files associated with all of the + tags, excluding any files tagged with any of the - tags.",
     *          in="path",
     *          name="search_query",
     *          required=true,
     *          example="+Tag1-Tag2+Tag3",
     *          @OA\Schema(
     *              type="string",
     *              format="string"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              required={"id"},
     *              @OA\Property(property="files", type="array",
     *                  @OA\Items(ref="#/components/schemas/File"),
     *              ),
     *              @OA\Property(property="tags", type="array",
     *                  @OA\Items(
     *                      title="Tag",
     *                      type="object",
     *                      @OA\Property(property="id", type="integer", readOnly="true", example="1"),
     *                      @OA\Property(property="name", type="string", maxLength=255, example="TagName"),
     *                      @OA\Property(property="files_count", type="integer", example="2"),
     *                  ),
     *              ),
     *          )
     *      )
     * )
     */
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
