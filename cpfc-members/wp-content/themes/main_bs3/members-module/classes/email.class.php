<?php
/**
 * Simple Email Class
 */

namespace CPFCMembers;

class Email {

    protected $_templatePath;
    protected $_data;

    public function __construct($templatePath, $templateData = array())
    {
        $this->_templatePath = 'members-module/' . $templatePath;
        $this->_data = array_merge($this->_defaultValues(), $templateData);
    }

    /**
     * Returns array of common values used in all Email Templates
     * @return array
     */
    protected function _defaultValues() {
        return array(
            'copyrightDate'    => date('Y'),
            'siteUrl'          => cpfc_home_url(),
            'privacyPolicyUrl' => cpfc_home_url(CPFC_MEMBERS_PRIVACY_POLICY_URL),
            'facebookUrl'      => get_field('emails_facebook_url', 'option'),
            'twitterUrl'       => get_field('emails_twitter_url', 'option'),
            'youtubeUrl'       => get_field('emails_youtube_url', 'option'),
        );
    }

    /**
     * Return Content Type of Email
     */
    public function setHtmlContentType() {
        return 'text/html';
    }

    /**
     * Send Email
     * @param  mixed $to           String or array of recipient(s)
     * @param  string $subject     Email Subject
     * @param  string $message     Message Content
     * @param  mixed $headers      Headers (array or string)
     * @param  mixed $attachments  Files to attach: a single filename, an array of filenames, or a newline-delimited string list of multiple
     * @return NULL
     */
    public function send($to, $subject, $headers = null, $attachments = null) {
        $message = get_partial($this->_templatePath, $this->_data, true);

        if (!defined('SITE_MODE_EMAIL') || SITE_MODE_EMAIL != 'live') {
            $message .= " (Test Email, Original Recipient: {$to})";
            $to = 'cpfc-eagles@mccormackmorrison.com';
            if (defined('DEVELOPER_EMAIL')) {
                $to = DEVELOPER_EMAIL;
            }
        }

        // Add "From" Name and Address
        $headers .= 'From: ' . get_field('emails_from_name', 'option') . ' <' . get_field('emails_from_email_address', 'option') . '>' . "\r\n";

        add_filter( 'wp_mail_content_type', array($this, 'setHtmlContentType'));
        wp_mail($to, $subject, $message, $headers, $attachments);
        remove_filter( 'wp_mail_content_type', array($this, 'setHtmlContentType'));
    }
}