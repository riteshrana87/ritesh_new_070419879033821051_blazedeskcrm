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

$lang['email_must_be_array'] = 'Eposta doğrulama yöntemine bir dizin geçilmelidir.';
$lang['email_invalid_address'] = 'Geçersiz eposta adresi: %s';
$lang['email_attachment_missing'] = 'Takip eden eposta eki bulunamadı: %s';
$lang['email_attachment_unreadable'] = 'Bu ek dosya açılamadı: %s';
$lang['email_no_from'] = '"Kimden" başlığı olmadan eposta gönderilemez.';
$lang['email_no_recipients'] = 'Alıcılar eklemelisiniz: Kime, Bilgi, Gizli';
$lang['email_send_failure_phpmail'] = 'PHP mail() kullanılarak eposta yollanamıyor. Sunucunuz bu metodu kullanarak eposta yollamak için yapılandırılmamış olabilir.';
$lang['email_send_failure_sendmail'] = 'PHP Sendmail kullanılarak eposta yollanamıyor. Sunucunuz bu metodu kullanarak eposta yollamak için yapılandırılmamış olabilir.';
$lang['email_send_failure_smtp'] = 'PHP SMTP kullanılarak eposta yollanamıyor. Sunucunuz bu metodu kullanarak eposta yollamak için yapılandırılmamış olabilir.';
$lang['email_sent'] = 'Mesajınız takip eden protokol kullanılarak başarıyla gönderildi: %s';
$lang['email_no_socket'] = 'Sendmail için bir soket açılamadı. Lütfen ayarları kontrol edin.';
$lang['email_no_hostname'] = 'Bir SMTP sunucu adı belirtmediniz.';
$lang['email_smtp_error'] = 'Takip eden SMTP hatası ile karşılaşıldı: %s';
$lang['email_no_smtp_unpw'] = 'Hata: Bir SMTP kullanıcı adı ve şifresi belirlemelisiniz.';
$lang['email_failed_smtp_login'] = 'AUTH LOGIN komutunun gönderimi başarısız oldu. Hata: %s';
$lang['email_smtp_auth_un'] = 'Kullanıcı adı doğrulaması başarısız oldu. Hata: %s';
$lang['email_smtp_auth_pw'] = 'Şifre doğrulaması başarısız oldu. Hata: %s';
$lang['email_smtp_data_failure'] = 'Veri gönderilemiyor: %s';
$lang['email_exit_status'] = 'Çıkış durum kodu: %s';
