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

$lang['email_must_be_array'] = 'E-postvalideringen må sorteres i matriseform.';
$lang['email_invalid_address'] = 'Ugyldig e-postadresse: %s';
$lang['email_attachment_missing'] = 'Finner ikke følgende vedlegg: %S';
$lang['email_attachment_unreadable'] = 'Kan ikke åpne følgende vedlegg: %s';
$lang['email_no_from'] = 'Kan ikke sende mail uten å fylle ut "Fra" -feltet.';
$lang['email_no_recipients'] = 'Du må fylle inn mottaker: Til, CC, eller Bcc. ';
$lang['email_send_failure_phpmail'] = 'Kan ikke send mail med PHP mail (). Serveren din er muligens ikke konfigurert for denne metoden.';
$lang['email_send_failure_sendmail'] = 'Kan ikke send mail med PHP Sendmail. Serveren din er muligens ikke konfigurert for denne metoden.';
$lang['email_send_failure_smtp'] = 'Kan ikke send mail med PHP SMTP. Serveren din er muligens ikke konfigurert for denne metoden.';
$lang['email_sent'] = 'Meldingen har blitt sendt ved bruk av følgende protokoll: %s';
$lang['email_no_socket'] = 'Kan ikke åpne en socket til Sendmail. Venligst kontroller innstillingene.';
$lang['email_no_hostname'] = 'Du spesifiserte ikke et SMTP vertsnavn.';
$lang['email_smtp_error'] = 'Følgende SMTP-feil har inntruffet : %s';
$lang['email_no_smtp_unpw'] = 'Feil: Du må velge et SMTP brukernavn og passord.';
$lang['email_failed_smtp_login'] = 'Sending av AUTH LOGIN-kommando feilet. Feil: %s';
$lang['email_smtp_auth_un'] = 'Autentisering av brukernavn feilet. Feil: %s';
$lang['email_smtp_auth_pw'] = 'Autentisering av passord feilet. Feil: %s';
$lang['email_smtp_data_failure'] = 'Kan ikke sende data: %s';
$lang['email_exit_status'] = 'Statuskode for avsluttning: %s';
