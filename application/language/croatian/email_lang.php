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

$lang['email_must_be_array'] = 'Metodi provjere e-pošte mora biti dodijeljen redak.';
$lang['email_invalid_address'] = 'Pogrešna adresa e-pošte: %s';
$lang['email_attachment_missing'] = 'Nije moguće pronaći sljedeći privitak e-pošte: %s';
$lang['email_attachment_unreadable'] = 'Nije moguće otvoriti ovaj privitak: %s';
$lang['email_no_from'] = 'Nije moguće poslati poruku e-pošte bez zaglavlja "Od".';
$lang['email_no_recipients'] = 'Morate uključiti primatelje: To (Prima), Cc (Kopija) ili Bcc (Skrivena kopija)';
$lang['email_send_failure_phpmail'] = 'Nije moguće poslati poruku e-pošte koristeći PHP poštu (). Vaš poslužitelj možda nije konfiguriran za slanje pošte koristeći ovu metodu.';
$lang['email_send_failure_sendmail'] = 'Nije moguće poslati poruku e-pošte koristeći PHP Slanje pošte. Vaš poslužitelj možda nije konfiguriran za slanje pošte koristeći ovu metodu.';
$lang['email_send_failure_smtp'] = 'Nije moguće poslati poruku e-pošte koristeći PHP SMTP. Vaš poslužitelj možda nije konfiguriran za slanje pošte koristeći ovu metodu.';
$lang['email_sent'] = 'Vaša poruka je uspješno poslana koristeći sljedeći protokol: %s';
$lang['email_no_socket'] = 'Nije moguće otvoriti soket za Slanje pošte. Molimo, provjerite postavke.';
$lang['email_no_hostname'] = 'Niste naveli SMTP hostname (ime glavnog računala).';
$lang['email_smtp_error'] = 'Došlo je do sljedeće SMTP pogreške: %s';
$lang['email_no_smtp_unpw'] = 'Pogreška: Morate dodijeliti SMTP korisničko ime i lozinku.';
$lang['email_failed_smtp_login'] = 'Slanje naredbe AUTH LOGIN nije uspjelo. Pogreška: %s';
$lang['email_smtp_auth_un'] = 'Provjera autentičnosti korisničkog imena nije uspjela. Pogreška: %s';
$lang['email_smtp_auth_pw'] = 'Provjera autentičnosti lozinke nije uspjela. Pogreška: %s';
$lang['email_smtp_data_failure'] = 'Nije moguće poslati podatke: %s';
$lang['email_exit_status'] = 'Izlazni statusni kod: %s';
