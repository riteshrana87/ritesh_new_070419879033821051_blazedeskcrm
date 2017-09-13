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

$lang['form_validation_required']		= 'Polje {field} je obavezno.';
$lang['form_validation_isset']			= 'Polje {field} mora imati vrijednost.';
$lang['form_validation_valid_email']		= 'Polje {field} mora sadržavati važeću adresu e-pošte.';
$lang['form_validation_valid_emails']		= 'Polje {field} mora sadržavati sve važeće adrese e-pošte.';
$lang['form_validation_valid_url']		= 'Polje {field} mora sadržavati valjani URL.';
$lang['form_validation_valid_ip']		= 'Polje {field} mora sadržavati valjanu IP adresu.';
$lang['form_validation_min_length']		= 'Polje {field} mora sadržavati najmanje {param} znakova.';
$lang['form_validation_max_length']		= 'Polje {field} ne može sadržavati više od {param} znakova.';
$lang['form_validation_exact_length']		= 'Polje {field} mora sadržavati točno {param} znakova.';
$lang['form_validation_alpha']			= 'Polje {field} može sadržavati samo abecedne znakove.';
$lang['form_validation_alpha_numeric']		= 'Polje {field} može sadržavati samo alfanumeričke znakove.';
$lang['form_validation_alpha_numeric_spaces']	= 'Polje {field} može sadržavati samo alfanumeričke znakove i prostore.';
$lang['form_validation_alpha_dash']		= 'Polje {field} može sadržavati samo alfanumeričke znakove, podvlake i crtice.';
$lang['form_validation_numeric']		= 'Polje {field} mora sadržavati samo brojeve.';
$lang['form_validation_is_numeric']		= 'Polje {field} mora sadržavati samo numeričke znakove.';
$lang['form_validation_integer']		= 'Polje {field} mora sadržavati cijeli broj.';
$lang['form_validation_regex_match']		= 'Polje {field} ne odgovara polju {param}.';
$lang['form_validation_matches']		= 'The {field} field does not match the {param} field.';
$lang['form_validation_differs']		= 'Polje {field} se mora razlikovati od polja {param}.';
$lang['form_validation_is_unique'] 		= 'Polje {field} mora sadržavati jedinstvenu vrijednost.';
$lang['form_validation_is_natural']		= 'Polje {field} mora sadržavati samo znamenke.';
$lang['form_validation_is_natural_no_zero']	= 'Polje {field} mora sadržavati samo znamenke koje moraju biti veće od nule.';
$lang['form_validation_decimal']		= 'Polje {field} mora sadržavati decimalni broj.';
$lang['form_validation_less_than']		= 'Polje {field} mora sadržavati broj manji od {param}';
$lang['form_validation_less_than_equal_to']	= 'Polje {field} mora sadržavati broj manji od ili jednak {param}.';
$lang['form_validation_greater_than']		= 'Polje {field} mora sadržavati broj veći od {param}.';
$lang['form_validation_greater_than_equal_to']	= 'Polje {field} mora sadržavati broj veći od ili jednak {param}.';
$lang['form_validation_error_message_not_set']	= 'Nije moguće pristupiti poruci o pogrešci koja odgovara vašem imenu polja {field}.';
$lang['form_validation_in_list']		= 'Polje {field} mora biti jedno od: {param}.';
