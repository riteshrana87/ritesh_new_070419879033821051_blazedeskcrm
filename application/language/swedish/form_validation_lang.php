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

$lang['form_validation_required']		= 'Fältet {field} är obligatoriskt.';
$lang['form_validation_isset']			= 'Fältet {field} måste ha ett värde.';
$lang['form_validation_valid_email']		= 'Fältet {field} måste innehålla en giltig e-postadress.';
$lang['form_validation_valid_emails']		= 'Fältet {field} måste innehålla alla giltiga e-postadresser.';
$lang['form_validation_valid_url']		= 'Fältet {field} måste innehålla en giltig webbadress.';
$lang['form_validation_valid_ip']		= 'Fältet {field} måste innehålla en giltig IP.';
$lang['form_validation_min_length']		= 'Fältet {field} måste vara minst {param} tecken.';
$lang['form_validation_max_length']		= 'Fältet {field} kan inte överstiga {param} tecken.';
$lang['form_validation_exact_length']		= 'Fältet {field} måste vara exakt {param} tecken.';
$lang['form_validation_alpha']			= 'Fältet {field} får endast innehålla alfabetiska tecken.';
$lang['form_validation_alpha_numeric']		= 'Fältet {field} får endast innehålla alfanumeriska tecken.';
$lang['form_validation_alpha_numeric_spaces']	= 'Fältet {field} får endast innehålla alfanumeriska tecken och blanksteg.';
$lang['form_validation_alpha_dash']		= 'Fältet {field} kan endast innehålla bokstäver, siffror, understreck och bindestreck.';
$lang['form_validation_numeric']		= 'Fältet {field} får bara innehålla siffror.';
$lang['form_validation_is_numeric']		= 'Fältet {field} får bara innehålla numeriska tecken.';
$lang['form_validation_integer']		= 'Fältet {field} måste innehålla ett heltal.';
$lang['form_validation_regex_match']		= 'Fältet {field} matchar inte fältet {param}.';
$lang['form_validation_matches']		= 'Fältet {field} måste skilja sig från fältet {param}.';
$lang['form_validation_differs']		= 'Fältet {field} måste innehålla ett unikt värde.';
$lang['form_validation_is_unique'] 		= 'Fältet {field} får endast innehålla siffror.';
$lang['form_validation_is_natural']		= 'Fältet {field} får endast innehålla siffror.';
$lang['form_validation_is_natural_no_zero']	= 'Fältet {field} får endast innehålla siffror och måste vara större än noll.';
$lang['form_validation_decimal']		= 'Fältet {field} måste innehålla ett decimaltal.';
$lang['form_validation_less_than']		= 'Fältet {field} måste innehålla ett tal mindre än {param}.';
$lang['form_validation_less_than_equal_to']	= 'Fältet {field} måste innehålla ett tal mindre än eller lika med {param}.';
$lang['form_validation_greater_than']		= 'Fältet {field} måste innehålla ett tal större än {param}.';
$lang['form_validation_greater_than_equal_to']	= 'Fältet {field} måste innehålla ett tal större än eller lika med {param}.';
$lang['form_validation_error_message_not_set']	= 'Det går inte att hitta ett felmeddelande relaterat till ditt fält {field}.';
$lang['form_validation_in_list']		= 'Fältet {field} måste vara en av: {param}.';
