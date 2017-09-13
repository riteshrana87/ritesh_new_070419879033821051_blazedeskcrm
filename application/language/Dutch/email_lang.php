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

$lang['email_must_be_array'] = 'The email validation method must be passed an array.';
$lang['email_invalid_address'] = 'Email adres is ongeldig: %s';
$lang['email_attachment_missing'] = 'Het is niet mogelijk om de volgende bijlage te plaatsen: %s';
$lang['email_attachment_unreadable'] = 'Het is niet mogelijk om de bijlage te openen: %s';
$lang['email_no_from'] = 'Email kan niet verzonden worden zonder afzender.';
$lang['email_no_recipients'] = 'Minstens één ontvanger moet toegevoegd zijn: To, Cc, or Bcc';
$lang['email_send_failure_phpmail'] = 'Het is niet mogelijk om een email te verzenden met het gebruik van PHP(). Uw server is mogelijk niet geconfigureerd om deze methode te gebruiken.';
$lang['email_send_failure_sendmail'] = 'Het is niet mogelijk om een email te verzenden met het gebruik van PHP. Uw server is mogelijk niet geconfigureerd om deze methode te gebruiken.';
$lang['email_send_failure_smtp'] = 'Het is onmogelijk om een email teverzenden met het gebruik van PHP SMTP. Uw server is mogelijk niet geconfigureerd om deze methode te gebruiken.';
$lang['email_sent'] = 'Uw bericht is met succes verzonden met gebruik van de volgende protocol: %s';
$lang['email_no_socket'] = 'Unable to open a socket to Sendmail. Please check settings.';
$lang['email_no_hostname'] = 'U heeft geen SMTP hostname geïdentificeerd.';
$lang['email_smtp_error'] = 'De volgende SMTP error kwamen wij tegen: %s';
$lang['email_no_smtp_unpw'] = 'Error: U bent verplicht een SMTP SMTP gebruikersnaam and wachtwoord te gebruiken.';
$lang['email_failed_smtp_login'] = 'Failed to send AUTH LOGIN command. Error: %s';
$lang['email_smtp_auth_un'] = 'Het is niet gelukt om de gebruikersnaam te herkennen. Error: %s';
$lang['email_smtp_auth_pw'] = 'Het is niet gelukt om het wachtwoord te herkennen. Error: %s';
$lang['email_smtp_data_failure'] = 'Het is niet gelukt om de data ter verzenden: %s';
$lang['email_exit_status'] = 'Exit status code: %s';
