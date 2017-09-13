<?php
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP
 *
 * This content is released under the MIT License (MIT)
 *
 * Copyright (c) 2014 - 2015, British Columbia Institute of Technology
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @package	CodeIgniter
 * @author	EllisLab Dev Team
 * @copyright	Copyright (c) 2008 - 2014, EllisLab, Inc. (http://ellislab.com/)
 * @copyright	Copyright (c) 2014 - 2015, British Columbia Institute of Technology (http://bcit.ca/)
 * @license	http://opensource.org/licenses/MIT	MIT License
 * @link	http://codeigniter.com
 * @since	Version 1.0.0
 * @filesource
 */
defined('BASEPATH') OR exit('No direct script access allowed');

$lang['db_invalid_connection_str'] = 'Det går inte att avgöra databasinställningar baserade på anslutningssträngen du skickade.';
$lang['db_unable_to_connect'] = 'Det går inte att ansluta till databasservern med hjälp av de medföljande inställningarna.';
$lang['db_unable_to_select'] = 'Det går inte att välja den angivna databasen: %s';
$lang['db_unable_to_create'] = 'Det går inte att skapa den angivna databasen: %s';
$lang['db_invalid_query'] = 'Förfrågan du skickade är inte giltig.';
$lang['db_must_set_table'] = 'Du måste välja databastabell som ska användas med din förfrågan.';
$lang['db_must_use_set'] = 'Du måste använda "set"-metoden för att uppdatera en post.';
$lang['db_must_use_index'] = 'Du måste ange ett index att matcha mot för massuppdateringar.';
$lang['db_batch_missing_index'] = 'En eller flera rader som skickats för massuppdatering saknar det angivna indexet.';
$lang['db_must_use_where'] = 'En radering måste innehålla en "where"- eller "like"-klausul.';
$lang['db_del_must_use_where'] = 'Att hämta fält kräver namnet på tabellen som en parameter.';
$lang['db_field_param_missing'] = 'Den här funktionen är inte tillgänglig för den databas du använder.';
$lang['db_unsupported_function'] = 'Transaktionsfel: transaktionen återförd.';
$lang['db_transaction_failure'] = 'Det går inte att radera den angivna databasen.';
$lang['db_unable_to_drop'] = 'Metoden stöds inte hos databasplattformen som används.';
$lang['db_unsupported_feature'] = 'Komprimeringsformatet du valde stöds inte av din server.';
$lang['db_unsupported_compression'] = 'Komprimeringsformatet du valde stöds inte av din server.';
$lang['db_filepath_error'] = 'Det går inte att skriva data till sökvägen du skickade.';
$lang['db_invalid_cache_path'] = 'Sökvägen du skickade är inte giltig eller skrivbar.';
$lang['db_table_name_required'] = 'Ett tabellnamn krävs för denna operation.';
$lang['db_column_name_required'] = 'Ett kolumnnamn krävs för denna operation.';
$lang['db_column_definition_required'] = 'En kolumndefinition krävs för denna operation.';
$lang['db_unable_to_set_charset'] = 'Det går inte att ställa in teckenuppsättning för klientanslutningen: %s';
$lang['db_error_heading'] = 'En databasfel uppstod';
