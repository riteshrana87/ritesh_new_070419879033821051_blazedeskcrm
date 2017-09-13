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

$lang['db_invalid_connection_str'] = 'Nije moguće odrediti postavke baze podataka na temelju niza veze koji ste poslali.';
$lang['db_unable_to_connect'] = 'Nije moguće povezati se na vaš poslužitelj baze podataka koristeći navedene postavke.';
$lang['db_unable_to_select'] = 'Nije moguće odabrati naznačenu bazu podataka: %s';
$lang['db_unable_to_create'] = 'Nije moguće kreirati naznačenu bazu podataka: %s';
$lang['db_invalid_query'] = 'Upit koji ste unijeli nije valjan.';
$lang['db_must_set_table'] = 'Morate podesiti tablicu baze podataka koja će se koristiti sa vašim upitom.';
$lang['db_must_use_set'] = 'Morate koristiti "set" metodu za ažuriranje unosa.';
$lang['db_must_use_index'] = 'Morate navesti indeks koji će odgovarati serijama ažuriranja.';
$lang['db_batch_missing_index'] = 'Jednom ili na više redaka koje ste podnijeli za serije ažuriranja nedostaje određeni indeks.';
$lang['db_must_use_where'] = 'Ažuriranja nisu dopuštena, osim ako ne sadrže "where" klauzulu.';
$lang['db_del_must_use_where'] = 'Brisanja nisu dopuštena, osim ako ne sadrže "where" ili "like" klauzulu.';
$lang['db_field_param_missing'] = 'Za preuzimanje polja zahtijeva se ime tablice kao parametar.';
$lang['db_unsupported_function'] = 'Ova značajka nije dostupna za bazu podataka koju koristite.';
$lang['db_transaction_failure'] = 'Neuspješna transakcija: Izveden je povraćaj na staro.';
$lang['db_unable_to_drop'] = 'Nije moguće spustiti naznačenu bazu podataka.';
$lang['db_unsupported_feature'] = 'Nije podržana značajka platforme za bazu podataka koju koristite.';
$lang['db_unsupported_compression'] = 'Format kompresije datoteka koji ste izabrali nije podržan od vašeg poslužitelja.';
$lang['db_filepath_error'] = 'Nije moguće upisivati podatke u putanju datoteke koju ste poslali.';
$lang['db_invalid_cache_path'] = 'Keš putanja koju ste poslali nije važeća i u njoj se ne može pisati.';
$lang['db_table_name_required'] = 'Potreban je naziv tablice za tu aktivnost.';
$lang['db_column_name_required'] = 'Potreban je naziv kolone za tu aktivnost.';
$lang['db_column_definition_required'] = 'Potrebna je definicija kolone za tu aktivnost.';
$lang['db_unable_to_set_charset'] = 'Nije moguće postaviti skup znakova za vezu s klijentom: %s';
$lang['db_error_heading'] = 'Dogodila se pogreška u bazi podataka.';
