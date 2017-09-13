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

$lang['db_invalid_connection_str'] = 'Nu este posibilă determinarea setărilor bazei de date în funcție de șirul de conexiune trimis.';
$lang['db_unable_to_connect'] = 'Nu este posibilă conectarea la baza de date a serverului dvs. folosind setările furnizate.';
$lang['db_unable_to_select'] = 'Nu este posibilă selectarea bazei de date specificată: %s';
$lang['db_unable_to_create'] = 'Nu este posibilă crearea bazei de date specificată: %s';
$lang['db_invalid_query'] = 'Interogarea trimisă nu este validă.';
$lang['db_must_set_table'] = 'Tabelul bazei de date trebuie setat pentru a fi folosit în interogarea dvs.';
$lang['db_must_use_set'] = 'Trebuie să folosiți metoda „setare” pentru a actualiza o intrare.';
$lang['db_must_use_index'] = 'Trebuie să specificați un index care să se potrivească cu actualizările loturilor.';
$lang['db_batch_missing_index'] = 'Unuia sau mai multor rânduri trimise pentru actualizarea loturilor le lipsește indexul specificat.';
$lang['db_must_use_where'] = 'Actualizările nu sunt permise decât dacă acestea conțin clauza „where”.';
$lang['db_del_must_use_where'] = 'Eliminările nu sunt permise decât dacă acestea conțin clauza „where” sau „like”.';
$lang['db_field_param_missing'] = 'Preluarea câmpurilor necesită numele tabelului ca parametru.';
$lang['db_unsupported_function'] = 'Această caracteristică nu este disponibilă pentru baza de date pe care o folosiți.';
$lang['db_transaction_failure'] = 'Tranzacție eșuată: Revenire efectuată.';
$lang['db_unable_to_drop'] = 'Nu se poate renunța la baza de date specificată.';
$lang['db_unsupported_feature'] = 'Caracteristică neacceptată a platformei bazei de date pe care o folosiți.';
$lang['db_unsupported_compression'] = 'Formatul de compresie a fișierelor pe care îl folosiți nu este acceptat de serverul nostru.';
$lang['db_filepath_error'] = 'Nu este posibilă scrierea datelor pe calea fișierului trimisă.';
$lang['db_invalid_cache_path'] = 'Calea cache-ului trimisă nu este validă sau inscriptibilă.';
$lang['db_table_name_required'] = 'Este necesar un nume de tabel pentru această operațiune.';
$lang['db_column_name_required'] = 'Este necesar un nume de coloană pentru această operațiune.';
$lang['db_column_definition_required'] = 'Este necesară o definiție a coloanei pentru această operațiune.';
$lang['db_unable_to_set_charset'] = 'Nu este posibilă setarea setului caracterelor de conexiune al clientului: %s';
$lang['db_error_heading'] = 'A apărut o Eroare a Bazei de Date';
