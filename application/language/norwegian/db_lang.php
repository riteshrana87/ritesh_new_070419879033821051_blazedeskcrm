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

$lang['db_invalid_connection_str'] = 'Kan ikke bestemme innstillinger for databasen basert på forbindelsene som ble lagt inn.';
$lang['db_unable_to_connect'] = 'Kan ikke koble til databaseserveren med de valgte innstillingene. ';
$lang['db_unable_to_select'] = 'Kan ikke velge de spesifiserte databasene: %s';
$lang['db_unable_to_create'] = 'Kan ikke opprette de spesifiserte databasene: %s';
$lang['db_invalid_query'] = 'Informasjon du la inn er ikke gyldig.';
$lang['db_must_set_table'] = 'Du må velge databasetabellen som skal brukes sammen med forespørselen din. ';
$lang['db_must_use_set'] = 'Du må bruke "Velg"-metoden for å oppdatere registreringen. ';
$lang['db_must_use_index'] = 'Du må spesifisere en index som skal matches med oppdateringene. ';
$lang['db_batch_missing_index'] = 'En eller flere rekker som er lagt inn for oppdatering mangler den spesifiserte indexen.';
$lang['db_must_use_where'] = 'Oppdateringer kan kun gjennomføres hvis de inneholder en "Hvor" klausul';
$lang['db_del_must_use_where'] = 'Slettinger tillates kun hvis de inneholder en "Hvor"- og "Like"-klausul.';
$lang['db_field_param_missing'] = 'For å hente områder kreves det at du bruker tabellnavnet som parameter.';
$lang['db_unsupported_function'] = 'Denne funksjonen er ikke tilgjengelig for databasen som er i bruk.';
$lang['db_transaction_failure'] = 'Transaksjonsfeil: Rollback utført.';
$lang['db_unable_to_drop'] = 'Kan ikke slippe den spesifiserte databasen..';
$lang['db_unsupported_feature'] = 'Funskjonen støttes ikke av databaseplatformen du bruker.';
$lang['db_unsupported_compression'] = 'Filkompresjonsformatet du har valgt støttes ikke av serveren din.';
$lang['db_filepath_error'] = 'Kunne ikke skrive data til den valgte filbanen.';
$lang['db_invalid_cache_path'] = 'Bufferfilbanen du oppga er ikke gyldig, eller så mangler du skrivetillatelse.';
$lang['db_table_name_required'] = 'Et tabellnavn kreves for denne operasjonen';
$lang['db_column_name_required'] = 'Et kolonnenavn kreves for denne operasjonen.';
$lang['db_column_definition_required'] = 'En kolonnedefinisjon kreves for denne operasjonen.';
$lang['db_unable_to_set_charset'] = 'Kan ikke opprette tilkobling til klient. Bokstavsett: %s';
$lang['db_error_heading'] = 'Det oppstod en feil i databasen.';
