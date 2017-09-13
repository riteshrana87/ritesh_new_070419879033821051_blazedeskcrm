<?php

class Imap {

    /**
     * imap connection
     */
    protected $imap = false;

    /**
     * mailbox url string
     */
    protected $mailbox = "";

    /**
     * currentfolder
     */
    protected $folder = "Inbox";

    /**
     * initialize imap helper
     *
     * @return void
     * @param $mailbox imap_open string
     * @param $username
     * @param $password
     * @param $encryption ssl or tls
     */
    public function __construct() {
        
    }

    public function connect($mailbox, $username, $password, $encryption = false) {
        $enc = '';
        if ($encryption != null && isset($encryption) && $encryption == 'ssl')
            $enc = '/imap/ssl/novalidate-cert';
        else if ($encryption != null && isset($encryption) && $encryption == 'tls')
            $enc = '/imap/tls/novalidate-cert';
        $this->mailbox = "{" . $mailbox . "SSL}";
        $this->imap = @imap_open($this->mailbox, $username, $password);
        return $this->imap;
    }

    /**
     * close connection
     */
    function __destruct() {
        if ($this->imap !== false)
            imap_close($this->imap);
    }

    /**
     * returns true after successfull connection
     *
     * @return bool true on success
     */
    public function isConnected() {
        return $this->imap !== false;
    }

    /**
     * returns last imap error
     *
     * @return string error message
     */
    public function getError() {
        return imap_last_error();
    }

    /**
     * select given folder
     *
     * @return bool successfull opened folder
     * @param $folder name
     */
    public function selectFolder($folder) {

        $result = imap_reopen($this->imap, $this->mailbox . $folder);
    
        if ($result === true)
            $this->folder = $folder;
        return $result;
    }

    /**
     * returns all available folders
     *
     * @return array with foldernames
     */
    public function getFolders() {
        $folders = imap_list($this->imap, $this->mailbox, "*");
        return str_replace($this->mailbox, "", $folders);
    }

    /**
     * returns the number of messages in the current folder
     *
     * @return int message count
     */
    public function countMessages() {
        return imap_num_msg($this->imap);
    }

    /**
     * returns the number of unread messages in the current folder
     *
     * @return int message count
     */
    public function countUnreadMessages() {
        $result = imap_search($this->imap, 'UNSEEN');
        if ($result === false)
            return 0;
        return count($result);
    }

    /**
     * returns unseen emails in the current folder
     *
     * @return array messages
     * @param $withbody without body
     */
    public function getUnreadMessages($withbody = true) {
        $emails = [];
        $result = imap_search($this->imap, 'UNSEEN');
        if ($result) {
            foreach ($result as $k => $i) {
                $emails[] = $this->formatMessage($i, $withbody);
            }
        }
        return $emails;
    }

    /**
     * returns all emails in the current folder
     *
     * @return array messages
     * @param $withbody without body
     */
    public function getMessages($withbody = true) {
        $count = $this->countMessages();
        $emails = array();
        for ($i = 1; $i <= $count; $i++) {
            $emails[] = $this->formatMessage($i, $withbody);
        }

// sort emails descending by date
// usort($emails, function($a, $b) {
// try {
// $datea = new \DateTime($a['date']);
// $dateb = new \DateTime($b['date']);
// } catch(\Exception $e) {
// return 0;
// }
// if ($datea == $dateb)
// return 0;
// return $datea < $dateb ? 1 : -1;
// });

        return $emails;
    }

    /**
     * returns email by given id
     *
     * @return array messages
     * @param $id
     * @param $withbody without body
     */
    public function getMessage($id, $withbody = true) {
        return $this->formatMessage($id, $withbody);
    }

