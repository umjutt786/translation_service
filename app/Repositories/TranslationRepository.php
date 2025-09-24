<?php

namespace App\Repositories;

use App\Models\Translation;

class TranslationRepository
{
    public function search(array $filters)
    {
        return Translation::query()
            ->when($filters['locale'] ?? null, fn($q, $locale) => $q->where('locale', $locale))
            ->when($filters['key'] ?? null, fn($q, $key) => $q->where('key', 'LIKE', "%$key%"))
            ->when($filters['tag'] ?? null, fn($q, $tag) => $q->whereJsonContains('tags', $tag))
            ->paginate(50);
    }

    public function create(array $data): Translation
    {
        return Translation::create($data);
    }

    public function update(Translation $translation, array $data): Translation
    {
        $translation->update($data);
        return $translation;
    }
}
