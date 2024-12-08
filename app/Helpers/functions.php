<?php

use App\Helpers\Classes\ResponseHelpers;
use Illuminate\Support\Str;

if (!function_exists('jdd')) {
    function jdd(...$data): void
    {
        ResponseHelpers::dd($data);
    }
}

if (!function_exists('generateUniqueSlug')) {
    function generateUniqueSlug($builder, ?string $slug, ?int $id = null): string
    {
        $slug = Str::slug($slug);

        $count = $builder::query()
            ->where('slug', $slug)
            ->when($id, fn($query) => $query->where('id', '!=', $id))
            ->count();

        if ($count === 0):
            return $slug;
        else:
            $slugParts = explode('-', $slug);
            $lastPart = end($slugParts);
            $lastPart = is_numeric($lastPart) && $lastPart > 0 ? $lastPart : 0;
            if ($lastPart === $count):
                array_pop($slugParts);
                $slug = implode('-', $slugParts);
                return generateUniqueSlug($builder, "$slug-" . ($count + 1), $id);
            else:
                $slug = str_replace("-$lastPart", '', $slug);
                $lastPart = $lastPart ?: 1;
                return generateUniqueSlug($builder, "$slug-" . ($lastPart + 1), $id);
            endif;
        endif;
    }
}
