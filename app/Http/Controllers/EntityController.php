<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEntityRequest;
use App\Http\Requests\UpdateEntityRequest;
use App\Models\Entity;
use App\Models\Category;
use App\Services\ApiService;

class EntityController extends Controller
{
    protected $apiService;

    public function __construct(ApiService $apiService)
    {
        $this->apiService = $apiService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $entries = $this->apiService->fetchEntries();

        $filteredEntries = collect($entries)->filter(function ($entry) {
            return in_array($entry['Category'], ['Animals', 'Security']);
        });

        foreach ($filteredEntries as $entry) {
            $category = Category::where('name', $entry['Category'])->first();

            Entity::create([
                'api' => $entry['API'],
                'description' => $entry['Description'],
                'link' => $entry['Link'],
                'category_id' => $category->id,
            ]);
        }

        return response()->json(['message' => 'Updated Register']);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEntityRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function apiCategory($category)
    {
        $categoryModel = Category::where('name', $category)->first();

        if (!$categoryModel) {
            return response()->json([
                'success' => false,
                'message' => 'Category not found'
            ], 404);
        }
        $entities = Entity::where('category_id', $categoryModel->id)->get();

        $formattedEntities = $entities->map(function ($entity) use ($categoryModel) {
            return [
                'api' => $entity->api,
                'description' => $entity->description,
                'link' => $entity->link,
                'category' => [
                    'id' => $categoryModel->id,
                    'category' => $categoryModel->name
                ]
            ];
        });
        return response()->json([
            'success' => true,
            'data' => $formattedEntities
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Entity $entity)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEntityRequest $request, Entity $entity)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Entity $entity)
    {
        //
    }
}
