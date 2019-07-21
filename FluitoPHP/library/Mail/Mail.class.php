<?php

/**
 * FluitoPHP(tm): Lightweight MVC (http://www.fluitophp.org)
 * Copyright (c) 2019, Vipin Jain (http://www.codesnsolutions.com)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) 2019, Vipin Jain (http://www.codesnsolutions.com)
 * @link          http://www.fluitophp.org FluitoPHP(tm): Lightweight MVC
 * @since         0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

namespace FluitoPHP\Mail;

/**
 * Mail Class.
 * 
 * This class is for sending email.
 * 
 * Variables:
 *      1. $mailTo
 *      2. $mailCc
 *      3. $mailBcc
 *      4. $mailFrom
 *      5. $replyTo
 *      6. $mailSubject
 *      7. $mailMessagePlain
 *      8. $mailMessageHTML
 *      9. $mailMessageCalender
 *      10. $mailHeaders
 *      11. $mailAttachments
 *      12. $mailImages
 *      13. $mailParameters
 *      14. $config
 *      15. $mailConfig
 *      16. $appConfig
 *      17. $boundaryMixed
 *      18. $boundaryAlternative
 *      19. $boundaryRelated
 *      20. $validEncodings
 *      21. $defaultEncoding
 * 
 * Functions:
 *      1. __construct
 *      2. ValidateEmails
 *      3. ValidateEmail
 *      4. To
 *      5. Cc
 *      6. Bcc
 *      7. From
 *      8. ReplyTo
 *      9. Subject
 *      10. PlainMessage
 *      11. HTMLMessage
 *      12. CalenderMessage
 *      13. Header
 *      14. Attachment
 *      15. Image
 *      16. Parameter
 *      17. Send
 *      18. GenerateTo
 *      19. GenerateHeaders
 *      20. GenerateNameChunk
 *      21. GenerateTextChunk
 *      22. GenerateImageBlock
 *      23. GenerateAttachmentBlock
 *      24. GeneratePlainBlock
 *      25. GenerateHTMLBlock
 *      26. GenerateCalenderBlock
 *      27. GenerateBody
 * 
 * @author Vipin Jain
 * @since  0.1
 */
class Mail {

    /**
     * Used to store the recipients of the email.
     * 
     * @var array
     * @author Vipin Jain
     * @since  0.1
     */
    protected $mailTo = [];

    /**
     * Used to store the CC recipients of the email.
     * 
     * @var array
     * @author Vipin Jain
     * @since  0.1
     */
    protected $mailCc = [];

    /**
     * Used to store the BCC recipients of the email.
     * 
     * @var array
     * @author Vipin Jain
     * @since  0.1
     */
    protected $mailBcc = [];

    /**
     * Used to store the sender of the email.
     * 
     * @var string
     * @author Vipin Jain
     * @since  0.1
     */
    protected $mailFrom = "";

    /**
     * Used to store the reply to email address.
     * 
     * @var string
     * @author Vipin Jain
     * @since  0.1
     */
    protected $replyTo = "";

    /**
     * Used to store the subject of the email.
     * 
     * @var string
     * @author Vipin Jain
     * @since  0.1
     */
    protected $mailSubject = "";

    /**
     * Used to store the message of the email.
     * 
     * @var string
     * @author Vipin Jain
     * @since  0.1
     */
    protected $mailMessagePlain = "";

    /**
     * Used to store the message of the email.
     * 
     * @var string
     * @author Vipin Jain
     * @since  0.1
     */
    protected $mailMessageHTML = "";

    /**
     * Used to store the message of the email.
     * 
     * @var string
     * @author Vipin Jain
     * @since  0.1
     */
    protected $mailMessageCalender = "";

    /**
     * Used to store the email headers.
     * 
     * @var array
     * @author Vipin Jain
     * @since  0.1
     */
    protected $mailHeaders = [];

    /**
     * Used to store the files that needs to be attached to the email.
     * 
     * @var array
     * @author Vipin Jain
     * @since  0.1
     */
    protected $mailAttachments = [];

    /**
     * Used to store the images that needs to be attached to the email.
     * 
     * @var array
     * @author Vipin Jain
     * @since  0.1
     */
    protected $mailImages = [];

    /**
     * Used to store the parameters if required for sendmail executable.
     * 
     * @var string
     * @author Vipin Jain
     * @since  0.1
     */
    protected $mailParameters = "";

    /**
     * Used to store the basic options.
     * 
     * @var array
     * @author Vipin Jain
     * @since  0.1
     */
    protected $config = array(
        'plainEncoding' => 'base64',
        'htmlEncoding' => 'base64',
        'calenderEncoding' => 'base64',
        'plainCharset' => 'ISO-8859-1',
        'htmlCharset' => 'ISO-8859-1',
        'calenderCharset' => 'UTF-8',
        'calenderMethod' => 'request',
        'eol' => "\r\n"
    );