    /**
     * @param $id
     * @param bool $withbody
     * @return array
     */
    protected function formatMessage($id, $withbody = true) {
        $header = imap_headerinfo($this->imap, $id);


// fetch unique uid
        $uid = imap_uid($this->imap, $id);

// get email data
        $subject = '';
        if (isset($header->subject) && strlen($header->subject) > 0) {
            foreach (imap_mime_header_decode($header->subject) as $obj) {
                $subject .= $obj->text;
            }
        }
        //        pr(imap_fetchheader($this->imap, $id));
        //      pr((array) $header);
        //        die;
        //$subject    = $header->fetchsubject;
        //    $subjectdecoded = iconv_mime_decode($subject, 1, "ISO-8859-1");

        $subject2 = $this->decodeMimeStr($header->subject); //$this->convertToUtf8($subject);

        $email = array(
            'to' => isset($header->to) ? $this->arrayToAddress($header->to) : '',
            'from' => $this->toAddress($header->from[0]),
            'date' => $header->date,
            'subject' => $subject2,
            'uid' => $uid,
            'unread' => strlen(trim($header->Unseen)) > 0,
            'answered' => strlen(trim($header->Answered)) > 0,
            'deleted' => strlen(trim($header->Deleted)) > 0,
            'header' => $header,
            'to_email' => $this->arrayToAddressemails($header->to),
            'from_email' => $this->toAddressEmail($header->from[0]),
            'reply_to_address' => isset($header->reply_to) ? $this->arrayToAddress($header->reply_to) : '',
            'reply_to_email' => isset($header->reply_to) ? $this->arrayToAddressemails($header->reply_to) : '',
            'message_no' => $header->Msgno
        );
        if (isset($header->cc))
            $email['cc'] = $this->arrayToAddressemails($header->cc);
        if (isset($header->bcc))
            $email['bcc'] = $this->arrayToAddressemails($header->bcc);

// get email body
        if ($withbody === true) {
            $body = $this->getBody($uid);

            $email['body'] = $body['body'];


            //imap_utf8(imap_fetchbody($this->imap, $id, 1));

            $email['html'] = $body['html'];
        }

// get attachments
        $structure = imap_fetchstructure($this->imap, $id);
//        $attachments = $this->attachments2name($this->getAttachments($this->imap, $id, $mailStruct, ""));
        $attachments = array();
        /* if any attachments found... */
        if (isset($structure->parts) && count($structure->parts)) {

            for ($i = 0; $i < count($structure->parts); $i++) {
//pr($structure->parts[$i]);
                $attachments[$i] = array(
                    'is_attachment' => false,
                    'filename' => '',
                    'name' => '',
                    'attachment' => '',
                    'size' => '',
                    'cid' => 0
                );

                if ($structure->parts[$i]->ifdparameters) {
                    foreach ($structure->parts[$i]->dparameters as $object) {
                        if (strtolower($object->attribute) == 'filename') {
                            $attachments[$i]['is_attachment'] = true;
                            $attachments[$i]['filename'] = $object->value;
                        }
                    }
                }

                if ($structure->parts[$i]->ifparameters) {
                    foreach ($structure->parts[$i]->parameters as $object) {
                        if (strtolower($object->attribute) == 'name') {
                            $attachments[$i]['is_attachment'] = true;
                            $attachments[$i]['name'] = $object->value;
                        }
                    }
                }

                if ($attachments[$i]['is_attachment']) {
                    $attachments[$i]['attachment'] = imap_fetchbody($this->imap, $id, $i + 1);

                    /* 3 = BASE64 encoding */
                    if ($structure->parts[$i]->encoding == 3) {
                        $attachments[$i]['attachment'] = base64_decode($attachments[$i]['attachment']);
                    }
                    /* 4 = QUOTED-PRINTABLE encoding */ elseif ($structure->parts[$i]->encoding == 4) {
                        $attachments[$i]['attachment'] = quoted_printable_decode($attachments[$i]['attachment']);
                    }
                    $attachments[$i]['size'] = $structure->parts[$i]->bytes;
                    $attachments[$i]['cid'] = ($structure->parts[$i]->ifid) ? str_replace(array('<', '>'), '', $structure->parts[$i]->id) : 0;
                }
                $email['attachments'][] = $attachments[$i];
            }
        }
        $CI = &get_instance();
        $CI->load->library("session");
        $folderId = $CI->session->userdata('LOGGED_IN')['ID'];
        $dirpth = FCPATH . "uploads/Mail/$folderId/";
        if (!is_dir($dirpth)) {
            mkdir($dirpth, 0777);
        }
// pr($attachments);
        /* iterate through each attachment and save it */
        foreach ($attachments as $attachment) {
            if ($attachment['is_attachment'] == 1) {
                $filename = $attachment['name'];
//$filename = $attachment['filename'];
                if (empty($filename)):
                    $filename = time() . ".dat";
                endif;

//                $folder = "attachment";
//                if (!is_dir($folder)) {
//                    mkdir($folder);
//                }
                $uid = imap_uid($this->imap, $id);

                $edir = FCPATH . "uploads/Mail/$folderId/" . $uid;
                if (!is_dir($edir)) {
                    mkdir($edir, 0777);
                }
                $fp = @fopen(FCPATH . "uploads/Mail/$folderId/" . $uid . "/" . $uid . "-" . $filename, "w+");
                @fwrite($fp, $attachment['attachment']);
                @fclose($fp);
            }
        }
        if (count($attachments) > 0) {
            foreach ($attachments as $val) {
                foreach ($val as $k => $t) {
                    if ($k == 'name' && $t != '') {
                        $decodedName = imap_mime_header_decode($t);
                        $t = $this->convertToUtf8($decodedName[0]->text);
                    } else {
                        $t = '';
                    }
                    $arr[$k] = $t;
                }

                $email['attachments'][] = $arr;
            }
        }
        return $email;
    }

