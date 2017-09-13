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

$lang['email_must_be_array'] = 'emailを有効にするメソッドに１つの配列が渡される必要があります。';
$lang['email_invalid_address'] = 'emailのアドレスが有効ではありません。';
$lang['email_attachment_missing'] = '次のemail への添付を見つけられません。';
$lang['email_attachment_unreadable'] = 'この添付を開けることができません。';
$lang['email_no_from'] = '"From" のヘッダーがない email は送れません。';
$lang['email_no_recipients'] = '受信者を含める必要があります： To,Cc,あるいはBcc';
$lang['email_send_failure_phpmail'] = 'PHP mail()を用いてeメールを送れません；あなたのサーバーはこの方法を使って eメールを送るように設定されていません。';
$lang['email_send_failure_sendmail'] = 'PHP Sendmail を用いて eメール を送れません。あなたのサーバーはこの方法を使って eメールを送るように設定されていません。';
$lang['email_send_failure_smtp'] = 'PHP SMTPを用いて eメールを送れません。あなたのサーバーはこの方法を使って eメールを送るように設定されていません。';
$lang['email_sent'] = 'あなたのメッセージは次のプロトコルを使ってうまく送れませんでした。';
$lang['email_no_socket'] = 'Sendmail のSocket をオープンできません。設定をチェックしてください。';
$lang['email_no_hostname'] = 'SMTP のホストの名前が指定されていませんでした。';
$lang['email_smtp_error'] = '次の SMTP エラーが起こりました。';
$lang['email_no_smtp_unpw'] = 'エラー：SMTP のユーザ名とパスワードを指定する必要があります。';
$lang['email_failed_smtp_login'] = 'AUTH LOGIN コマンドを送るのに失敗しました。エラー';
$lang['email_smtp_auth_un'] = 'ユーザ名の認証に失敗しました。エラー';
$lang['email_smtp_auth_pw'] = 'パスワードの認証に失敗しました。エラー';
$lang['email_smtp_data_failure'] = 'データを送ることができません。';
$lang['email_exit_status'] = '終了状態コード。';
