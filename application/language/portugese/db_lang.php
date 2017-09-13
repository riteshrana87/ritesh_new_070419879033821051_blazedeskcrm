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

$lang['db_invalid_connection_str'] = 'Não foi possível determinar as configurações da base de dados com base na sequência da ligação submetida.';
$lang['db_unable_to_connect'] = 'Não foi possível ligar ao servidor da base de dados usando as configurações fornecidas.';
$lang['db_unable_to_select'] = 'Não foi possível selecionar a base de dados especificada: %s';
$lang['db_unable_to_create'] = 'Não foi possível criar a base de dados especificada: %s';
$lang['db_invalid_query'] = 'O pedido que submeteu não é válido.';
$lang['db_must_set_table'] = 'Deve definir a tabela da base de dados para ser usada com o seu pedido.';
$lang['db_must_use_set'] = 'Deve usar o método "set" para atualizar uma entrada.';
$lang['db_must_use_index'] = 'Deve especificar um índice para corresponder para atualizações em lote.';
$lang['db_batch_missing_index'] = 'Uma ou mais linhas apresentadas para atualização em lote tem o índice especificado em falta.';
$lang['db_must_use_where'] = 'Não são permitidas atualizações a menos que contenham uma cláusula "where".';
$lang['db_del_must_use_where'] = 'Exclusões não são permitidas a menos que contenham uma cláusula "where" ou "like".';
$lang['db_field_param_missing'] = 'Para obter campos, requer o nome da tabela como um parâmetro.';
$lang['db_unsupported_function'] = 'Este recurso não está disponível para a base de dados que está a usar.';
$lang['db_transaction_failure'] = 'Falha na transação: Rollback realizado.';
$lang['db_unable_to_drop'] = 'Não foi possível deixar a base de dados especificada.';
$lang['db_unsupported_feature'] = 'Funcionalidade não suportada da plataforma de base de dados que está a usar.';
$lang['db_unsupported_compression'] = 'O formato de compressão de ficheiro que escolheu não é suportado pelo seu servidor.';
$lang['db_filepath_error'] = 'Não foi possível gravar dados no caminho do arquivo que submeteu.';
$lang['db_invalid_cache_path'] = 'O caminho da cache que submeteu não é válido ou gravável.';
$lang['db_table_name_required'] = 'É necessário um nome de tabela para essa operação.';
$lang['db_column_name_required'] = 'É necessário um nome de coluna para essa operação.';
$lang['db_column_definition_required'] = 'É necessário uma definição de coluna para essa operação.';
$lang['db_unable_to_set_charset'] = 'Não foi possível definir o conjunto de caracteres da ligação do cliente: %s';
$lang['db_error_heading'] = 'Ocorreu Um Erro na Base de Dados';