    /**
     * delete given message
     *
     * @return bool success or not
     * @param $id of the message
     */
    public function deleteMessage($id) {
        return $this->deleteMessages(array($id));
    }

    /**
     * delete messages
     *
     * @return bool success or not
     * @param $ids array of ids
     */
    public function deleteMessages($ids) {
        if (imap_mail_move($this->imap, implode(",", $ids), $this->getTrash(), CP_UID) == false)
            return false;
        return imap_expunge($this->imap);
    }

    /**
     * move given message in new folder
     *
     * @return bool success or not
     * @param $id of the message
     * @param $target new folder
     */
    public function moveMessage($id, $target) {
        return $this->moveMessages(array($id), $target);
    }

    /**
     * move given message in new folder
     *
     * @return bool success or not
     * @param $ids array of message ids
     * @param $target new folder
     */
    public function moveMessages($ids, $target) {
        if (imap_mail_move($this->imap, implode(",", $ids), $target, CP_UID) === false)
            return false;
        return imap_expunge($this->imap);
    }

    /**
     * mark message as read
     *
     * @return bool success or not
     * @param $id of the message
     * @param $seen true = message is read, false = message is unread
     */
    public function setUnseenMessage($id, $seen = true) {
        $header = $this->getMessageHeader($id);
        if ($header == false)
            return false;

        $flags = "";
        $flags .= (strlen(trim($header->Answered)) > 0 ? "\\Answered " : '');
        $flags .= (strlen(trim($header->Flagged)) > 0 ? "\\Flagged " : '');
        $flags .= (strlen(trim($header->Deleted)) > 0 ? "\\Deleted " : '');
        $flags .= (strlen(trim($header->Draft)) > 0 ? "\\Draft " : '');

        $flags .= (($seen == true) ? '\\Seen ' : ' ');
//echo "\n<br />".$id.": ".$flags;
        imap_clearflag_full($this->imap, $id, '\\Seen', ST_UID);
        return imap_setflag_full($this->imap, $id, trim($flags), ST_UID);
    }