    /**
     * Used to get updated mail configuration of the class.
     * 
     * @var array
     * @author Vipin Jain
     * @since  0.1
     */
    static protected $mailConfig = array(
        'base' => array(),
        'images' => array(
            'nameEncoding' => 'US-ASCII',
            'fileNameEncoding' => 'US-ASCII',
            'type' => 'application/octet-stream'
        ),
        'attachments' => array(
            'nameEncoding' => 'US-ASCII',
            'fileNameEncoding' => 'US-ASCII',
            'encoding' => 'base64',
            'disposition' => 'attachment',
            'type' => 'application/octet-stream',
            'charset' => '',
            'language' => '',
            'location' => '',
            'description' => '',
            'headerCharset' => '',
            'headers' => array()
        ),
    );

    /**
     * Used to get application configuration of the class.
     * 
     * @var array
     * @author Vipin Jain
     * @since  0.1
     */
    static protected $appConfig = null;

    /**
     * Used to store the mixed boundary.
     * 
     * @var string
     * @author Vipin Jain
     * @since  0.1
     */
    protected $boundaryMixed = null;

    /**
     * Used to store the alternative boundary.
     * 
     * @var string
     * @author Vipin Jain
     * @since  0.1
     */
    protected $boundaryAlternative = null;

    /**
     * Used to store the related boundary.
     * 
     * @var string
     * @author Vipin Jain
     * @since  0.1
     */
    protected $boundaryRelated = null;

    /**
     * Used to store valid encodings.
     * 
     * @var array
     * @author Vipin Jain
     * @since  0.1
     */
    static protected $validEncodings = array(
        'quoted-printable',
        'base64',
        '8bit'
    );

    /**
     * Used to store default encoding, used if invalid encoding is provided.
     * 
     * @var string
     * @author Vipin Jain
     * @since  0.1
     */
    static protected $defaultEncoding = 'base64';

    /**
     * Used to initialize the class.
     * 
     * @param array $config Provide the options for the email.
     * @author Vipin Jain
     * @since  0.1
     */
    function __construct($config = []) {

        if (self::$appConfig === null) {

            self::$appConfig = \FluitoPHP\FluitoPHP::GetInstance()->
                    GetConfig('MAIL');

            self::$appConfig = self::$appConfig ? self::$appConfig : [];

            $moduleConfig = \FluitoPHP\FluitoPHP::GetInstance()->
                    GetModuleConfig('MAIL');

            $moduleConfig = $moduleConfig ? $moduleConfig : [];

            self::$appConfig = array_replace_recursive(self::$appConfig, $moduleConfig);

            self::$mailConfig = array_replace_recursive(self::$mailConfig, self::$appConfig);

            self::$mailConfig['attachments']['name'] = '';
            self::$mailConfig['images']['name'] = '';
        }

        if (is_array(self::$mailConfig['base'])) {

            self::$mailConfig['base'] = array_intersect_key(self::$mailConfig['base'], $this->
                    config);

            $this->
                    config = array_replace($this->
                    config, self::$mailConfig['base']);
        }

        if (is_array($config)) {

            $config = array_intersect_key($config, $this->
                    config);

            $this->
                    config = array_replace($this->
                    config, $config);
        }

        $this->
                config['contentSplitLength'] = 76;

        $this->
                boundaryMixed = "=_" . preg_replace("/[^0-9a-z]/i", "", uniqid(time(), true));

        $this->
                boundaryAlternative = "=_" . preg_replace("/[^0-9a-z]/i", "", uniqid(time(), true));

        $this->
                boundaryRelated = "=_" . preg_replace("/[^0-9a-z]/i", "", uniqid(time(), true));

        if (!in_array($this->
                        config['plainEncoding'], self::$validEncodings)) {

            $this->
                    config['plainEncoding'] = self::$defaultEncoding;
        }

        if (!in_array($this->
                        config['htmlEncoding'], self::$validEncodings)) {

            $this->
                    config['htmlEncoding'] = self::$defaultEncoding;
        }

        if (!in_array($this->
                        config['calenderEncoding'], self::$validEncodings)) {

            $this->
                    config['calenderEncoding'] = self::$defaultEncoding;
        }
    }

    /**
     * Used to validate the email array or string.
     * 
     * @param mixed $emails Provide the email list in array or a string with comma (,) or colon (;) separated.
     * @return array Returns the array with valid entries only.
     * @author Vipin Jain
     * @since  0.1
     */
    private function ValidateEmails($emails) {

        $return = [];

        if (is_string($emails)) {

            $emails = str_replace(";", ",", $emails);
            $emails = explode(",", $emails);
        }

        if (!is_array($emails)) {

            return $return;
        }

        $count = count($emails);

        for ($x = 0; $x < $count; $x++) {

            $emails[$x] = $this->
                    ValidateEmail($emails[$x]);

            if ($emails[$x] !== false) {

                $return[] = $emails[$x];
            }
        }

        return $return;
    }

