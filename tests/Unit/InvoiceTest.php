<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class InvoiceTest extends TestCase
{
    /** @test */
    public function a_file_should_be_required()
    {
        $response = $this->post('api/upload-invoice',
            [
                'file' => ''
            ]);

        $this->assertArrayHasKey('file', $response->json(['errors']));

    }

    /** @test */
    public function a_file_should_be_of_mime_csv()
    {
        $response = $this->post('api/upload-invoice',
            [
                'file' => 'jpeg'
            ]);

        $this->assertArrayHasKey('file', $response->json(['errors']));

    }
}