    public function setasFlagged($id, $seen = false) {
        $header = $this->getMessageHeader($id);
        if ($header == false)
            return false;
//
        $flags = "";
        $flags .= (strlen(trim($header->Answered)) > 0 ? "\\Answered " : '');
        $flags .= (strlen(trim($header->Flagged)) > 0 ? "\\Flagged " : '');
        $flags .= (strlen(trim($header->Deleted)) > 0 ? "\\Deleted " : '');
        $flags .= (strlen(trim($header->Draft)) > 0 ? "\\Draft " : '');

        $flags .= (($seen == true) ? '\\Flagged ' : ' ');
//        echo "\n<br />" . $id . ": " . $flags;
        imap_clearflag_full($this->imap, $id, '\\Flagged', ST_UID);
        return imap_setflag_full($this->imap, $id, trim($flags), ST_UID);
    }

    /**
     * return content of messages attachment
     *
     * @return binary attachment
     * @param $id of the message
     * @param $index of the attachment (default: first attachment)
     */
    public function getAttachment($id, $index = 0) {
// find message
        $attachments = false;
        $messageIndex = imap_msgno($this->imap, $id);
        $header = imap_headerinfo($this->imap, $messageIndex);
        $mailStruct = imap_fetchstructure($this->imap, $messageIndex);
        $attachments = $this->getAttachments($this->imap, $messageIndex, $mailStruct, "");

        if ($attachments == false)
            return false;

// find attachment
        if ($index > count($attachments))
            return false;
        $attachment = $attachments[$index];

// get attachment body
        $partStruct = imap_bodystruct($this->imap, imap_msgno($this->imap, $id), $attachment['partNum']);
        $filename = $partStruct->dparameters[0]->value;
        $message = imap_fetchbody($this->imap, $id, $attachment['partNum'], FT_UID);

        switch ($attachment['enc']) {
            case 0:
            case 1:
                $message = imap_8bit($message);
                break;
            case 2:
                $message = imap_binary($message);
                break;
            case 3:
                $message = imap_base64($message);
                break;
            case 4:
                $message = quoted_printable_decode($message);
                break;
        }

        return array(
            "name" => $attachment['name'],
            "size" => $attachment['size'],
            "content" => $message);
    }

    /**
     * add new folder
     *
     * @return bool success or not
     * @param $name of the folder
     * @param $subscribe immediately subscribe to folder
     */
    public function addFolder($name, $subscribe = false) {
        $success = imap_createmailbox($this->imap, $this->mailbox . $name);

        if ($success && $subscribe) {
            $success = imap_subscribe($this->imap, $this->mailbox . $name);
        }

        return $success;
    }

    /**
     * remove folder
     *
     * @return bool success or not
     * @param $name of the folder
     */
    public function removeFolder($name) {
        return imap_deletemailbox($this->imap, $this->mailbox . $name);
    }

    /**
     * rename folder
     *
     * @return bool success or not
     * @param $name of the folder
     * @param $newname of the folder
     */
    public function renameFolder($name, $newname) {
        return imap_renamemailbox($this->imap, $this->mailbox . $name, $this->mailbox . $newname);
    }

    /**
     * clean folder content of selected folder
     *
     * @return bool success or not
     */
    public function purge() {
// delete trash and spam
        if ($this->folder == $this->getTrash() || strtolower($this->folder) == "spam") {
            if (imap_delete($this->imap, '1:*') === false) {
                return false;
            }
            return imap_expunge($this->imap);

// move others to trash
        } else {
            if (imap_mail_move($this->imap, '1:*', $this->getTrash()) == false)
                return false;
            return imap_expunge($this->imap);
        }
    }

    /**
     * returns all email addresses
     *
     * @return array with all email addresses or false on error
     */
    public function getAllEmailAddresses() {
        $saveCurrentFolder = $this->folder;
        $emails = array();
        foreach ($this->getFolders() as $folder) {
            $this->selectFolder($folder);
            foreach ($this->getMessages(false) as $message) {
                $emails[] = $message['from'];
                $emails = array_merge($emails, $message['to']);
                if (isset($message['cc']))
                    $emails = array_merge($emails, $message['cc']);
            }
        }
        $this->selectFolder($saveCurrentFolder);
        return array_unique($emails);
    }

    /**
     * save email in sent
     *
     * @return void
     * @param $header
     * @param $body
     */
    public function saveMessageInSent($header, $body) {
        return imap_append($this->imap, $this->mailbox . $this->getSent(), $header . "\r\n" . $body . "\r\n", "\\Seen");
    }

