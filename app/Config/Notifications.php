<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Notifications extends BaseConfig
{
    /**
     * Destinatario fijo de los avisos de contacto. Definido aquí (no en base
     * de datos) según la decisión del plan de proyecto.
     */
    public string $contactRecipient = 'gerencia@technoliner.co';

    public string $contactRecipientName = 'Carlos Arturo Olarte González';

    public string $politicaVersionVigente = '1.0';
}
