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

$lang['db_invalid_connection_str'] = 'İlettiğiniz bağlantı dizisine bağlı veritabanı ayarları bulunamadı.';
$lang['db_unable_to_connect'] = 'Sağladığınız ayarlar ile veritabanı sunucunuza bağlantı gerçekleştirilemedi.';
$lang['db_unable_to_select'] = 'Belirtilen veritabanı seçilemedi: %s';
$lang['db_unable_to_create'] = 'Belirtilen veritabanı yaratılamadı: %s';
$lang['db_invalid_query'] = 'İlettiğiniz sorgulama geçerli değil.';
$lang['db_must_set_table'] = 'Sorgulama ile kullanılacak veritabanı tablosunu belirlemelisiniz.';
$lang['db_must_use_set'] = 'Bir girdiyi güncellemek için "set" yöntemini kullanmalısınız.';
$lang['db_must_use_index'] = 'Toplu güncelleştirmeler için eşleşecek bir dizin belirtmelisiniz.';
$lang['db_batch_missing_index'] = 'Toplu güncelleştirme için iletilen bir veya daha fazla satırın dizinleri eksik.';
$lang['db_must_use_where'] = 'Bir "where" ibaresi içermedikçe, güncellemelere izin verilmemektedir.';
$lang['db_del_must_use_where'] = 'Bir "where" veya "like" ibaresi içermedikçe, silmelere izin verilmemektedir.';
$lang['db_field_param_missing'] = 'Alanları getirme işlemi tablonun adını bir parametre olarak ihtiyaç duymaktadır.';
$lang['db_unsupported_function'] = 'Bu özellik kullandığınız veritabanı için uygun değildir.';
$lang['db_transaction_failure'] = 'İşlem hatası: Başlangıca dönüş gerçekleştirildi.';
$lang['db_unable_to_drop'] = 'Belirtilen veritabanı bırakılamadı.';
$lang['db_unsupported_feature'] = 'Kullandığınız veritabanı platformunun desteklenmeyen özelliği.';
$lang['db_unsupported_compression'] = 'Seçtiğiniz dosya sıkıştırma biçimi sunucunuz tarafından desteklenmemektedir.';
$lang['db_filepath_error'] = 'Belirttiğiniz dosya yoluna veri yazılamadı.';
$lang['db_invalid_cache_path'] = 'Belirttiğiniz önbellek yolu geçerli veya yazılabilir değil.';
$lang['db_table_name_required'] = 'Bu işlem için bir tablo adı gereklidir.';
$lang['db_column_name_required'] = 'Bu işlem için bir kolon adı gereklidir.';
$lang['db_column_definition_required'] = 'Bu işlem için bir kolon tanımı gereklidir.';
$lang['db_unable_to_set_charset'] = 'İstemci bağlantı karakter takımı belirlenemedi: %s';
$lang['db_error_heading'] = 'Bir Veritabanı Hatası Oluştu';