    /**
     * explicitly close imap connection
     */
    public function close() {
        if ($this->imap !== false)
            imap_close($this->imap);
    }

// protected helpers

    /**
     * get trash folder name or create new trash folder
     *
     * @return trash folder name
     */
    protected function getTrash() {
        foreach ($this->getFolders() as $folder) {
            if (strtolower($folder) === "trash" || strtolower($folder) === "papierkorb")
                return $folder;
        }

// no trash folder found? create one
        $this->addFolder('Trash');

        return 'Trash';
    }

    /**
     * get sent folder name or create new sent folder
     *
     * @return sent folder name
     */
    protected function getSent() {
        foreach ($this->getFolders() as $folder) {
            if (strtolower($folder) === "sent" || strtolower($folder) === "gesendet")
                return $folder;
        }

// no sent folder found? create one
        $this->addFolder('Sent');

        return 'Sent';
    }

    /**
     * fetch message by id
     *
     * @return header
     * @param $id of the message
     */
    protected function getMessageHeader($id) {
        $count = $this->countMessages();
        for ($i = 1; $i <= $count; $i++) {
            $uid = imap_uid($this->imap, $i);
            if ($uid == $id) {
                $header = imap_headerinfo($this->imap, $i);
                return $header;
            }
        }
        return false;
    }

    /**
     * convert attachment in array(name => ..., size => ...).
     *
     * @return array
     * @param $attachments with name and size
     */
    protected function attachments2name($attachments) {
        $names = array();
        foreach ($attachments as $attachment) {
            $names[] = array(
                'name' => $attachment['name'],
                'size' => $attachment['size']
            );
        }
        return $names;
    }

    /**
     * convert imap given address in string
     *
     * @return string in format "Name <email@bla.de>"
     * @param $headerinfos the infos given by imap
     */
    protected function toAddress($headerinfos) {
        $email = "";
        $name = "";
        if (isset($headerinfos->mailbox) && isset($headerinfos->host)) {
            $email = $headerinfos->mailbox . "@" . $headerinfos->host;
        }

        if (!empty($headerinfos->personal)) {
            $name = imap_mime_header_decode($headerinfos->personal);
            $name = $name[0]->text;
        } else {
            $name = $email;
        }

        $name = $this->convertToUtf8($name);

        return $name . " <" . $email . ">";
    }

    protected function toAddressEmail($headerinfos) {
        $email = "";
        $name = "";
        if (isset($headerinfos->mailbox) && isset($headerinfos->host)) {
            $email = $headerinfos->mailbox . "@" . $headerinfos->host;
        }

        if (!empty($headerinfos->personal)) {
            $name = imap_mime_header_decode($headerinfos->personal);
            $name = $name[0]->text;
        } else {
            $name = $email;
        }

        $name = $this->convertToUtf8($name);

        return $email;
    }

    /**
     * converts imap given array of addresses in strings
     *
     * @return array with strings (e.g. ["Name <email@bla.de>", "Name2 <email2@bla.de>"]
     * @param $addresses imap given addresses as array
     */
    protected function arrayToAddress($addresses) {
        $addressesAsString = array();
        foreach ($addresses as $address) {
            $addressesAsString[] = $this->toAddress($address);
        }
        return $addressesAsString;
    }

    protected function arrayToAddressemails($addresses) {
        $addressesAsString = array();
        foreach ($addresses as $address) {
            $addressesAsString[] = $this->toAddressEmail($address);
        }
        return $addressesAsString;
    }

    /**
     * returns body of the email. First search for html version of the email, then the plain part.
     *
     * @return string email body
     * @param $uid message id
     */
    protected function getBody($uid) {
        $body = $this->get_part($this->imap, $uid, "TEXT/HTML");
        $html = true;
// if HTML body is empty, try getting text body

        if ($body == "") {
            $body = $this->get_part($this->imap, $uid, "TEXT/PLAIN");
            $html = false;
        }
        $body = $this->convertToUtf8($body);

        return array('body' => $body, 'html' => $html);
    }