    /**
     * Used to validate an email.
     * 
     * @param string $email Provide the email for validation.
     * @return bool Retyrns true if the email is valid else false.
     * @author Vipin Jain
     * @since  0.1
     */
    public function ValidateEmail($email) {

        $email = trim($email);

        $posAt = strpos($email, "@");

        if ($posAt === false) {

            return false;
        }

        $posAt = strpos($email, "@", $posAt + 1);

        if ($posAt !== false) {

            return false;
        }

        $posAt = strpos($email, "<");

        if ($posAt !== false) {

            $posAt = strpos($email, "<", $posAt + 1);

            if ($posAt !== false) {

                return false;
            }
        }

        $posAt = strpos($email, ">");

        if ($posAt !== false) {

            $posAt = strpos($email, ">", $posAt + 1);

            if ($posAt !== false) {

                return false;
            }
        }

        return $email;
    }

    /**
     * Used to add a new recipient.
     * 
     * @param array $mailTo Provide the receivers list in array or a string with comma (,) or colon (;) separated.
     * @return \FluitoPHP\Mail\Mail Self reference is returned for chained calls.
     * @author Vipin Jain
     * @since  0.1
     */
    public function AddTo($mailTo) {

        $mailTo = $this->
                ValidateEmails($mailTo);

        foreach ($mailTo as $email) {

            $this->
                    mailTo[] = $email;
        }

        return $this;
    }

    /**
     * Used to add a new CC receiver.
     * 
     * @param array $mailCc Provide the CC receivers list in array or a string with comma (,) or colon (;) separated.
     * @return \FluitoPHP\Mail\Mail Self reference is returned for chained calls.
     * @author Vipin Jain
     * @since  0.1
     */
    public function AddCc($mailCc) {

        $mailCc = $this->
                ValidateEmails($mailCc);

        foreach ($mailCc as $email) {

            $this->
                    mailCc[] = $email;
        }

        return $this;
    }

    /**
     * Used to add a new BCC receiver.
     * 
     * @param array $mailBcc Provide the BCC receivers list in array or a string with comma (,) or colon (;) separated.
     * @return \FluitoPHP\Mail\Mail Self reference is returned for chained calls.
     * @author Vipin Jain
     * @since  0.1
     */
    public function AddBcc($mailBcc) {

        $mailBcc = $this->
                ValidateEmails($mailBcc);

        foreach ($mailBcc as $email) {

            $this->
                    mailBcc[] = $email;
        }

        return $this;
    }

    /**
     * Used to update or get sender email and name.
     * 
     * @param string $mailFrom Provide the sender email and/or name.
     * @return mixed Self reference is returned for chained calls if $mailFrom is a string else from email of the mail will be returned.
     * @author Vipin Jain
     * @since  0.1
     */
    public function From($mailFrom = null) {

        if (!is_string($mailFrom)) {

            return $this->
                    mailFrom;
        }

        $mailFrom = $this->
                ValidateEmail($mailFrom);

        if ($mailFrom !== false) {

            $this->
                    mailFrom = $mailFrom;
        }

        return $this;
    }

    /**
     * Used to update or get reply to email.
     * 
     * @param string $replyTo Provide the reply to email.
     * @return mixed Self reference is returned for chained calls if $replyTo is a string else reply to email of the mail will be returned.
     * @author Vipin Jain
     * @since  0.1
     */
    public function ReplyTo($replyTo = null) {

        if (!is_string($replyTo)) {

            return $this->
                    replyTo;
        }

        $replyTo = $this->
                ValidateEmail($replyTo);

        if ($replyTo !== false) {

            $this->
                    replyTo = $replyTo;
        }

        return $this;
    }

    /**
     * Used to update or get mail subject.
     * 
     * @param string $mailSubject Provide the email subject.
     * @return mixed Self reference is returned for chained calls if $mailSubject is a string else subject of the mail will be returned.
     * @author Vipin Jain
     * @since  0.1
     */
    public function Subject($mailSubject = null) {

        if (!is_string($mailSubject)) {

            return $this->
                    mailSubject;
        }

        $this->
                mailSubject = $mailSubject;

        return $this;
    }

    /**
     * Used to update or get mail plain message.
     * 
     * @param string $mailMessagePlain Provide the email message.
     * @return mixed Self reference is returned for chained calls if $mailMessagePlain is a string else message of the mail will be returned.
     * @author Vipin Jain
     * @since  0.1
     */
    public function PlainMessage($mailMessagePlain = null) {

        if (!is_string($mailMessagePlain)) {

            return $this->
                    mailMessagePlain;
        }

        $this->
                mailMessagePlain = $mailMessagePlain;

        return $this;
    }

    /**
     * Used to update or get mail html message.
     * 
     * @param string $mailMessageHTML Provide the email message.
     * @return mixed Self reference is returned for chained calls if $mailMessageHTML is a string else message of the mail will be returned.
     * @author Vipin Jain
     * @since  0.1
     */
    public function HTMLMessage($mailMessageHTML = null) {

        if (!is_string($mailMessageHTML)) {

            return $this->
                    mailMessageHTML;
        }

        $this->
                mailMessageHTML = $mailMessageHTML;

        return $this;
    }

    /**
     * Used to update or get mail calender message.
     * 
     * @param string $mailMessageCalender Provide the email message.
     * @return mixed Self reference is returned for chained calls if $mailMessageCalender is a string else message of the mail will be returned.
     * @author Vipin Jain
     * @since  0.1
     */
    public function CalenderMessage($mailMessageCalender = null) {

        if (!is_string($mailMessageCalender)) {

            return $this->
                    mailMessageCalender;
        }

        $this->
                mailMessageCalender = $mailMessageCalender;

        return $this;
    }

