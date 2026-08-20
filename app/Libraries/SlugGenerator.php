<?php

namespace App\Libraries;

use CodeIgniter\Model;

/**
 * Genera slugs únicos sin tildes a partir de un texto, añadiendo
 * un sufijo numérico si ya existe uno igual en la tabla del modelo.
 */
class SlugGenerator
{
    public static function generar(Model $model, string $texto, string $campoSlug = 'slug', ?int $idExcluir = null): string
    {
        helper('url');

        $base = mb_url_title($texto, '-', true);
        $slug = $base;
        $i    = 2;

        while (true) {
            $builder = $model->where($campoSlug, $slug);

            if ($idExcluir !== null) {
                $builder->where($model->primaryKey . ' !=', $idExcluir);
            }

            if ($builder->countAllResults() === 0) {
                return $slug;
            }

            $slug = $base . '-' . $i;
            $i++;
        }
    }
}
