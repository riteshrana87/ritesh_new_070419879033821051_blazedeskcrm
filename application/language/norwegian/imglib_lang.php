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

$lang['imglib_source_image_required'] = 'Du må spesifisere et bilde i innstillingene dine.';
$lang['imglib_gd_required'] = 'GD bildebibliotek kreves for denne funksjonen.';
$lang['imglib_gd_required_for_props'] = 'Din server må støtte GD bildebiblioteket for å bestemme bildets egenskaper.';
$lang['imglib_unsupported_imagecreate'] = 'Din server støtter ikke GD-funksjonen som kreves for å behandle denne bildetypen.';
$lang['imglib_gif_not_supported'] = 'GIF-bilder støttes ofte ikke pga. lisensrestriksjoner. Du må kanskje bruke JPG- eller PNG-formatet i stedet.';
$lang['imglib_jpg_not_supported'] = 'JPG-filer støttes ikke.';
$lang['imglib_png_not_supported'] = 'PNG-filer støttes ikke.';
$lang['imglib_jpg_or_png_required'] = 'Protokollen for å endre bildestørrelse som er valgt i dine instillingerr, virker kun med JPEG- og PNG-filer.';
$lang['imglib_copy_error'] = 'Det oppstod en feil i forsøket på å erstatte filen. Sørg for at du har skrivetilatelse til filsystemet.';
$lang['imglib_rotate_unsupported'] = 'Det ser ikke ut til at serveren din støtter rotering av bilder.';
$lang['imglib_libpath_invalid'] = 'Filbanen til ditt bildebibliotek er feil. Venligst velg en gyldigfilbane i i bildeinnstillingene dine.';
$lang['imglib_image_process_failed'] = 'Bildeprossesering feilet. Vennligst kontroller at serveren din støtter den valget protokollen, og at filbanen til bildebiblioteket er korrekt.';
$lang['imglib_rotation_angle_required'] = 'Du må angi en rotasjonsvinkel for å rotere bildet.';
$lang['imglib_invalid_path'] = 'Bildets filbane er ikke korrekt.';
$lang['imglib_copy_failed'] = 'Bildekopieringen feilet.';
$lang['imglib_missing_font'] = 'Finner ikke en font som kan brukes.';
$lang['imglib_save_failed'] = 'Bildet kunne ikke lagres. Venligst sørg for at bilde- og filsystemet tillater lagring.';