    /**
     * Used to add the email headers.
     * 
     * @param array $mailHeaders Provide the headers that needs to be added to the mail in string format containing one header or in array.
     * @return mixed Self reference is returned for chained calls if $mailHeaders is string or array of headers else message of the mail will be returned.
     * @author Vipin Jain
     * @since  0.1
     */
    public function Header($mailHeaders = null) {

        if ($mailHeaders === null) {

            return $this->
                    mailHeaders;
        }

        if (is_string($mailHeaders)) {

            $mailHeaders = explode($this->
                    config['eol'], $mailHeaders);
        }

        if (!is_array($mailHeaders)) {

            return $this;
        }

        foreach ($mailHeaders as $header) {

            $tempHeader = trim($tempHeader);

            $tempHeader = str_replace("=", ":", $tempHeader);

            $headerSplit = explode(":", $tempHeader);

            $headerType = strtolower($headerSplit[0]);

            $headerKey = false;

            foreach ($this->
            mailHeaders as $hKey => $curHeader) {

                $curHeader = trim($curHeader);

                $curHeader = str_replace("=", ":", $curHeader);

                $curHeaderSplit = explode(":", $curHeader);

                $curHeaderType = strtolower($curHeaderSplit[0]);

                if ($curHeaderType === $headerType) {

                    $headerKey = $hKey;
                    break;
                }
            }

            if ($headerKey) {

                $this->
                        mailHeaders[$headerKey] = $header;
            } else {

                $this->
                        mailHeaders[] = $header;
            }
        }

        return $this;
    }

    /**
     * Used to add the email attachments.
     * 
     * @param string $mailAttachment Provide the attachment path.
     * @param string $name Provide the name of the attachment.
     * @param array $parms Provide additional parameters for the attachment.
     * @return bool Returns true on success and false on failure.
     * @author Vipin Jain
     * @since  0.1
     */
    public function Attachment($mailAttachment, $name = '', $parms = []) {

        if (!is_array($parms) ||
                !isset($mailAttachment) ||
                !is_string($mailAttachment)) {

            return false;
        }

        $mailAttachment = realpath($mailAttachment);

        if (!file_exists($mailAttachment) ||
                is_dir($mailAttachment)) {

            return false;
        }

        $parms = array_intersect_key($parms, self::$mailConfig['attachments']);

        if (isset($parms['charset']) &&
                is_string($parms['charset']) &&
                strlen($parms['charset'])) {

            if (!isset($parms['fileNameEncoding']) ||
                    !is_string($parms['fileNameEncoding']) ||
                    !strlen($parms['fileNameEncoding'])) {

                $parms['fileNameEncoding'] = $parms['charset'];
            }

            if (!isset($parms['nameEncoding']) ||
                    !is_string($parms['nameEncoding']) ||
                    !strlen($parms['nameEncoding'])) {

                $parms['nameEncoding'] = $parms['charset'];
            }
        }

        $parms = array_replace(self::$mailConfig['attachments'], $parms);

        $parms['path'] = $mailAttachment;

        $parms['name'] = $name;

        if (!isset($parms['type']) ||
                !is_string($parms['type']) ||
                !strlen($parms['type'])) {

            $parms['type'] = mime_content_type($mailAttachment);
        }

        if (!in_array($parms['encoding'], self::$validEncodings)) {

            $parms['encoding'] = self::$defaultEncoding;
        }

        $this->
                mailAttachments[] = $parms;

        return true;
    }

    /**
     * Used to add the email images.
     * 
     * @param string $mailImage Provide the image path.
     * @param string $name Provide the name of the image.
     * @param array $parms Provide additional parameters for the image.
     * @return mixed Content ID of the image is returned or false in case of failure.
     * @author Vipin Jain
     * @since  0.1
     */
    public function Image($mailImage, $name = '', $parms = []) {

        if (!is_array($parms) ||
                !isset($mailImage) ||
                !is_string($mailImage)) {

            return false;
        }

        $mailImage = realpath($mailImage);

        if (!file_exists($mailImage) ||
                is_dir($mailImage)) {

            return false;
        }

        $parms = array_intersect_key($parms, self::$mailConfig['images']);

        $parms = array_replace(self::$mailConfig['images'], $parms);

        $parms = array_replace($parms, array(
            'encoding' => 'base64',
            'disposition' => 'inline',
            'contentId' => ''
        ));

        $parms['path'] = $mailImage;

        $parms['name'] = $name;

        if (!isset($parms['type']) ||
                !is_string($parms['type']) ||
                !strlen($parms['type'])) {

            $parms['type'] = mime_content_type($mailAttachment);
        }

        $extension = pathinfo($mailImage, PATHINFO_EXTENSION);

        $parms['contentId'] = uniqid("Image" . sprintf("%1$04d", count($this->
                                mailImages) + 1) .
                (strlen($extension) ? ".{$extension}@" : "@") .
                uniqid() . '.');

        $this->
                mailImages[] = $parms;

        return $parms['contentId'];
    }

