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

require_once(INCLUDE_DIR.'class.api.php');

/**
 * Controlador de la API de las FAQ con las acciones de los recursos de la API
 * @author Esteban De La Fuente Rubio, DeLaF (esteban[at]sasco.cl)
 * @version 2020-09-20
 */
class FaqApiController extends ApiController
{

    public function search($format)
    {
        global $ost;
        // check api key
        $this->requireApiKey();
        // filters for the search
        if (empty($_GET['q'])) {
            return $this->exerr(400, __('You must specified a query string'));
        }
        $filters = $_GET;
        foreach ($filters as &$filter) {
            $filter = db_input($filter, false);
        }
        // base url
        $url_base = db_input($ost->config->getUrl() . 'kb/faq.php?', false);
        // search cols
        if (!empty($filters['search_in_answer'])) {
            $search_cols = 'f.question, f.answer, f.keywords';
        } else {
            $search_cols = 'f.question, f.keywords';
        }
        // search mode
        if (!empty($filters['search_mode'])) {
            if ($filters['search_mode'] == 'natural') {
                $search_mode = 'NATURAL LANGUAGE';
            } else {
                $search_mode = 'BOOLEAN';
            }
        } else {
            $search_mode = 'BOOLEAN';
        }
        // words for search
        $filters['q'] = implode(' ', array_map(function($e) {return '+'.$e;}, explode(' ', preg_replace('!\s+!', ' ', $filters['q']))));
        // exec query and get results
        $res = db_query('
            SELECT
                f.faq_id,
                f.question,
                TRIM(f.keywords) AS keywords,
                CONCAT(\'' . $url_base . 'id=\', f.faq_id) AS url,
                c.category_id,
                c.name AS category_name,
                CONCAT(\'' . $url_base . 'cid=\', c.category_id) AS category_url
            FROM
                ost_faq AS f
                JOIN ost_faq_category AS c ON c.category_id = f.category_id
            WHERE
                c.ispublic = true
                AND f.ispublished = true
                AND MATCH (' . $search_cols . ') AGAINST (\'' . $filters['q'] . '\' IN ' . $search_mode . ' MODE)
            ORDER BY c.name, f.question
        ');
        if ($res === false) {
            return $this->exerr(500, __('SQL error: empty res from db_query'));
        }
        $result = db_assoc_array($res);
        db_free_result($res);
        // prepare response from result
        if ($format == 'json') {
            $response = json_encode($result);
        } else if ($format == 'xml') {
            // TODO: return XML from array result
            return $this->exerr(405, __('Format XML not allowed, please, use JSON instead'));
        }
        // return results in the format requested
        $this->response(200, $response, 'application/'.$format);
    }

    public function response($code, $resp, $contentType = 'text/html', $charset = 'UTF-8')
    {
        Http::response($code, $resp, $contentType, $charset);
        exit();
    }

}
