<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ListPlaceRequest;
use App\Http\Requests\StorePlaceRequest;
use App\Http\Requests\UpdatePlaceRequest;
use App\Http\Resources\PlaceResource;
use App\Models\Place;
use App\Services\PlaceService;

class PlaceController extends Controller
{
    public function __construct(
        private readonly PlaceService $placeService
    ) {}

    public function index(ListPlaceRequest $request)
    {
        $places = $this->placeService->list($request->validated());

        return PlaceResource::collection($places);
    }

    public function store(StorePlaceRequest $request)
    {
        $place = $this->placeService->create($request->validated());

        return (new PlaceResource($place))
            ->response()
            ->setStatusCode(201);
    }

    public function show(Place $place)
    {
        return new PlaceResource($place);
    }

    public function update(UpdatePlaceRequest $request, Place $place)
    {
        $place = $this->placeService->update($place, $request->validated());

        return new PlaceResource($place);
    }

    public function destroy(Place $place)
    {
        $this->placeService->delete($place);

        return response()->json(null, 204);
    }

    public function showBySlug(string $slug)
    {
        $place = $this->placeService->findBySlugOrFail($slug);

        return new PlaceResource($place);
    }
}
