<?php
/**
 * Created by PhpStorm.
 * User: frank
 * Date: 13/04/2018
 * Time: 06:34
 */

class MY_Email extends CI_Email
{
    /**
     * @access public
     * @var string $protocol mail/sendmail/smtp
     */
    var $protocol = "smtp";

    /**
     * SMTP Server
     *
     * @access public
     * @var string $smtp_host Example: mail.earthlink.net
     */
    var $smtp_host = "rhsuite.com.br";

    /**
     * SMTP Username
     *
     * @access public
     * @var string $smtp_user
     */
    var $smtp_user = "contato@rhsuite.com.br";

    /**
     * SMTP Password
     *
     * @access public
     * @var string $smtp_pass
     */
    var $smtp_pass = "contato@314";

    /**
     * SMTP Port
     *
     * @access public
     * @var string $smtp_port
     */
    var $smtp_port = "465";

    /**
     * SMTP Encryption
     *
     * @access public
     * @var string|null $smtp_crypto Can be null, tls or ssl
     */
    var $smtp_crypto = "ssl";

    /**
     * Defines email formatting
     *
     * @access public
     * @var string $mailtype text/html
     */
    var $mailtype = "html";

    /**
     * Enables email validation
     *
     * @access public
     * @var bool $validate
     */
    var $validate = true;

}