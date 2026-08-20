<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ProductosSeeder extends Seeder
{
    public function run()
    {
        $now = date('Y-m-d H:i:s');

        $categorias = $this->db->table('producto_categorias')->select('id, slug')->get()->getResultArray();
        $idPorSlug  = array_combine(array_column($categorias, 'slug'), array_column($categorias, 'id'));

        $productos = [
            [
                'categoria' => 'sellos-sensibles-presion',
                'nombre'    => 'Liner sensitivo con y sin aluminio',
                'slug'      => 'liner-sensitivo-con-y-sin-aluminio',
                'resumen'   => 'Sello por presión sin necesidad de equipos de inducción; opción con aluminio para mayor barrera.',
                'descripcion_html' => '<p>Diseñado para adherirse al borde del envase mediante presión, proporcionando un sello de seguridad sin necesidad de equipos de inducción. La capa de aluminio ofrece una mayor barrera contra la humedad, el oxígeno y otros agentes externos, ayudando a preservar la calidad del producto y brindando evidencia de apertura.</p><p>Recomendado para productos secos.</p>',
                'especificaciones' => [
                    ['nombre' => 'Materiales compatibles', 'valor' => 'PE, PP, PS, PVC, PET, vidrio'],
                    ['nombre' => 'Industrias', 'valor' => 'Alimentos, farmacéutica, cosmética'],
                ],
            ],
            [
                'categoria' => 'sellos-sensibles-presion',
                'nombre'    => 'Liner Eco-sensitive',
                'slug'      => 'liner-eco-sensitive',
                'resumen'   => 'Sello sensible a presión, sin calor, con enfoque ecológico.',
                'descripcion_html' => '<p>Nuestros sellos sensibles a la presión ofrecen una solución práctica, segura y ecológica para el cierre de envases, proporcionando un sellado confiable sin necesidad de aplicar calor.</p>',
                'especificaciones' => [
                    ['nombre' => 'Materiales compatibles', 'valor' => 'PE, PP, PS, PVC, PET, vidrio'],
                    ['nombre' => 'Industrias', 'valor' => 'Farmacéutica, alimentaria, cosmética'],
                ],
            ],
            [
                'categoria' => 'liners-espumados',
                'nombre'    => 'Espumado EPE densidad 250 y 300',
                'slug'      => 'espumado-epe-densidad-250-y-300',
                'resumen'   => 'Polietileno expandido en densidades 250 y 300, para aplicaciones con altos estándares de limpieza.',
                'descripcion_html' => '<p>El EPE (Polietileno Expandido) es un material ideal para soluciones de empaque en las industrias de alimentos, farmacéutica, bebidas y cosmética. Incorpora un agente espumante conforme a la normativa europea, garantizando un material de alta calidad y desempeño.</p><p>Su estructura no favorece la proliferación de bacterias, hongos ni otros microorganismos, lo que lo convierte en una excelente opción para aplicaciones que requieren altos estándares de limpieza y seguridad.</p>',
                'especificaciones' => [
                    ['nombre' => 'Densidades disponibles', 'valor' => '250, 300'],
                    ['nombre' => 'Industrias', 'valor' => 'Alimentos, farmacéutica, bebidas, cosmética'],
                ],
            ],
            [
                'categoria' => 'liners-espumados',
                'nombre'    => 'Liner de poliestireno',
                'slug'      => 'liner-de-poliestireno',
                'resumen'   => 'Barrera contra fugas y contaminantes, compatible con diferentes tapas.',
                'descripcion_html' => '<p>Liner de poliestireno (PS) diseñado para proporcionar un sellado eficiente y una protección adicional al contenido del envase. Actúa como una barrera que ayuda a evitar fugas, minimiza el ingreso de contaminantes y preserva la calidad del producto durante su almacenamiento y transporte.</p><p>Compatible con una amplia variedad de tapas, ofreciendo un cierre confiable y un excelente desempeño en diferentes aplicaciones.</p>',
                'especificaciones' => [
                    ['nombre' => 'Industrias', 'valor' => 'Alimentos, farmacéutica, cosmética, veterinaria, química no agresiva'],
                ],
            ],
            [
                'categoria' => 'sellos-induccion',
                'nombre'    => 'Liner de inducción de una pieza PET/PVC',
                'slug'      => 'liner-de-induccion-de-una-pieza-pet-pvc',
                'resumen'   => 'Sello de inducción de una sola pieza, cierre hermético para envases PET y PVC.',
                'descripcion_html' => '<p>Sello de inducción de una sola pieza que proporciona un cierre hermético y seguro para envases fabricados en PET y PVC. Su diseño facilita una apertura práctica sin comprometer la integridad del producto, brindando protección y evidencia de manipulación.</p>',
                'especificaciones' => [
                    ['nombre' => 'Materiales compatibles', 'valor' => 'PET, PVC'],
                    ['nombre' => 'Industrias', 'valor' => 'Alimentos, farmacéutica, veterinaria, cosmética, química no agresiva'],
                ],
            ],
            [
                'categoria' => 'sellos-induccion',
                'nombre'    => 'Liner de inducción de doble pieza PE y PET/PVC',
                'slug'      => 'liner-de-induccion-de-doble-pieza-pe-y-pet-pvc',
                'resumen'   => 'Sellado por fusión mediante inducción, con protección de inviolabilidad.',
                'descripcion_html' => '<p>Proporciona un sellado por fusión auténtico. Este material ofrece la conocida protección de garantía de inviolabilidad mediante termosellado por inducción.</p>',
                'especificaciones' => [
                    ['nombre' => 'Materiales compatibles', 'valor' => 'PE, PET, PVC'],
                    ['nombre' => 'Industrias', 'valor' => 'Alimentos, bebidas, farmacéutica, cosmética, química, agroquímica'],
                ],
            ],
        ];

        foreach ($productos as $orden => $producto) {
            $existente = $this->db->table('productos')->where('slug', $producto['slug'])->get()->getRowArray();

            if ($existente) {
                continue;
            }

            $this->db->table('productos')->insert([
                'categoria_id'     => $idPorSlug[$producto['categoria']],
                'nombre'           => $producto['nombre'],
                'slug'             => $producto['slug'],
                'resumen'          => $producto['resumen'],
                'descripcion_html' => $producto['descripcion_html'],
                'activo'           => 0,
                'destacado'        => 0,
                'orden'            => $orden + 1,
                'created_at'       => $now,
                'updated_at'       => $now,
            ]);

            $productoId = $this->db->insertID();

            foreach ($producto['especificaciones'] as $especOrden => $espec) {
                $this->db->table('producto_especificaciones')->insert([
                    'producto_id' => $productoId,
                    'nombre'      => $espec['nombre'],
                    'valor'       => $espec['valor'],
                    'orden'       => $especOrden + 1,
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ]);
            }
        }
    }
}
