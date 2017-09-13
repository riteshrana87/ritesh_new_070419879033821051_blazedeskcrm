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

$lang['db_invalid_connection_str'] = 'لم نتمكن من تحديد إعدادات قاعدة البيانات وفقًا لرابط الاتصال الذي أرسلته إلينا.';
$lang['db_unable_to_connect'] = 'لم نتمكن من الاتصال بخادم قاعدة البيانات الخاصة بك باستخدام الإعدادات التي أدخلتها.';
$lang['db_unable_to_select'] = 'لم نتمكن من اختيار قاعدة البيانات المختارة: %s';
$lang['db_unable_to_create'] = 'لم نتمكن من إنشاء قاعدة البيانات المختارة: %s';
$lang['db_invalid_query'] = 'الاستفسار الذي أدخلته غير صالح.';
$lang['db_must_set_table'] = 'يجب تعيين جدول قاعدة البيانات المستخدم المرتبط باستفسارك.';
$lang['db_must_use_set'] = 'يجب استخدام طريقة التعيين لتحديث مُدخل بعينه.';
$lang['db_must_use_index'] = 'يجب تعيين الدليل المتوافق مع التحديثات الدورية.';
$lang['db_batch_missing_index'] = 'واحد أو أكثر من الصفوف المرسلة للتحديث الدوري مفقود في الدليل المختار.';
$lang['db_must_use_where'] = 'غير مسموح بالتعديلات غير تلك التي تتضمن شرط "حيث".';
$lang['db_del_must_use_where'] = 'غير مسموح بحذف البيانات غير تلك الأوامر التي تتضمن شرط "حيث" أو "مثل".';
$lang['db_field_param_missing'] = 'إدراج الحقول يتطلب استخدام اسم الجدول كمعامل.';
$lang['db_unsupported_function'] = 'هذه الخاصية غير متوافرة لقاعدة البيانات التي تستخدمها.';
$lang['db_transaction_failure'] = 'فشل المعاملة: العودة إلى نقطة الأصل.';
$lang['db_unable_to_drop'] = 'لم نتمكن من إسقاط قاعدة البيانات المحددة.';
$lang['db_unsupported_feature'] = 'أنت الآن تستخدم خاصية غير مدعومة من منصة قاعدة البيانات التي تستخدمها.';
$lang['db_unsupported_compression'] = 'صيغة الملف المضغوط غير متوافقة مع الخادم الخاص بك.';
$lang['db_filepath_error'] = 'لم نتمكن من كتابة البيانات إلى المسار الذي أدخلته.';
$lang['db_invalid_cache_path'] = 'مسار ذاكرة التخزين المؤقت الذي أدخلته غير سليم.';
$lang['db_table_name_required'] = 'رجاء إدخال اسم الجدول لتلك العملية.';
$lang['db_column_name_required'] = 'رجاء إدخال اسم العمود لتلك العملية.';
$lang['db_column_definition_required'] = 'رجاء إدخال مُعرف العمود لتلك العملية.';
$lang['db_unable_to_set_charset'] = 'لم نتمكن من ضبط مجموعة حروف الاتصال بالعميل: %s';
$lang['db_error_heading'] = 'حدث خطأ في قاعدة البيانات';
