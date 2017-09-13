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

$lang['email_must_be_array'] = 'Metoden til e-mail validering skal passeres en vifte.';
$lang['email_invalid_address'] = 'gyldig E-mail adresse: %s';
$lang['email_attachment_missing'] = 'Kunne ikke lokalisere den følgende vedhæftede e-mail fil.';
$lang['email_attachment_unreadable'] = 'Kunne ikke åbne den vedhæftede fil.';
$lang['email_no_from'] = 'Kan ikke sende mail uden ”Fra” overskrift.';
$lang['email_no_recipients'] = 'Du skal inkludere modtagere: To, Cc, eller Bcc';
$lang['email_send_failure_phpmail'] = 'Kunne ikke sende e-mail ved brug af PHP mail(). Din server er måske ikke konfigureret til at sende mail ved brug af denne metode.';
$lang['email_send_failure_sendmail'] = 'Kunne ikke sende e-mail ved brug af PHP Sendmail. Din server er måske ikke konfigureret til at sende mail ved brug af denne metode.';
$lang['email_send_failure_smtp'] = 'Din server er måske ikke konfigureret til at sende mail ved brug af denne metode.';
$lang['email_sent'] = 'Din besked blev sendt ved brug af følgende protokol: %s';
$lang['email_no_socket'] = 'Kunne ikke åbne en filsti til Sendmail. Tjek venligst dine indstillinger.';
$lang['email_no_hostname'] = 'Du har ikke specificeret et SMTP værtsnavn.';
$lang['email_smtp_error'] = 'Den følgende SMTP-fejl opstod: %s';
$lang['email_no_smtp_unpw'] = 'Fejl: Du skal tilskrive et SMTP-brugernavn og kodeord.';
$lang['email_failed_smtp_login'] = 'Kunne ikke sende AUTH LOGIN kommando. Fejl: %s';
$lang['email_smtp_auth_un'] = 'Kunne ikke godkende brugernavn. Fejl: %s';
$lang['email_smtp_auth_pw'] = 'Kunne ikke godkende kodeord. Fejl: %s';
$lang['email_smtp_data_failure'] = 'Kunne ikke sende data: %s';
$lang['email_exit_status'] = 'Exit status code: %s';
