<?php

namespace App\Packages\CorePackage\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

abstract class BaseController extends Controller
{
    /**
     * The model instance.
     *
     * @var Model
     */
    protected $model;

    /**
     * Resource name for messages.
     *
     * @var string
     */
    protected $resourceName = 'item';

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $items = $this->model->paginate(10);

        return response()->json($items);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @throws ValidationException
     */
    public function storeBase(Request $request, array $validationRules): JsonResponse
    {
        $validated = $request->validate($validationRules);
        $item = $this->model->create($validated);

        return response()->json($item, 201);
    }

    /**
     * Display the specified resource.
     */
    public function showBase(int $id): JsonResponse
    {
        $item = $this->model->findOrFail($id);

        return response()->json($item);
    }

    /**
     * Update the specified resource in storage.
     *
     * @throws ValidationException
     */
    public function updateBase(Request $request, int $id, array $validationRules): JsonResponse
    {
        $item = $this->model->findOrFail($id);
        $validated = $request->validate($validationRules);
        $item->update($validated);

        return response()->json($item);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroyBase(int $id): JsonResponse
    {
        $item = $this->model->findOrFail($id);
        $item->delete();

        return response()->json(['message' => ucfirst($this->resourceName).' deleted successfully']);
    }
}