    /**
     * convert to utf8 if necessary.
     *
     * @return true or false
     * @param $string utf8 encoded string
     */
    function convertToUtf8($str) {
        if (mb_detect_encoding($str, "UTF-8, ISO-8859-1, GBK") != "UTF-8")
            $str = utf8_encode($str);
        $str = iconv('UTF-8', 'UTF-8//IGNORE', $str);
        return $str;
    }

    /**
     * returns a part with a given mimetype
     * taken from http://www.sitepoint.com/exploring-phps-imap-library-2/
     *
     * @return string email body
     * @param $imap imap stream
     * @param $uid message id
     * @param $mimetype
     */
    protected function get_part($imap, $uid, $mimetype, $structure = false, $partNumber = false) {
        if (!$structure) {
            $structure = imap_fetchstructure($imap, $uid, FT_UID);
        }
        //echo $uid . '<br/>';
        $charsetN = 'UTF-8';

        $params = array();
        if (!empty($structure->parameters)) {
            foreach ($structure->parameters as $param) {
                $params[strtolower($param->attribute)] = $param->value;
            }
        }
        if (!empty($structure->dparameters)) {
            foreach ($structure->dparameters as $param) {
                $paramName = strtolower(preg_match('~^(.*?)\*~', $param->attribute, $matches) ? $matches[1] : $param->attribute);
                if (isset($params[$paramName])) {
                    $params[$paramName] .= $param->value;
                } else {
                    $params[$paramName] = $param->value;
                }
            }
        }

        // $charsetN . '<br/>';
        if ($structure) {
            if ($mimetype == $this->get_mime_type($structure)) {
                if (!$partNumber) {
                    $partNumber = 1;
                }
                $text = imap_fetchbody($imap, $uid, $partNumber, FT_UID | FT_PEEK);
                // echo "Encodig" . $structure->encoding . '<br/>';
                /// echo "Uid" . $uid . '<br/>';
                switch ($structure->encoding) {
                    # 7BIT
                    case 0:
                        $text;
                    # 8BIT
                    case 1:
                        $text = quoted_printable_decode(imap_8bit($text));
                    # BINARY
                    case 2:
                        $text = imap_binary($text);
                    # BASE64
                    case 3:
                        $tex = imap_base64($text);
                    # QUOTED-PRINTABLE
                    case 4:
                        $text = quoted_printable_decode($text);
                    # OTHER
                    case 5:
                        $text;
                    # UNKNOWN
                    default:
                        $text;
                }
           //     pr($params);
                if(isset($params['charset']))
                {
                return $text = $this->convertStringEncoding($text, $params['charset'], 'UTF-8');
                }
                else
                {
                    return $text;
                }
//                switch ($structure->encoding) {
//                    case 3: return imap_base64($text);
//                    case 4: return imap_qprint($text);
//                    default: return $text;
//                }
            }

// multipart 
            if ($structure->type == 1) {
                foreach ($structure->parts as $index => $subStruct) {
                    $prefix = "";
                    if ($partNumber) {
                        $prefix = $partNumber . ".";
                    }
                    $data = $this->get_part($imap, $uid, $mimetype, $subStruct, $prefix . ($index + 1));
                    if ($data) {
                        return $data;
                    }
                }
            }
        }
        return false;
    }

    /**
     * extract mimetype
     * taken from http://www.sitepoint.com/exploring-phps-imap-library-2/
     *
     * @return string mimetype
     * @param $structure
     */
    protected function get_mime_type($structure) {
        $primaryMimetype = array("TEXT", "MULTIPART", "MESSAGE", "APPLICATION", "AUDIO", "IMAGE", "VIDEO", "OTHER");

        if ($structure->subtype) {
            return $primaryMimetype[(int) $structure->type] . "/" . $structure->subtype;
        }
        return "TEXT/PLAIN";
    }

