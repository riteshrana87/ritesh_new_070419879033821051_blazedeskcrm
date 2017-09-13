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

$lang['form_validation_required']		= 'يجب إدخال الحقل {field}.';
$lang['form_validation_isset']			= 'يجب إدخال قيمة للحقل {field}.';
$lang['form_validation_valid_email']		= 'يجب أن يحتوي الحقل {field} على عنوان بريد إلكتروني صالح.';
$lang['form_validation_valid_emails']		= 'يجب أن يحتوي الحقل {field} على جميع عناوين البريد الإلكتروني الصالحة.';
$lang['form_validation_valid_url']		= 'يجب أن يحتوي الحقل {field} على عنوان URL صالح.';
$lang['form_validation_valid_ip']		= 'يجب أن يحتوي الحقل {field} على عنوان بروتوكول انترنت ساري.';
$lang['form_validation_min_length']		= 'يجب أن يحتوي الحقل {field} على عدد حروف {param} على الأقل.';
$lang['form_validation_max_length']		= 'لا يمكن أن تزداد عدد الحروف في الحقل {field} عن {param}.';
$lang['form_validation_exact_length']		= 'يجب أن تكون عدد الحروف في الحقل {field} بالضبط {param} أحرف.';
$lang['form_validation_alpha']			= 'يجب أن يحتوي الحقل {field} فقط على حروف أبجدية.';
$lang['form_validation_alpha_numeric']		= 'يجب أن يحتوي الحقل {field} فقط على حروف أبجدية وأرقام.';
$lang['form_validation_alpha_numeric_spaces']	= 'يجب أن يحتوي الحقل {field} فقط على حروف أبجدية رقمية.';
$lang['form_validation_alpha_dash']		= 'يجب أن يحتوي الحقل {field} فقط على حروف أبجدية رقمية ومسافات بين الأحرف.';
$lang['form_validation_numeric']		= 'يجب أن يحتوي الحقل {field} فقط على حروف أبجدية رقمية وشرطات سفلية وشرطات.';
$lang['form_validation_is_numeric']		= 'يجب أن يحتوي الحقل {field} فقط على حروف رقمية.';
$lang['form_validation_integer']		= 'يجب أن يحتوي الحقل {field} فقط على عدد صحيح.';
$lang['form_validation_regex_match']		= 'الحقل {field} ليس بالصيغة السليمة.';
$lang['form_validation_matches']		= 'حقل {field} لا يطابق حقل {param}.';
$lang['form_validation_differs']		= 'يجب أن يختلف الحقل {field} عن الحقل {param}.';
$lang['form_validation_is_unique'] 		= 'يجب أن يحتوي الحقل {field} على قيمة فريدة.';
$lang['form_validation_is_natural']		= 'يجب أن يحتوي الحقل {field} على أرقام فقط.';
$lang['form_validation_is_natural_no_zero']	= 'يجب أن يحتوي الحقل {field} على أرقام أكبر من الصفر فقط.';
$lang['form_validation_decimal']		= 'يجب أن يحتوي الحقل {field} على رقم عشري.';
$lang['form_validation_less_than']		= 'يجب أن يحتوي الحقل {field} على رقم أقل من {param}.';
$lang['form_validation_less_than_equal_to']	= 'يجب أن يحتوي حقل {field} على رقم أقل من أو يساوي {param}.';
$lang['form_validation_greater_than']		= 'يجب أن يحتوي حقل {field} على رقم أكبر من {param}.';
$lang['form_validation_greater_than_equal_to']	= 'يجب أن يحتوي حقل {field} على رقم أكبر من أو يساوي {param}.';
$lang['form_validation_error_message_not_set']	= 'لم نتمكن من عرض رسالة خطأ تتعلق باسم حقل {field} الخاص بك.';
$lang['form_validation_in_list']		= 'يجب أن يكون الحقل {field} جزء من: {param}.';
