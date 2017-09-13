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
 * @since	Version 3.0.0
 * @filesource
 */
defined('BASEPATH') OR exit('No direct script access allowed');

$lang['migration_none_found'] = 'لم نتمكن من العثور على الترحيل.';
$lang['migration_not_found'] = 'لم نتمكن من العثور على ترحيل برقم إصدار: %s.';
$lang['migration_sequence_gap'] = 'هناك فجوة بين تسلسل الترحيل بالقرب من رقم الإصدار: %s.';
$lang['migration_multiple_version'] = 'هناك العديد من خطوات الترحيل لها نفس رقم الإصدار: %s.';
$lang['migration_class_doesnt_exist'] = 'لم نتمكن من العثور على تصنيف الترحيل "%s".';
$lang['migration_missing_up_method'] = 'تصنيف الترحيل "%s" لا يحتوي على أسلوب "علوي".';
$lang['migration_missing_down_method'] = 'تصنيف الترحيل "%s" لا يحتوي على أسلوب "سفلي".';
$lang['migration_invalid_filename'] = 'اسم الملف في الترحيل "%s" غير صالح.';
