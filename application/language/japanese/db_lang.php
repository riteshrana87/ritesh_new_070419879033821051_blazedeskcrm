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

$lang['db_invalid_connection_str'] = 'あなたが入力した接続文字列によるとデータベースの設定を決定できません。';
$lang['db_unable_to_connect'] = '与えられた設定を用いてあなたのデータベース・サーバーへの接続ができません。';
$lang['db_unable_to_select'] = '指定したデータベースを選択することができません。';
$lang['db_unable_to_create'] = '指定したデータベースを生成することができません。';
$lang['db_invalid_query'] = 'あなたが入力したクエリーは有効ではありません。';
$lang['db_must_set_table'] = 'クエリーと一緒に用いるデータベース・テーブルを設定する必要があります。';
$lang['db_must_use_set'] = '入力を更新するのに"set"の方法を使う必要があります。';
$lang['db_must_use_index'] = '一括更新のために一致させるインデックスを指定する必要があります。';
$lang['db_batch_missing_index'] = '一括更新のための一つあるいは複数の行でインデックスの指定が抜けています。';
$lang['db_must_use_where'] = '更新には"where" クローズがなければなりません。';
$lang['db_del_must_use_where'] = '削除には"where" あるいは "like"  クローズがなければなりません。';
$lang['db_field_param_missing'] = 'フィールドを得るにはパラメータとしてテーブルの名前が必要です。';
$lang['db_unsupported_function'] = 'この機能はあなたが使っているデータベースでは使えません。';
$lang['db_transaction_failure'] = '処理に失敗：ロールバックが行われました。';
$lang['db_unable_to_drop'] = '指定したデータベースを降ろすことができません。';
$lang['db_unsupported_feature'] = '使用中のデータベース・プラットフォームではサポートされていない機能です。';
$lang['db_unsupported_compression'] = 'あなたが選んだファイル圧縮形式はあなたのサーバーではサポートされていません。';
$lang['db_filepath_error'] = '入力したファイルのパスにデータを書くことができません。';
$lang['db_invalid_cache_path'] = '入力したキャッシュのパスは有効でないか書き込みができないかです。';
$lang['db_table_name_required'] = 'その操作にはテーブルの名前が必要です。';
$lang['db_column_name_required'] = 'その操作には列の名前が必要です。';
$lang['db_column_definition_required'] = 'その操作には列の定義が必要です。';
$lang['db_unable_to_set_charset'] = 'クライアント接続の文字セットを設定できません。';
$lang['db_error_heading'] = 'データベースのエラーが起こりました。';
