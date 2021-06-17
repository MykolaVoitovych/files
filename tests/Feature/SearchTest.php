<?php

namespace Tests\Feature;

use App\Models\File;
use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SearchTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test store file with tags
     *
     * @test
     * @return void
     */
    public function checkSearch()
    {
        $file = File::factory()->create();
        $tagsIds = Tag::all()->pluck('id')->random(3);
        $file->tags()->sync($tagsIds);

        $searchTag = $file->tags->first()->name;
        $tagsWithoutSearch = $file->tags->where('name', '!=', $searchTag);

        $responseTags = [];
        foreach ($tagsWithoutSearch as $tag) {
            array_push($responseTags, [
                'id' => $tag->id,
                'name' => $tag->name,
                'files_count' => 1,
            ]);
        }

        $query = '+' . $searchTag;
        $response = $this->get(route('search', $query));

        $response->assertOk();
        $response->assertJson([
            'files' => [
                $file->name
            ],
            'tags' => $responseTags
        ]);

        $query = '-' . $tagsWithoutSearch->first()->name;
        $response = $this->get(route('search', $query));

        $response->assertOk();
        $response->assertJson([
            'files' => [],
            'tags' => []
        ]);
    }
}
