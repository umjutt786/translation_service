<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\StoreTranslationRequest;
use App\Http\Requests\UpdateTranslationRequest;
use App\Repositories\TranslationRepository;
use App\Models\Translation;
use Illuminate\Support\Facades\Cache;


class TranslationController extends Controller
{

    /**
     * @OA\Info(
     *     title="Laravel Translation API",
     *     version="1.0.0",
     *     description="API for managing translations",
     *     @OA\Contact(
     *         email="support@example.com"
     *     ),
     *     @OA\License(
     *         name="Apache 2.0",
     *         url="http://www.apache.org/licenses/LICENSE-2.0.html"
     *     )
     * )
     *
     * @OA\Server(
     *     url="http://localhost:9000",
     *     description="Local development server"
     * )
     *
     * @OA\Tag(
     *     name="Translations",
     *     description="API Endpoints for Translation Management"
     * )
     */


        public function __construct(private TranslationRepository $repo) {}


    /**
     * @OA\Get(
     *     path="/api/translations",
     *     tags={"Translations"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="locale",
     *         in="query",
     *         description="Locale for filtering translations",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of translations",
     *         @OA\JsonContent()
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     )
     * )
     */

    public function index()
    {
        return response()->json(
            $this->repo->search(request()->only(['locale', 'key', 'tag']))
        );
    }

    /**
     * @OA\Post(
     *     path="/api/translations",
     *     tags={"Translations"},
     *     summary="Create a new translation",
     *     description="Store a new translation in the database",
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"locale","key","value","tag"},
     *             @OA\Property(property="locale", type="string", example="en"),
     *             @OA\Property(property="key", type="string", example="welcome_message"),
     *             @OA\Property(property="value", type="string", example="Welcome to our site"),
     *             @OA\Property(property="tag", type="string", example="web")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Translation created successfully",
     *         @OA\JsonContent(type="object")
     *     ),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */

    public function store(StoreTranslationRequest $request)
    {
        return response()->json(
            $this->repo->create($request->validated()), 201
        );
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * @OA\Put(
     *     path="/api/translations/{id}",
     *     tags={"Translations"},
     *     summary="Update a translation",
     *     description="Update an existing translation by ID",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Translation ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="locale", type="string", example="en"),
     *             @OA\Property(property="key", type="string", example="welcome_message"),
     *             @OA\Property(property="value", type="string", example="Welcome to our site"),
     *             @OA\Property(property="tag", type="string", example="web")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Translation updated successfully",
     *         @OA\JsonContent(type="object")
     *     ),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=404, description="Not found")
     * )
     */

    public function update(UpdateTranslationRequest $request, Translation $translation)
    {
        return response()->json(
            $this->repo->update($translation, $request->validated())
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * @OA\Get(
     *     path="/api/translations/export/{locale}",
     *     tags={"Translations"},
     *     summary="Export translations for a given locale",
     *     description="Return all translations for a given locale as a JSON object",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="locale",
     *         in="path",
     *         required=true,
     *         description="Locale code (en, fr, es)",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Translations exported successfully",
     *         @OA\JsonContent(type="object", example={"welcome_message": "Welcome to our site"})
     *     ),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=404, description="Not found")
     * )
     */



    public function export(string $locale)
    {
        $translations = Cache::remember("translations_{$locale}", 60, function () use ($locale) {
            return Translation::where('locale', $locale)
                ->select('key', 'value')
                ->get()
                ->pluck('value', 'key');
        });

        return response()->json($translations, 200, [], JSON_UNESCAPED_UNICODE);
    }
}
