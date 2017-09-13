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

$lang['email_must_be_array'] = 'Metoda de validare a e-mailului trebuie să treacă de o matrice.';
$lang['email_invalid_address'] = 'Adresă de e-mail invalidă: %s';
$lang['email_attachment_missing'] = 'Nu este posibilă localizarea următorului atașament de e-mail: %s';
$lang['email_attachment_unreadable'] = 'Nu este posibilă deschiderea acestui atașament: %s';
$lang['email_no_from'] = 'Nu poate fi trimis un e-mail fără antetul „De la”.';
$lang['email_no_recipients'] = 'Trebuie să includeți destinatarii: Către, Cc sau Bcc';
$lang['email_send_failure_phpmail'] = 'Nu este posibilă trimiterea e-mailului folosind e-mailul PHP (). Este posibil ca serverul dvs. să nu fie configurat pentru a trimite e-mailuri folosind această metodă.';
$lang['email_send_failure_sendmail'] = 'Nu este posibilă trimiterea e-mailului folosind Sendmail PHP. Este posibil ca serverul dvs. să nu fie configurat pentru a trimite e-mailuri folosind această metodă.';
$lang['email_send_failure_smtp'] = 'Nu este posibilă trimiterea e-mailului folosind PHP SMTP. Este posibil ca serverul dvs. să nu fie configurat pentru a trimite e-mailuri folosind această metodă.';
$lang['email_sent'] = 'Mesajul dvs. a fost trimis cu succes folosind următorul protocol: %s';
$lang['email_no_socket'] = 'Nu este posibilă deschiderea slotului către Sendmail. Vă rugăm să verificați setările.';
$lang['email_no_hostname'] = 'Nu ați specificat un nume de gazdă SMTP.';
$lang['email_smtp_error'] = 'A fost întâlnită următoarea eroare SMTP: %S';
$lang['email_no_smtp_unpw'] = 'Eroare: Trebuie să atribuiți un nume de utilizator și o parolă SMTP.';
$lang['email_failed_smtp_login'] = 'Trimiterea comenzii AUTH LOGIN a eșuat. Eroare: %s';
$lang['email_smtp_auth_un'] = 'Autentificarea numelui de utilizator a eșuat. Eroare: %s';
$lang['email_smtp_auth_pw'] = 'Autentificarea parolei a eșuat. Eroare: %s';
$lang['email_smtp_data_failure'] = 'Nu este posibilă trimiterea datelor: %s';
$lang['email_exit_status'] = 'Cod status de ieșire: %s';
