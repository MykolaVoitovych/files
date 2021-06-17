<?php

namespace Tests\Feature;

use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StoreTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test store file with tags
     *
     * @test
     * @return void
     */
    public function checkStoreFile()
    {
        $tags = Tag::all()->pluck('name')->random(rand(1, 3));
        $data = [
            'tags' => $tags,
            'file' => new \Illuminate\Http\UploadedFile(resource_path('test.txt'), 'test.txt', null, null, true),
        ];
        $response = $this->postJson(route('file.store'), $data);

        $response->assertStatus(201);
        $response->assertJsonStructure([
            'id'
        ]);
    }

    /**
     * Test store file with tags
     *
     * @test
     * @return void
     */
    public function checkStoreFileValidation()
    {
        $data = [
            'tags' => '',
            'file' => '',
        ];

        $response = $this->postJson(route('file.store'), $data);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['tags', 'file']);

        $data['tags'] = [121];
        $response = $this->postJson(route('file.store'), $data);
        $response->assertJsonValidationErrors(['tags.0', 'file']);
    }
}