    /**
     * get attachments of given email
     * taken from http://www.sitepoint.com/exploring-phps-imap-library-2/
     *
     * @return array of attachments
     * @param $imap stream
     * @param $mailNum email
     * @param $part
     * @param $partNum
     */
    protected function getAttachments($imap, $mailNum, $part, $partNum) {
        $attachments = array();

        if (isset($part->parts)) {
            foreach ($part->parts as $key => $subpart) {
                if ($partNum != "") {
                    $newPartNum = $partNum . "." . ($key + 1);
                } else {
                    $newPartNum = ($key + 1);
                }
                $result = $this->getAttachments($imap, $mailNum, $subpart, $newPartNum);
                if (count($result) != 0) {
                    array_push($attachments, $result);
                }
            }
        } else if (isset($part->disposition)) {
            if (strtolower($part->disposition) == "attachment") {
                $partStruct = imap_bodystruct($imap, $mailNum, $partNum);
                $attachmentDetails = array(
                    "name" => isset($part->dparameters[0]->value) ? $part->dparameters[0]->value : '',
                    "partNum" => $partNum,
                    "enc" => $partStruct->encoding,
                    "size" => $part->bytes
                );
                return $attachmentDetails;
            }
        }

        return $attachments;
    }

    /**
     * Return general mailbox statistics
     *
     * @return bool | StdClass object
     */
    public function getMailboxStatistics() {
        return $this->isConnected() ? imap_mailboxmsginfo($this->imap) : false;
    }

    function pagingMails() {
        $emails = imap_search($this->imap, 'UNSEEN');
       // print_r($emails);
      //  die;
//        $MC = imap_check($this->imap);
//        $Totallimit = $MC->Nmsgs;
        //$newlm = $nopage * 10;
// Fetch an overview for all messages in INBOX
//        $result = imap_fetch_overview($this->imap, "1:8:9", 0);
//        print_r($result);
//        die;
        $count = $last;
        $emails = array();
        for ($i = 1; $i <= $count; $i++) {
            $emails[] = $this->formatMessage($i, true);
        }
        return $emails;
    }

    /**
     * Return array of IMAP messages for pagination
     *
     * @param   int     $page       page number to get
     * @param   int     $per_page   number of results per page
     * @param   array   $sort       array('subject', 'asc') etc
     *
     * @return  mixed   array containing imap_fetch_overview, pages, and total rows if successful, false if an error occurred
     * @author  Raja K
     */
    public function listMessages($page = 1, $per_page = 25, $sort = false) {

//        echo $page;
//        echo $per_page;
//        die;

        $limit = ($per_page * $page);
        $email = array();
        $start = ($limit - $per_page) + 1;
        $start = ($start < 1) ? 1 : $start;
        $limit = (($limit - $start) != ($per_page - 1)) ? ($start + ($per_page - 1)) : $limit;
        $info = imap_check($this->imap);
       // pr($this->imap);
       // pr($info);
        if ($info->Nmsgs > 0) {
            $limit = ($info->Nmsgs < $limit) ? $info->Nmsgs : $limit;

//        if (true === is_array($sort)) {
//            $sorting = array(
//                'direction' => array('asc' => 0,
//                    'desc' => 1),
//                'by' => array('date' => SORTDATE,
//                    'arrival' => SORTARRIVAL,
//                    'from' => SORTFROM,
//                    'subject' => SORTSUBJECT,
//                    'size' => SORTSIZE));
//            $by = (true === is_int($by = $sorting['by'][$sort[0]])) ? $by : $sorting['by']['date'];
//            $direction = (true === is_int($direction = $sorting['direction'][$sort[1]])) ? $direction : $sorting['direction']['desc'];
//
//            $sorted = imap_sort($this->imap, $by, $direction);
//
//            $msgs = array_chunk($sorted, $per_page);
//            $msgs = $msgs[$page - 1];
//        } else
            $msgs = range($start, $limit); //just to keep it consistent
            $result = imap_fetch_overview($this->imap, implode($msgs, ','), 0);
            if (count($result) > 0) {
                for ($i = 0; $i < count($result); $i++) {
                    $email[] = $this->formatMessage($result[$i]->msgno, true);
                }
            }
        }

        return $email;

        //sorting!
//        if (true === is_array($sorted)) {
//            $tmp_result = array();
//            foreach ($result as $r)
//                $tmp_result[$r->msgno] = $r;
//
//            $result = array();
//            foreach ($msgs as $msgno) {
//                $result[] = $tmp_result[$msgno];
//            }
//        }

        $return = array('res' => $result,
            'start' => $start,
            'limit' => $limit,
            'sorting' => array('by' => $sort[0], 'direction' => $sort[1]),
            'total' => imap_num_msg($this->imap));
        $return['pages'] = ceil($return['total'] / $per_page);
        return $return;
    }

