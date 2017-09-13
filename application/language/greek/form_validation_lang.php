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

$lang['form_validation_required']		= 'Το {field} πεδίο είναι απαραίτητο.';
$lang['form_validation_isset']			= 'Το {field} πεδίο πρέπει να έχει τιμή.';
$lang['form_validation_valid_email']		= 'Το {field} πεδίο πρέπει να εμπεριέχει έγκυρη email διεύθυνση.';
$lang['form_validation_valid_emails']		= 'Το {field} πεδίο πρέπει να εμπεριέχει μόνο έγκυρες email διευθύνσεις.';
$lang['form_validation_valid_url']		= 'Το {field} πεδίο πρέπει να εμπεριέχει έγκυρο URL.';
$lang['form_validation_valid_ip']		= 'Το {field} πεδίο πρέπει να εμπεριέχει έγκυρη IP.';
$lang['form_validation_min_length']		= 'Το {field} πεδίο πρέπει να έχει τουλάχιστον {param} χαρακτήρες.';
$lang['form_validation_max_length']		= 'Το {field} πεδίο πρέπει να έχει τουλάχιστον {param} χαρακτήρες.';
$lang['form_validation_exact_length']		= 'Το {field} πεδίο δε μπορεί να έχει περισσότερους από {param} χαρακτήρες.';
$lang['form_validation_alpha']			= 'Το {field} πεδίο πρέπει να εμπεριέχει μόνο αλφαβητικούς χαρακτήρες.';
$lang['form_validation_alpha_numeric']		= 'The {field} field may only contain alpha-numeric characters.';
$lang['form_validation_alpha_numeric_spaces']	= 'Το {field} πεδίο πρέπει να εμπεριέχει μόνο αλφαριθμητικούς χαρακτήρες και διαστήματα.';
$lang['form_validation_alpha_dash']		= 'Το {field} πεδίο πρέπει να εμπεριέχει μόνο αλφαριθμητικούς χαρακτήρες, υπογραμμίσεις, και παύλες.';
$lang['form_validation_numeric']		= 'Το {field} πεδίο μπορεί να εμπεριέχει μόνο αριθμούς.';
$lang['form_validation_is_numeric']		= 'Το {field} πεδίο μπορεί να εμπεριέχει μόνο αριθμητικούς χαρακτήρες.';
$lang['form_validation_integer']		= 'Το {field} πεδίο μπορεί να εμπεριέχει μόνο ακέραιο.';
$lang['form_validation_regex_match']		= 'Το {field} πεδίο δεν είναι στη σωστή μορφή.';
$lang['form_validation_matches']		= 'Το {field} πεδίο δε ταιριάζει με το {param} πεδίο.';
$lang['form_validation_differs']		= 'Το {field} πεδίο πρέπει να διαφέρει από το {param} πεδίο.';
$lang['form_validation_is_unique'] 		= 'Το {field} πεδίο πρέπει να εμπεριέχει μοναδική τιμή.';
$lang['form_validation_is_natural']		= 'Το {field} πεδίο πρέπει να εμπεριέχει μόνο ψηφία.';
$lang['form_validation_is_natural_no_zero']	= 'Το {field} πεδίο πρέπει να εμπεριέχει μόνο ψηφία και πρέπει να είναι μεγαλύτερο από μηδέν.';
$lang['form_validation_decimal']		= 'Το {field} πεδίο πρέπει να εμπεριέχει δεκαδικό αριθμό.';
$lang['form_validation_less_than']		= 'Το {field} πεδίο πρέπει να εμπεριέχει αριθμό μικρότερο από {param} .';
$lang['form_validation_less_than_equal_to']	= 'Το {field} πεδίο πρέπει να εμπεριέχει αριθμό μικρότερο από ή ίσο με {param} .';
$lang['form_validation_greater_than']		= 'Το {field} πεδίο θα πρέπει να εμπεριέχει αριθμό μεγαλύτερο από {param}   ';
$lang['form_validation_greater_than_equal_to']	= 'Το {field} πεδίο θα πρέπει να εμπεριέχει αριθμό μεγαλύτερο από ή ίσο με {param}';
$lang['form_validation_error_message_not_set']	= 'Αδυναμία πρόσβασης μηνύματος σφάλματος που αντιστοιχεί στο όνομα πεδίου {field}. ';
$lang['form_validation_in_list']		= 'Το {field} πεδίο θα πρέπει να είναι κάποιο από: {param}.';
