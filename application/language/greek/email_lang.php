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

$lang['email_must_be_array'] = 'Αυτή η μέθοδος ελέγχου εγκυρότητας θα πρέπει να περάσει array.';
$lang['email_invalid_address'] = 'Μη έγκυρη email διεύθυνση: %s';
$lang['email_attachment_missing'] = 'Αδυναμία εντοπισμού της παρακάτω email επισύναψης: %s';
$lang['email_attachment_unreadable'] = 'Αδυναμία ανοίγματος της επισύναψης: %s';
$lang['email_no_from'] = 'Αδυναμία αποστολής mail χωρίς πεδίο "From".';
$lang['email_no_recipients'] = 'Θα πρέπει να συμπεριλάβετε παραλήπτες: To, Cc, ή Bcc';
$lang['email_send_failure_phpmail'] = 'Αδυναμία αποστολής email χρησιμοποιώντας PHP mail (). O server σας μπορεί να μην έχει ρυθμιστεί να αποστέλλει mail χρησιμοποιώντας αυτή τη μέθοδο.';
$lang['email_send_failure_sendmail'] = 'Αδυναμία αποστολής email χρησιμοποιώντας PHP Sendmail. Ο server σας μπορεί να μην έχει ρυθμιστεί να αποστέλλει mail χρησιμοποιώντας αυτή τη μέθοδο.';
$lang['email_send_failure_smtp'] = 'Αδυναμία αποστολής email χρησιμοποιώντας PHP SMTP. Ο server σας μπορεί να μην έχει ρυθμιστεί να αποστέλλει mail χρησιμοποιώντας αυτή τη μέθοδο.';
$lang['email_sent'] = 'Το μήνυμά σας έχει σταλθεί επιτυχώς χρησιμοποιώντας το παρακάτω πρωτόκολο:  %s';
$lang['email_no_socket'] = 'Αδυναμία ανοίγματος socket στο Sendmail. Θα πρέπει να ελέγξετε τις ρυθμίσεις.';
$lang['email_no_hostname'] = 'Δεν ορίσατε SMTP όνομα εξυπηρετητή.';
$lang['email_smtp_error'] = 'Παρουσιάστηκε το εξής SMTP σφάλμα: %s';
$lang['email_no_smtp_unpw'] = 'Σφάλμα: Θα πρέπει να ορίσετε SMTP όνομα χρήστη και κωδικό πρόσβασης.';
$lang['email_failed_smtp_login'] = 'Αποτυχία αποστολής εντολής AUTH LOGIN. Σφάλμα: %s';
$lang['email_smtp_auth_un'] = 'Αποτυχία επικύρωσης ονόματος χρήστη. Σφάλμα: %s';
$lang['email_smtp_auth_pw'] = 'Αποτυχία επικύρωσης κωδικού πρόσβασης. Σφάλμα: %s';
$lang['email_smtp_data_failure'] = 'Αδυναμία αποστολής δεδομένων: %s';
$lang['email_exit_status'] = 'Exit status code: %s';