    /**
     * Retrieve the quota settings per user
     * @return array - FALSE in the case of call failure
     */
    protected function getQuota() {
        error_reporting(0);
        return @imap_get_quotaroot($this->imap, 'INBOX');
    }

    /**
     * Return quota usage in KB
     * @return int - FALSE in the case of call failure
     */
    public function getQuotaUsage() {

        $quota = $this->getQuota();
        if (is_array($quota)) {
            $quota['usage'] = $quota['STORAGE']['usage'];
            $quota['limit'] = $quota['STORAGE']['limit'];
        }
        return $quota;
    }

    function mimie_text_decode($string) {

        $string = htmlspecialchars(chop($string));

        $elements = imap_mime_header_decode($string);
        if (is_array($elements)) {
            for ($i = 0; $i < count($elements); $i++) {
                $charset = $elements[$i]->charset;
                $txt .= $elements[$i]->text;
            }
        } else {
            $txt = $string;
        }
        if ($txt == '') {
            $txt = 'No_name';
        }
        if ($charset == 'us-ascii') {
            //$txt = $this->charset_decode_us_ascii ($txt);
        }
        return $txt;
    }

    function decodeMimeStr($string, $charset = "UTF-8") {
        $newString = '';
        $elements = imap_mime_header_decode($string);
        for ($i = 0; $i < count($elements); $i++) {
            if ($elements[$i]->charset == 'default')
                $elements[$i]->charset = 'iso-8859-1';
            $newString .= iconv($elements[$i]->charset, $charset, $elements[$i]->text);
        }
        return $newString;
    }

    function decode_imap_text($var) {
        if (ereg("=\?.{0,}\?[Bb]\?", $var)) {
            $var = split("=\?.{0,}\?[Bb]\?", $var);

            while (list($key, $value) = each($var)) {
                if (ereg("\?=", $value)) {
                    $arrTemp = split("\?=", $value);
                    $arrTemp[0] = base64_decode($arrTemp[0]);
                    $var[$key] = join("", $arrTemp);
                }
            }
            $var = join("", $var);
        }

        if (ereg("=\?.{0,}\?Q\?", $var)) {
            $var = quoted_printable_decode($var);
            $var = ereg_replace("=\?.{0,}\?[Qq]\?", "", $var);
            $var = ereg_replace("\?=", "", $var);
        }
        return trim($var);
    }

    function decode_qprint($str) {
        $str = preg_replace("/\=([A-F][A-F0-9])/", "%$1", $str);
        $str = urldecode($str);
        $str = utf8_encode($str);
        return $str;
    }

    /**
     * Converts a string from one encoding to another.
     * @param string $string
     * @param string $fromEncoding
     * @param string $toEncoding
     * @return string Converted string if conversion was successful, or the original string if not
     */
    protected function convertStringEncoding($string, $fromEncoding, $toEncoding) {
        $convertedString = null;
        if ($string && $fromEncoding != $toEncoding) {
            $convertedString = @iconv($fromEncoding, $toEncoding . '//IGNORE', $string);
            if (!$convertedString && extension_loaded('mbstring')) {
                $convertedString = @mb_convert_encoding($string, $toEncoding, $fromEncoding);
            }
        }
        return $convertedString ? : $string;
    }

}
