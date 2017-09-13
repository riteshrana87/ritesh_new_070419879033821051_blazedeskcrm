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

$lang['db_invalid_connection_str'] = 'Αδυναμία προσδιορισμού ρυθμίσεων βάσης δεδομένων με βάση τη στοιχειοσειρά που υποβάλλατε.';
$lang['db_unable_to_connect'] = 'Αδυναμία σύνδεσης στον server βάσης δεδομένων με τη χρήση των παρεχόμενων ρυθμίσεων.';
$lang['db_unable_to_select'] = 'Αδυναμία επιλογής της καθορισμένης βάσης δεδομένων %s';
$lang['db_unable_to_create'] = 'Αδυναμία δημιουργίας της καθορισμένης βάσης δεδομένων %s';
$lang['db_invalid_query'] = 'Το αίτημα που υποβάλλατε δεν είναι έγκυρο.';
$lang['db_must_set_table'] = 'Θα πρέπει να ρυθμίσετε τoν πίνακα βάσης δεδομένων που θα χρησιμοποιηθεί με το αίτημά σας.';
$lang['db_must_use_set'] = 'Θα πρέπει να χρησιμοποιήσετε τη μέθοδο "set" για να ενημερώσετε μια καταχώρηση.';
$lang['db_must_use_index'] = 'Θα πρέπει να προσδιορίσετε ένα πίνακα που να συμφωνεί με τις batch ενημερώσεις.';
$lang['db_batch_missing_index'] = 'Μια ή περισσότερες υποβαλλόμενες σειρές για batch ενημέρωση λείπει από τον προσδιορισμένο πίνακα.';
$lang['db_must_use_where'] = 'Οι ενημερώσεις δεν επιτρέπονται εκτός αν περιέχουν όρο "where" (που).';
$lang['db_del_must_use_where'] = 'Οι διαγραφές δεν επιτρέπονται εκτός αν περιέχουν όρο "where" ή "like"';
$lang['db_field_param_missing'] = 'Για να προσκομίσετε πεδία απαιτείται το όνομα του πίνακα ως παράμετρος.';
$lang['db_unsupported_function'] = 'Αυτή η λειτουργία δεν είναι διαθέσιμη για τη βάση δεδομένων που χρησιμοποιείτε.';
$lang['db_transaction_failure'] = 'Αποτυχία συναλλαγής: Εκτελέστηκε επαναφορά.';
$lang['db_unable_to_drop'] = 'Αδυναμία drop της προσδιορισμένης βάσης δεδομένων.';
$lang['db_unsupported_feature'] = 'Μη υποστηριζόμενη λειτουργία της πλατφόρμας βάσης δεδομένων που χρησιμοποιείτε.';
$lang['db_unsupported_compression'] = 'Η μορφή συμπίεσης αρχείου που επιλέξατε δεν υποστηρίζεται από τον server.';
$lang['db_filepath_error'] = 'Αδυναμία εγγραφής δεδομένων στη διαδρομή αρχείου που υποβάλλατε.';
$lang['db_invalid_cache_path'] = 'Η διαδρομή της cache μνήμης που υποβάλλατε είτε δεν είναι έγκυρη ή εγγράψιμη.';
$lang['db_table_name_required'] = 'Απαιτείται όνομα πίνακα για αυτή την λειτουργία.';
$lang['db_column_name_required'] = 'Απαιτείται όνομα στήλης για αυτή την λειτουργία.';
$lang['db_column_definition_required'] = 'Απαιτείται ορισμός στήλης για αυτή την λειτουργία.';
$lang['db_unable_to_set_charset'] = 'Αδυναμία ορισμού σύνδεσης set χαρακτήρα πελάτη: %s';
$lang['db_error_heading'] = 'Παρουσιάστηκε Σφάλμα Βάσης Δεδομένων';
