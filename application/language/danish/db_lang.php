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

$lang['db_invalid_connection_str'] = 'Unable to determine the database settings based on the connection string you submitted. Kunne ikke afgøre databaseindstillingerne baseret på forbindelsesstrengen du indsendte.';
$lang['db_unable_to_connect'] = 'Unable to connect to your database server using the provided settings. Kunne ikke forbinde til din databaseserver med de forudsatte indstilling.';
$lang['db_unable_to_select'] = 'Kunne ikke vælge den specificerede database: %s';
$lang['db_unable_to_create'] = 'Kunne ikke skabe den specificerede database: %s';
$lang['db_invalid_query'] = 'Den indsendte forespørgsel er ugyldig.';
$lang['db_must_set_table'] = 'Du skal indstille databaseskemaet til at blive brugt ved din forespørgsel.';
$lang['db_must_use_set'] = 'Du skal bruge ”indstil” metoden for at opdatere en indtastning.';
$lang['db_must_use_index'] = 'Du skal specificere et indeks til at matche for partiopdateringer.';
$lang['db_batch_missing_index'] = 'En eller flere af de indsendte rækker til partiopdatering mangler det specificerede indeks.';
$lang['db_must_use_where'] = 'Opdateringer er ikke tilladt medmindre de indeholder en ”hvor” klausul.';
$lang['db_del_must_use_where'] = 'Det er ikke tilladt at slette medmindre de indeholder en ”hvor” eller ”som” klausul.';
$lang['db_field_param_missing'] = 'For at hente felter kræves skemanavnet som en parameter.';
$lang['db_unsupported_function'] = 'Denne funktion er ikke tilgængelig for den database du bruger.';
$lang['db_transaction_failure'] = 'Transaktionsfejl: Efterregulerer.';
$lang['db_unable_to_drop'] = 'Kunne ikke droppe den specificerede database.';
$lang['db_unsupported_feature'] = 'Ikke-understøttet funktion på den database platform du bruger.';
$lang['db_unsupported_compression'] = 'Filkomprimeringsformatet du valgte er ikke understøttet af din server.';
$lang['db_filepath_error'] = 'Kunne ikke skrive data til filstien du har indsendt.';
$lang['db_invalid_cache_path'] = 'Cache-filstien du indsendte er ikke gyldig eller skrivbar.';
$lang['db_table_name_required'] = 'Der kræves et skemanavn for den handling.';
$lang['db_column_name_required'] = 'Der kræves et kolonnenavn for den handling.';
$lang['db_column_definition_required'] = 'Der kræves en kolonnedefination for den handling.';
$lang['db_unable_to_set_charset'] = 'Kunne ikke indstille klientforbindelseskarakter set: %s';
$lang['db_error_heading'] = 'Der Opstod en Fejl i Databasen';
