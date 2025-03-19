<?php
namespace Tests\Feature;

use Tests\TestCase;
use App\Services\ApiService;
use Illuminate\Support\Facades\Http;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ApiServiceTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_fetches_entries_from_api()
    {
        Http::fake([
            'https://web.archive.org/web/20240403172734/https://api.publicapis.org/entries' => Http::response([
                'entries' => [
                    ['API' => 'Test API', 'Category' => 'Animals'],
                    ['API' => 'Another API', 'Category' => 'Security'],
                ]
            ], 200)
        ]);

        $service = new ApiService();
        $entries = $service->fetchEntries();

        $this->assertCount(2, $entries);
        $this->assertEquals('Test API', $entries[0]['API']);
    }

    /** @test */
    public function it_uses_local_file_if_api_fails()
    {
        Http::fake([
            'https://api.publicapis.org/entries' => Http::response([], 500)
        ]);

        \Storage::fake();
        \Storage::put('entries.json', json_encode([
            'entries' => [
                ['API' => 'Local API', 'Category' => 'Animals']
            ]
        ]));

        $service = new ApiService();
        $entries = $service->fetchEntries();

        $this->assertCount(1, $entries);
        $this->assertEquals('Local API', $entries[0]['API']);
    }
}
