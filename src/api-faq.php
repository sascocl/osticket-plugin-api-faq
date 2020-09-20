<?php

/**
 * Plugin for osTicket for query the FAQ via API
 * Copyright (C) 2020 SASCO SpA (https://sasco.cl)
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 * You should have received a copy of the GNU Affero General Public License
 * along with this program. If not, see <https://www.gnu.org/licenses/>.
 */

require_once(INCLUDE_DIR.'class.plugin.php');
require_once(INCLUDE_DIR.'class.signal.php');

define('API_FAQ_PLUGIN_ROOT', dirname(__FILE__));

spl_autoload_register(['FaqApiPlugin', 'autoload']);

/**
 * Clase que crea las rutas necesarias para ser despachadas al llamar a la API
 * @author Esteban De La Fuente Rubio, DeLaF (esteban[at]sasco.cl)
 * @version 2020-09-20
 */
class FaqApiPlugin extends Plugin
{

    public static function autoload($className)
    {
        $className = ltrim($className, '\\');
        $fileName = '';
        $namespace = '';
        if ($lastNsPos = strrpos($className, '\\')) {
            $namespace = substr($className, 0, $lastNsPos);
            $className = substr($className, $lastNsPos + 1);
            $fileName = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
        }
        $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';
        $fileName = 'include/' . $fileName;
        if (file_exists ( API_FAQ_PLUGIN_ROOT . DIRECTORY_SEPARATOR . $fileName )) {
            require $fileName;
        }
    }

    public function bootstrap()
    {
        Signal::connect('api', [
            'FaqApiPlugin',
            'callbackDispatch'
        ]);
    }

    static public function callbackDispatch($object, $data)
    {
        $object->append(url('^/faq/',
            patterns('FaqApiController',
                url_get("^search\.(?P<format>json|xml)$", 'search'),
            )
        ));
    }

}
