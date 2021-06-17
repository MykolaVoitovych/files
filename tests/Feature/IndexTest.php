<?php

namespace Tests\Feature;

use Tests\TestCase;

class IndexTest extends TestCase
{
    /**
     * @test
     * @return void
     */
    public function checkIndex()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
