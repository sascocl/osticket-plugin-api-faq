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

return [
    'id'            => 'api:faq', # notrans
    'version'       => '1.0.0',
    'name'          => /* trans */ 'FAQ API',
    'author'        => 'Esteban De La Fuente Rubio',
    'description'   => /* trans */ 'Allows access to the FAQ via API.',
    'url'           => 'https://github.com/sascocl/osticket-plugin-api-faq',
    'plugin'        => 'api-faq.php:FaqApiPlugin'
];
