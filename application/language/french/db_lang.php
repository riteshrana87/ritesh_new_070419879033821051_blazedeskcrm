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

$lang['db_invalid_connection_str'] = 'Impossible de déterminer les paramètres de la base de données avec la chaîne de connexion que vous avez soumis.';
$lang['db_unable_to_connect'] = 'Impossible de se connecter à votre base de données en utilisant les paramètres fournis.';
$lang['db_unable_to_select'] = 'Impossible de sélectionner la base de données sélectionnée : %s';
$lang['db_unable_to_create'] = 'Impossible de créér la base de données sélectionnée : %s';
$lang['db_invalid_query'] = 'La requête que vous avez soumise nest pas valide.';
$lang['db_must_set_table'] = 'Vous devez paramétrer la table de base de données pour lutiliser lors de vos requêtes.';
$lang['db_must_use_set'] = 'Vous devez utiliser la méthode "set" pour mettre à jour une entrée.';
$lang['db_must_use_index'] = 'Vous devez spécifier lindex à utiliser pour les mises à jours en lot.';
$lang['db_batch_missing_index'] = 'Une rangée ou plus, soumises à la mise à jour en lot, nont pas lindex spécifié.';
$lang['db_must_use_where'] = 'Les mises à jour ne sont pas autorisées à moins quelles ne contiennent une clause "where".';
$lang['db_del_must_use_where'] = 'Les suppressions ne sont pas autorisées à moins quelles ne contiennent une clause "where" ou "like".';
$lang['db_field_param_missing'] = 'La recherche de champs a besoin du nom de la table comme paramètre.';
$lang['db_unsupported_function'] = 'Ce paramètre nest pas disponible pour la base de données que vous utilisez.';
$lang['db_transaction_failure'] = 'Transaction échouée : retour effectué';
$lang['db_unable_to_drop'] = 'Impossible de supprimer la base de données spécifiée.';
$lang['db_unsupported_feature'] = 'Un paramètre de la plateforme de base de données que vous utilisez nest pas supporté.';
$lang['db_unsupported_compression'] = 'Le format de compression de fichier que vous avez choisi nest pas supporté par votre serveur.';
$lang['db_filepath_error'] = 'Impossible dinscrire des données sur le chemin daccès que vous avez soumis.';
$lang['db_invalid_cache_path'] = 'Le chemin du cache que vous avez soumis nest pas valide ou il est impossible décrire dessus.';
$lang['db_table_name_required'] = 'Un nom de table est nécessaire pour cette opération..';
$lang['db_column_name_required'] = 'Un nom de colonne est nécessaire pour cette opération.';
$lang['db_column_definition_required'] = 'Une définition de colonne est nécessaire pour cette opération.';
$lang['db_unable_to_set_charset'] = 'Impossible de définir le jeu de caractères de la connexion du client : %s';
$lang['db_error_heading'] = 'Une Erreur de Base de Données est Survenue';
