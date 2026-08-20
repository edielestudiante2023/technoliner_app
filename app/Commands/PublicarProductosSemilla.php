<?php

namespace App\Commands;

use App\Models\ProductoImagenModel;
use App\Models\ProductoModel;
use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

/**
 * Activa los 6 productos sembrados por ProductosSeeder y vincula su imagen
 * principal (ya subida previamente a public/uploads/productos/). Idempotente:
 * puede correrse varias veces sin duplicar imágenes ni pisar datos.
 */
class PublicarProductosSemilla extends BaseCommand
{
    protected $group       = 'App';
    protected $name        = 'productos:publicar-semilla';
    protected $description = 'Activa los productos sembrados y vincula su imagen principal.';

    private const DATOS = [
        'liner-sensitivo-con-y-sin-aluminio' => [
            'destacado' => 1,
            'ruta'      => '22fd519572668985d6f1ff39fb893b47.webp',
            'alt'       => 'Liner sensitivo con y sin aluminio sobre fondo azul, distintos tamaños',
            'bytes'     => 141960,
        ],
        'liner-eco-sensitive' => [
            'destacado' => 1,
            'ruta'      => '10713b70b06637a862088371889729e2.webp',
            'alt'       => 'Liner Eco-sensitive con sello ECO Sensitive impreso, distintos tamaños',
            'bytes'     => 56516,
        ],
        'espumado-epe-densidad-250-y-300' => [
            'destacado' => 0,
            'ruta'      => '026bf19fc441576c1f4f49506716ee87.webp',
            'alt'       => 'Liners espumados EPE en distintos tamaños y formas',
            'bytes'     => 48988,
        ],
        'liner-de-poliestireno' => [
            'destacado' => 0,
            'ruta'      => '20cd5b1e720a83516394f42ffbdfe4b3.webp',
            'alt'       => 'Liner de poliestireno en distintos tamaños',
            'bytes'     => 33614,
        ],
        'liner-de-induccion-de-una-pieza-pet-pvc' => [
            'destacado' => 0,
            'ruta'      => '370bbe160234dc3dd572e117aa3e9a07.webp',
            'alt'       => 'Liner de inducción de una pieza para envases PET y PVC',
            'bytes'     => 34944,
        ],
        'liner-de-induccion-de-doble-pieza-pe-y-pet-pvc' => [
            'destacado' => 1,
            'ruta'      => 'a426312db5e5a0dc75d6e513e6755580.webp',
            'alt'       => 'Liner de inducción de doble pieza, disco de aluminio y respaldo de pulpa',
            'bytes'     => 66946,
        ],
    ];

    public function run(array $params)
    {
        $productoModel = new ProductoModel();
        $imagenModel   = new ProductoImagenModel();

        foreach (self::DATOS as $slug => $datos) {
            $producto = $productoModel->where('slug', $slug)->first();

            if (! $producto) {
                CLI::error("No existe el producto con slug {$slug}. ¿Corriste el seeder?");

                continue;
            }

            $rutaAbsoluta = FCPATH . 'uploads/productos/' . $datos['ruta'];

            if (! is_file($rutaAbsoluta)) {
                CLI::error("Falta el archivo public/uploads/productos/{$datos['ruta']} para {$slug}.");

                continue;
            }

            $productoModel->update($producto['id'], [
                'activo'    => 1,
                'destacado' => $datos['destacado'],
            ]);

            $imagenExistente = $imagenModel
                ->where('producto_id', $producto['id'])
                ->where('ruta', $datos['ruta'])
                ->first();

            if ($imagenExistente) {
                CLI::write("{$slug}: activo, imagen ya vinculada.", 'green');

                continue;
            }

            $imagenModel->insert([
                'producto_id'     => $producto['id'],
                'ruta'            => $datos['ruta'],
                'nombre_original' => $datos['ruta'],
                'mime_type'       => 'image/webp',
                'tamano_bytes'    => $datos['bytes'],
                'alt_text'        => $datos['alt'],
                'es_principal'    => 1,
                'orden'           => 1,
                'created_at'      => date('Y-m-d H:i:s'),
            ]);

            CLI::write("{$slug}: activado y con imagen principal.", 'green');
        }
    }
}