    /**
     * Used to update or get mail parameters.
     * 
     * @param string $mailParameters Provide additional parameters.
     * @return \FluitoPHP\Mail\Mail Self reference is returned for chained calls.
     * @author Vipin Jain
     * @since  0.1
     */
    public function Parameters($mailParameters = null) {

        if (!is_string($mailParameters)) {

            return $this->
                    mailParameters;
        }

        $this->
                mailParameters = $mailParameters;

        return $this;
    }

    /**
     * Used to send the email.
     * 
     * @return bool Returns if the mail is successfully sent to the mail server for sending.
     * @author Vipin Jain
     * @since  0.1
     */
    public function Send() {

        $return = mail($this->
                        GenerateTo(), $this->
                        Subject(), $this->
                        GenerateBody(), $this->
                        GenerateHeaders(), $this->Parameters());

        return $return;
    }

    /**
     * Used to generate the comma separated recipients emails.
     * 
     * @return string Returns the comma separated recipients emails.
     * @author Vipin Jain
     * @since  0.1
     */
    public function GenerateTo() {

        if (count($this->
                        mailTo) === 0) {

            return "";
        }

        return implode(",", $this->
                mailTo);
    }

    /**
     * Used to generate the headers in string format.
     * 
     * @return string Returns the headers in string format.
     * @author Vipin Jain
     * @since  0.1
     */
    public function GenerateHeaders() {

        $headers = [];

        if (count($this->
                        mailAttachments)) {

            $headers = array(
                'MIME-Version: 1.0',
                'Content-Type: multipart/mixed;',
                " boundary=\"{$this->
                boundaryMixed}\""
            );
        } else if ((strlen($this->
                        mailMessagePlain) &&
                strlen($this->
                        mailMessageHTML)) ||
                (strlen($this->
                        mailMessagePlain) &&
                strlen($this->
                        mailMessageCalender)) ||
                (strlen($this->
                        mailMessageHTML) &&
                strlen($this->
                        mailMessageCalender))) {

            if (count($this->
                            mailImages)) {

                if (strlen($this->
                                mailMessageHTML)) {

                    if (strlen($this->
                                    mailMessagePlain)) {

                        $headers = array(
                            'MIME-Version: 1.0',
                            'Content-Type: multipart/alternative;',
                            " boundary=\"{$this->
                            boundaryAlternative}\""
                        );
                    } else {

                        $headers = array(
                            'MIME-Version: 1.0',
                            'Content-Type: multipart/related;',
                            " boundary=\"{$this->
                            boundaryRelated}\""
                        );
                    }
                } else {

                    $headers = array(
                        'MIME-Version: 1.0',
                        'Content-Type: multipart/alternative;',
                        " boundary=\"{$this->
                        boundaryAlternative}\""
                    );
                }
            } else {

                $headers = array(
                    'MIME-Version: 1.0',
                    'Content-Type: multipart/alternative;',
                    " boundary=\"{$this->
                    boundaryAlternative}\""
                );
            }
        } else if (strlen($this->
                        mailMessageCalender)) {

            $headers = array(
                'MIME-Version: 1.0',
                "Content-Type: text/calender; charset={$this->
                config['calenderCharset']}; method={$this->
                config['calenderMethod']}",
                "Content-Transfer-Encoding: {$this->
                config['calenderEncoding']}"
            );
        } else if (strlen($this->
                        mailMessagePlain)) {

            $headers = array(
                'MIME-Version: 1.0',
                "Content-Type: text/plain; charset={$this->
                config['plainCharset']};",
                "Content-Transfer-Encoding: {$this->
                config['plainEncoding']}"
            );
        } else if (strlen($this->
                        mailMessageHTML)) {

            if (count($this->
                            mailImages)) {

                $headers = array(
                    'MIME-Version: 1.0',
                    'Content-Type: multipart/related;',
                    " boundary=\"{$this->
                    boundaryRelated}\""
                );
            } else {

                $headers = array(
                    'MIME-Version: 1.0',
                    "Content-Type: text/html; charset={$this->
                    config['htmlCharset']};",
                    "Content-Transfer-Encoding: {$this->
                    config['htmlEncoding']}"
                );
            }
        } else {
            $headers = array(
                'MIME-Version: 1.0'
            );
        }

        if (count($this->
                        mailTo)) {

            $headers[] = 'To: ' . implode(", ", $this->
                            mailTo);
        }

        if (count($this->
                        mailCc)) {

            $headers[] = 'Cc: ' . implode(", ", $this->
                            mailCc);
        }

        if (count($this->
                        mailBcc)) {

            $headers[] = 'Bcc: ' . implode(", ", $this->
                            mailBcc);
        }

        if (strlen($this->
                        mailFrom)) {

            $headers[] = "From: {$this->
                    mailFrom}";
        }

        if (strlen($this->
                        replyTo)) {

            $headers[] = "Reply-To: {$this->
                    replyTo}";
        }

        foreach ($this->
        mailHeaders as $header) {

            $tempHeader = trim($tempHeader);

            $tempHeader = str_replace("=", ":", $tempHeader);

            $headerSplit = explode(":", $tempHeader);

            $headerType = strtolower($headerSplit[0]);

            $headerKey = false;

            foreach ($headers as $hKey => $curHeader) {

                $curHeader = trim($curHeader);

                $curHeader = str_replace("=", ":", $curHeader);

                $curHeaderSplit = explode(":", $curHeader);

                $curHeaderType = strtolower($curHeaderSplit[0]);

                if ($curHeaderType === $headerType) {

                    $headerKey = $hKey;
                    break;
                }
            }

            if ($headerKey === false) {

                $headers = $header;
            }
        }

        return implode($this->
                        config['eol'], $headers) . $this->
                config['eol'];
    }

    /**
     * Used to chunk the name of the file.
     * 
     * @param string $pathOrName Provide the path or name to be chunked.
     * @param string $encoding Provide the encoding for the chunk.
     * @param string $type Provide the name type for the chunk. (name, filename)
     * @return string Returns the chunked text.
     * @author Vipin Jain
     * @since  0.1
     */
    protected function GenerateNameChunk($pathOrName, $encoding = 'US-ASCII', $type = 'name') {

        if (!in_array($type, array(
                    'name',
                    'filename'
                ))) {

            $type = 'name';
        }

        $return = "";

        $basename = rawurlencode(pathinfo($pathOrName, PATHINFO_BASENAME));

        if (strlen(" {$type}={$basename}") <= $this->
                config['contentSplitLength'] - 2) {

            $return = " {$type}={$basename}";
        } else {

            $basename = $encoding . "''" . $basename;
            $counter = 0;

            while (strlen($basename)) {

                $prefix = " {$type}*{$counter}*=";

                $requiredLength = $this->
                        config['contentSplitLength'] - 2 - strlen($prefix);

                if ($requiredLength > strlen($basename)) {

                    $requiredLength = strlen($basename);
                }

                $stripped = substr($basename, 0, $requiredLength);

                $basename = substr($basename, $requiredLength);

                $return .= "{$prefix}{$stripped}" . (strlen($basename) ? ";" . $this->
                        config['eol'] : "");
            }
        }

        return $return;
    }

    /**
     * Used to chunk the text string.
     * 
     * @param string $text Provide the text string to be chunked.
     * @param string $encoding Provide the encoding to be used for the text.
     * @return string Returns the generated chunked text string.
     * @author Vipin Jain
     * @since  0.1
     */
    protected function GenerateTextChunk($text, $encoding) {

        if (!is_string($text)) {

            return "";
        }

        $return = "";

        switch ($encoding) {
            case 'quoted-printable':

                $text = quoted_printable_encode($text);

                $splitLength = $this->
                        config['contentSplitLength'] - 1;

                $return = preg_replace("/(.{$splitLength}[^\=]{0,3})/", '$1' . $this->
                        config['eol'], $text);

                break;
            case 'base64':

                $text = base64_encode($text);

                $return = chunk_split($text, $this->
                        config['contentSplitLength'] - 1, $this->
                        config['eol']);

                break;
            default:

                $textArray = explode($this->
                        config['eol'], $text);

                foreach ($textArray as $line) {
                    if (strlen($line) > $this->
                            config['contentSplitLength'] - 1) {

                        $line = chunk_split($line, $this->
                                config['contentSplitLength'] - 1, "=" . $this->
                                config['eol']);

                        $line = rtrim($line, "=" . $this->
                                config['eol']);
                    }

                    $return .= (strlen($return) ? $this->
                            config['eol'] : "") . $line;
                }
                break;
        }

        return $return;
    }

    /**
     * Used to generate image block for mail.
     * 
     * @return string Returns the generated image block for mail.
     * @author Vipin Jain
     * @since  0.1
     */
    protected function GenerateImageBlock() {

        $return = "";

        foreach ($this->
        mailImages as $image) {

            $file = new \FluitoPHP\File\File($image['path'], 'r');

            $fileContent = chunk_split(base64_encode($file->
                                    Read()));

            $fileSize = $file->
                    Size();

            $fileName = (is_string($image['name']) &&
                    strlen($image['name']) ? $image['name'] : $image['path']);

            $return .= "--{$this->
                    boundaryRelated}" . $this->
                    config['eol'];

            $return .= "Content-Transfer-Encoding: {$image['encoding']}" . $this->
                    config['eol'];

            $return .= "Content-ID: <{$image['contentId']}>" . $this->
                    config['eol'];

            $return .= "Content-Type: {$image['type']};" . $this->
                    config['eol'];

            $return .= $this->
                            GenerateNameChunk($fileName, $image['nameEncoding'], 'name') . $this->
                    config['eol'];

            $return .= "Content-Disposition: {$image['disposition']};" . $this->
                    config['eol'];

            $return .= $this->
                            GenerateNameChunk($fileName, $image['fileNameEncoding'], 'filename') . ";" . $this->
                    config['eol'];

            $return .= " size={$fileSize}" . $this->
                    config['eol'] . $this->
                    config['eol'];

            $return .= $fileContent;
        }

        $return .= "--{$this->
                boundaryRelated}--" . $this->
                config['eol'];

        return $return;
    }

    /**
     * Used to generate attachment block for mail.
     * 
     * @return string Returns the generated attachment block for mail.
     * @author Vipin Jain
     * @since  0.1
     */
    protected function GenerateAttachmentBlock() {

        $return = "";

        foreach ($this->
        mailAttachments as $attachment) {

            $file = new \FluitoPHP\File\File($attachment['path'], 'r');

            $fileContent = chunk_split(base64_encode($file->
                                    Read()));

            $fileSize = $file->
                    Size();

            $fileName = (is_string($attachment['name']) &&
                    strlen($attachment['name']) ? $attachment['name'] : $attachment['path']);

            $return .= "--{$this->
                    boundaryMixed}" . $this->
                    config['eol'];

            $return .= "Content-Transfer-Encoding: {$attachment['encoding']}" . $this->
                    config['eol'];

            $return .= "Content-Type: {$attachment['type']};" .
                    ($attachment['charset'] ? " charset={$attachment['charset']};" : "") . $this->
                    config['eol'];

            $return .= $this->
                            GenerateNameChunk($fileName, $attachment['nameEncoding'], 'name') . $this->
                    config['eol'];

            $return .= "Content-Disposition: {$attachment['disposition']};" . $this->
                    config['eol'];

            $return .= $this->
                            GenerateNameChunk($fileName, $attachment['fileNameEncoding'], 'filename') . ";" . $this->
                    config['eol'];

            $return .= " size={$fileSize}" . $this->
                    config['eol'] . $this->
                    config['eol'];

            $return .= $fileContent;
        }

        $return .= "--{$this->
                boundaryMixed}--" . $this->
                config['eol'];

        return $return;
    }

    /**
     * Used to generate the plain text block of the mail.
     * 
     * @return string Returns the generated plain text block of the mail.
     * @author Vipin Jain
     * @since  0.1
     */
    protected function GeneratePlainBlock() {

        $return = "";

        $return .= "Content-Transfer-Encoding: {$this->
                config['plainEncoding']}" . $this->
                config['eol'];

        $return .= "Content-Type: text/plain; charset={$this->
                config['plainCharset']}" . $this->
                config['eol'] . $this->
                config['eol'];

        $return .= $this->
                        GenerateTextChunk($this->
                                mailMessagePlain, $this->
                                config['plainEncoding']) . $this->
                config['eol'];

        return $return;
    }

    /**
     * Used to generate the html text block of the mail.
     * 
     * @return string Returns the generated html text block of the mail.
     * @author Vipin Jain
     * @since  0.1
     */
    protected function GenerateHTMLBlock() {

        $return = "";

        $return .= "Content-Transfer-Encoding: {$this->
                config['htmlEncoding']}" . $this->
                config['eol'];

        $return .= "Content-Type: text/html; charset={$this->
                config['htmlCharset']}" . $this->
                config['eol'] . $this->
                config['eol'];

        $return .= $this->
                        GenerateTextChunk($this->
                                mailMessageHTML, $this->
                                config['htmlEncoding']) . $this->
                config['eol'];

        return $return;
    }

    /**
     * Used to generate the calender text block of the mail.
     * 
     * @return string Returns the generated calender text block of the mail.
     * @author Vipin Jain
     * @since  0.1
     */
    protected function GenerateCalenderBlock() {

        $return = "";

        $return .= "Content-Transfer-Encoding: {$this->
                config['calenderEncoding']}" . $this->
                config['eol'];

        $return .= "Content-Type: text/calender; charset={$this->
                config['calenderCharset']}; method={$this->
                config['calenderMethod']}" . $this->
                config['eol'] . $this->
                config['eol'];

        $return .= $this->
                        GenerateTextChunk($this->
                                mailMessageCalender, $this->
                                config['calenderEncoding']) . $this->
                config['eol'];

        return $return;
    }

    /**
     * Used to generate the message body.
     * 
     * @return string Returns the generated message body.
     * @author Vipin Jain
     * @since  0.1
     */
    public function GenerateBody() {

        $body = "";

        if (count($this->
                        mailAttachments)) {

            $body .= "--{$this->
                    boundaryMixed}" . $this->
                    config['eol'];

            if (strlen($this->
                            mailMessagePlain) && strlen($this->
                            mailMessageHTML)) {

                $body .= "Content-Type: multipart/alternative;" . $this->
                        config['eol'];

                $body .= " boundary=\"{$this->
                        boundaryAlternative}\"" . $this->
                        config['eol'] . $this->
                        config['eol'];

                $body .= "--{$this->
                        boundaryAlternative}" . $this->
                        config['eol'];

                $body .= $this->
                        GeneratePlainBlock();

                $body .= "--{$this->
                        boundaryAlternative}" . $this->
                        config['eol'];

                if (count($this->
                                mailImages)) {

                    $body .= "Content-Type: multipart/related;" . $this->
                            config['eol'];

                    $body .= " boundary=\"{$this->
                            boundaryRelated}\"" . $this->
                            config['eol'] . $this->
                            config['eol'];

                    $body .= "--{$this->
                            boundaryRelated}" . $this->
                            config['eol'];
                }

                $body .= $this->
                        GenerateHTMLBlock();

                if (count($this->
                                mailImages)) {

                    $body .= $this->
                                    GenerateImageBlock() . $this->
                            config['eol'];
                }

                $body .= "--{$this->
                        boundaryAlternative}--" . $this->
                        config['eol'] . $this->
                        config['eol'];
            } else if (strlen($this->
                            mailMessagePlain)) {

                $body .= $this->
                        GeneratePlainBlock();
            } else if (strlen($this->
                            mailMessageHTML)) {

                if (count($this->
                                mailImages)) {

                    $body .= "Content-Type: multipart/related;" . $this->
                            config['eol'];

                    $body .= " boundary=\"{$this->
                            boundaryRelated}\"" . $this->
                            config['eol'] . $this->
                            config['eol'];

                    $body .= "--{$this->
                            boundaryRelated}" . $this->
                            config['eol'];
                }

                $body .= $this->
                        GenerateHTMLBlock();

                if (count($this->
                                mailImages)) {

                    $body .= $this->
                                    GenerateImageBlock() . $this->
                            config['eol'];
                }
            }

            $body .= $this->
                    GenerateAttachmentBlock();
        } else if ((strlen($this->
                        mailMessagePlain) &&
                strlen($this->
                        mailMessageHTML)) ||
                (strlen($this->
                        mailMessagePlain) &&
                strlen($this->
                        mailMessageCalender)) ||
                (strlen($this->
                        mailMessageHTML) &&
                strlen($this->
                        mailMessageCalender))) {

            if (count($this->
                            mailImages)) {

                if (strlen($this->
                                mailMessageHTML)) {

                    if (strlen($this->
                                    mailMessagePlain)) {

                        $body .= "--{$this->
                                boundaryAlternative}" . $this->
                                config['eol'];

                        $body .= $this->
                                GeneratePlainBlock();

                        $body .= "--{$this->
                                boundaryAlternative}" . $this->
                                config['eol'];

                        $body .= "Content-Type: multipart/related;" . $this->
                                config['eol'];

                        $body .= " boundary=\"{$this->
                                boundaryRelated}\"" . $this->
                                config['eol'] . $this->
                                config['eol'];

                        $body .= "--{$this->
                                boundaryRelated}" . $this->
                                config['eol'];

                        $body .= $this->
                                GenerateHTMLBlock();

                        $body .= $this->
                                        GenerateImageBlock() . $this->
                                config['eol'];

                        $body .= "--{$this->
                                boundaryAlternative}--" . $this->
                                config['eol'] . $this->
                                config['eol'];
                    } else {

                        $body .= "--{$this->
                                boundaryRelated}" . $this->
                                config['eol'];

                        $body .= $this->
                                GenerateHTMLBlock();

                        $body .= $this->
                                GenerateImageBlock();
                    }
                } else {

                    $body .= "--{$this->
                            boundaryAlternative}" . $this->
                            config['eol'];

                    $body .= $this->
                            GeneratePlainBlock();

                    $body .= "--{$this->
                            boundaryAlternative}" . $this->
                            config['eol'];

                    $body .= $this->
                            GenerateCalenderBlock();

                    $body .= "--{$this->
                            boundaryAlternative}--" . $this->
                            config['eol'] . $this->
                            config['eol'];
                }
            } else {

                if (strlen($this->
                                mailMessagePlain)) {

                    $body .= "--{$this->
                            boundaryAlternative}" . $this->
                            config['eol'];

                    $body .= $this->
                            GeneratePlainBlock();
                }

                if (strlen($this->
                                mailMessageHTML)) {

                    $body .= "--{$this->
                            boundaryAlternative}" . $this->
                            config['eol'];

                    $body .= $this->
                            GenerateHTMLBlock();
                }

                if (strlen($this->
                                mailMessageCalender)) {

                    $body .= "--{$this->
                            boundaryAlternative}" . $this->
                            config['eol'];

                    $body .= $this->
                            GenerateCalenderBlock();
                }

                $body .= "--{$this->
                        boundaryAlternative}--" . $this->
                        config['eol'] . $this->
                        config['eol'];
            }
        } else if (strlen($this->
                        mailMessageCalender)) {

            $body .= $this->
                    GenerateTextChunk($this->
                    mailMessageCalender);
        } else if (strlen($this->
                        mailMessagePlain)) {

            $body .= $this->
                    GenerateTextChunk($this->
                    mailMessagePlain);
        } else if (strlen($this->
                        mailMessageHTML)) {

            if (count($this->
                            mailImages)) {

                $body .= "--{$this->
                        boundaryRelated}" . $this->
                        config['eol'];

                $body .= $this->
                        GenerateHTMLBlock();

                $body .= $this->
                        GenerateImageBlock();
            } else {

                $body .= $this->
                        GenerateTextChunk($this->
                        mailMessageHTML);
            }
        }

        return $body;
    }

}
