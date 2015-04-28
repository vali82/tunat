<?php
namespace Application\Mail;

use Application\Libs\General;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Mail;
use Zend\Mail\Transport\Smtp as SmtpTransport;
use Zend\Mail\Transport\SmtpOptions;
use Zend\Mime\Part as MimePart;
use Zend\Mime\Message as MimeMessage;
use \DateTime;

class MailGeneral extends AbstractActionController
{
    public $_to;
    public $_from;
	public $_subdomain;
	public $_no_reply;
    protected $_subject;
    protected $_message;
    protected $translator;
    protected $_config;
    protected $_general_config;
    protected $_log2hdd = false; // keep mails into logs

	protected $method = 'mandrill'; // mandrill OR null if zf2 mail


    public function __construct($config)
    {
        $this->_general_config = $config;
       // die('dsfsfd');
        $conf = $config->get('config');
        $this->translator = $config->get('translator');

        $this->_config = $conf['email'];
        $this->_site_names = $conf['site_names'];
    }

    protected function header($action, $user_type = 'user')
    {
        return
            '
<div style="background: font-family: \'Source Sans Pro\', Arial;color: #577897;font-size: 15px;font-weight: 100;line-height: 19px;text-align: left;min-height: 500px;">
    <style type="text/css">
        @import url(http://fonts.googleapis.com/css?family=Source+Sans+Pro:100,400,300,700,700italic,900);
    </style>
    <div style="margin: 0px auto; width: 640px; padding-top: 20px; overflow: hidden;">
        <div style="padding: 0 15px;">
            <img src="' . $this->_site_names['http'] . '/img/logo.png" alt="" style="float:left;">
            <a style="background: #094B88;color:#fff;display: block;border: 0 none;border-bottom: 4px solid #0A447B;box-shadow:inset 0 2px 4px rgba(255,255,255,0.2), 0 3px 5px rgba(0,0,0,0.2);height: 32px;padding: 0 24px;float: right;font-family: \'Source Sans Pro\', Arial;font-size: 13px;font-weight: bold;height: 32px;line-height: 34px;padding: 0 24px;text-decoration: none;" href="' . $action[0]['href'] . '">' . $action[0]['name'] . '</a>
            ' . (isset($action[1]) ? '<a style="background: #094B88;color:#fff;display: block;border: 0 none;border-bottom: 4px solid #0A447B;box-shadow:inset 0 2px 4px rgba(255,255,255,0.2), 0 3px 5px rgba(0,0,0,0.2);height: 32px;padding: 0 24px;float: right;font-family: \'Source Sans Pro\', Arial;font-size: 13px;font-weight: bold;height: 32px;line-height: 34px;margin-right:10px;padding: 0 24px;text-decoration: none;" href="' . $action[1]['href'] . '">' . $action[0]['name'] . '</a>' : '') . '
            <div style="float: left; width: 100%; margin-bottom: 20px;">&nbsp;</div>
            <img src="' . $this->_site_names['http'] . '/img/mail/' . ($user_type == 'user' ? 'user_img.jpg' : 'merchant.png') . '" alt="" style="float:left; padding:0 20px;">
            <h1 style="color: #5A7A99;float: left;font-family: \'Source Sans Pro\', Arial;font-size: 49px;font-weight: 900;letter-spacing: -1px;line-height: 42px;margin: 0;">
';
    }

    protected function footer()
    {
        return
            '
	<div style="float: left; width: 100%;padding-bottom:30px;">&nbsp;</div>

        <div class="footer" style="font-family: \'Source Sans Pro\', Arial;background: #F9F9F9; overflow: hidden; width: 96%; float: left; padding: 2%; border-top: 1px solid rgb(237, 237, 237); border-bottom: 1px solid rgb(237, 237, 237);">
            <div style="margin: 0px auto 10px; float: left; width: 100%; text-align: center;">
                <a style="color: rgb(87, 120, 151); font-size: 13px; line-height: 20px; text-decoration: none; border-right: 1px solid rgb(221, 221, 221); padding-right: 10px; margin-right: 5px;" href="#">Politica de confidențialitate</a>
                 <a style="color: #577897; font-size: 13px;line-height: 20px;text-decoration: none;" href="#">Termeni si conditii</a>
            </div>
            <div style="margin: 0px auto 10px; float: left; width: 100%; text-align: center;">
                <a href="#"><img alt="facebook" src="' . $this->_site_names['http'] . '/img/mail/social-fb.png"></a>
                <a href="#"><img alt="twitter" src="' . $this->_site_names['http'] . '/img/mail/social-tw.png"></a>
                <a href="#"><img alt="linked in" src="' . $this->_site_names['http'] . '/img/mail/social-li.png"></a>
                <a href="#"><img alt="google plus" src="' . $this->_site_names['http'] . '/img/mail/social-gp.png"></a>
            </div>
            
            
            <p style="font-family: \'Source Sans Pro\', Arial;float: left; width: 100%; text-align: center; margin: 0px;color: #577897; font-size: 13px;">Copyright &copy; 2015 '.$this->_site_names['name'].'&reg; LLC. Toate drepturile rezervate. </p>
            <p style="float: left; color: #577897; font-size: 15px; font-weight: 900; width: 100%; text-align: center; margin: 5px 0px;">Contact:</p>
            <p style="font-family: \'Source Sans Pro\', Arial;float: left; width: 100%; text-align: center; margin: 0px;color: #577897; font-size: 13px;">E-mail: info@kinderpedia.ro</p>
            <p style="font-family: \'Source Sans Pro\', Arial;float: left; width: 100%; text-align: center; margin: 0px;color: #577897; font-size: 13px;">Telefon: 0732.234.123</p>

        </div>
        <div style="float: left; height:80px; width: 100%;">&nbsp;</div>
    </div>
</div>
';
    }


	protected function mandrillAction()
	{
		$from = ($this->_from !== null ? array('email'=>$this->_from['email'], 'name'=>$this->_from['name']) : array('email'=>$this->_config['from']['email'], 'name'=>$this->_config['from']['name']));
		try
		{
			$mandrill = new \Mandrill($this->_config['mandrill']['key']);

			$subdomain_id = APPLICATION_ENV.'-default';

			$message = array(
				'html' => $this->_message,
				//'text' => 'Example text content',
				'subject' => $this->_subject,
				'from_email' => $from['email'],
				'from_name' => $from['name'],
				'to' => array(
					array(
						'email' => $this->_to,
						'name' => '',
						'type' => 'to'
					)
				),
				'headers' => ($this->_no_reply === true ? array('Reply-To' => 'no-reply@'.$this->_site_names['domain']) : array('Reply-To' => $from['email'])),
				'important' => false,
				'track_opens' => null,
				'track_clicks' => null,
				'auto_text' => null,
				'auto_html' => null,
				'inline_css' => null,
				'url_strip_qs' => null,
				'preserve_recipients' => null,
				'view_content_link' => null,
				//'bcc_address' => 'message.bcc_address@example.com',
				'tracking_domain' => null,
				'signing_domain' => null,
				'return_path_domain' => null,
				/*'merge' => true,
				'global_merge_vars' => array(
					array(
						'name' => 'merge1',
						'content' => 'merge1 content'
					)
				),
				'merge_vars' => array(
					array(
						'rcpt' => 'recipient.email@example.com',
						'vars' => array(
							array(
								'name' => 'merge2',
								'content' => 'merge2 content'
							)
						)
					)
				),
				'tags' => array(''),*/
				'subaccount' => $subdomain_id,
				//'google_analytics_domains' => array('example.com'),
				//'google_analytics_campaign' => 'message.from_email@example.com',
				//'metadata' => array('website' => 'www.example.com'),
				/*'recipient_metadata' => array(
					array(
						'rcpt' => 'recipient.email@example.com',
						'values' => array('user_id' => 123456)
					)
				),*/
				/*'attachments' => array(
					array(
						'type' => 'text/plain',
						'name' => 'myfile.txt',
						'content' => 'ZXhhbXBsZSBmaWxl'
					)
				),
				'images' => array(
					array(
						'type' => 'image/png',
						'name' => 'IMAGECID',
						'content' => 'ZXhhbXBsZSBmaWxl'
					)
				)*/
			);


			$async = false;
			$ip_pool = 'Main Pool';
			$send_at = '';
			$ret = $mandrill->messages->send($message, $async, $ip_pool, $send_at);
			return array('error'=>false, 'message'=>'');
			//print_r($result);
			/*
			Array
			(
				[0] => Array
					(
						[email] => recipient.email@example.com
						[status] => sent
						[reject_reason] => hard-bounce
						[_id] => abc123abc123abc123abc123abc123
					)

			)
			*/
		} catch(\Mandrill_Error $e) {

			return array('error'=>true, 'message'=>$e->getMessage());
			// Mandrill errors are thrown as exceptions
			//echo 'A mandrill error occurred: ' . get_class($e) . ' - ' . $e->getMessage();
			// A mandrill error occurred: Mandrill_Unknown_Subaccount - No subaccount exists with the id 'customer-123'
			//throw $e;
		}
	}

    protected function sendAction()
    {

	    if ($this->method === 'mandrill') {

		    if (APPLICATION_ENV != 'development' || 1==1) {
			    $response = $this->mandrillAction();
		    } else {
			    $response = array('error'=>false, 'message'=>'');
		    }

	    } else {

	        $body = $this->_message;
	        $htmlPart = new MimePart($body);
	        $htmlPart->type = "text/html";

	        $textPart = new MimePart($body);
	        $textPart->type = "text/plain";

	        $body = new MimeMessage();
	        $body->setParts(array($textPart, $htmlPart));

	        $_message = new Mail\Message();
	        if ($this->_from !== null) {
	            $_message->setFrom($this->_from['email'], $this->_from['name']);
	        } else {
	            $_message->setFrom($this->_config['from']['email'], $this->_config['from']['name']);
	        }
	        $_message->addTo($this->_to);
	        //$message->addReplyTo($reply);
	        /* if ($cc)
	            $message->addCc($cc);
	        if ($bcc)
	            $message->addBcc($bcc); */
	        //$message->setSender($sender);
	        $_message->setSubject($this->_subject);
	        $_message->setEncoding("UTF-8");
	        $_message->setBody($body);
	        $_message->getHeaders()->get('content-type')->setType('multipart/alternative');

	        $transport = new Mail\Transport\Sendmail();

	        if (APPLICATION_ENV != 'development') {
	            $transport->send($_message);
	        }
		    $response = array('error'=>false, 'message'=>'');
	    }

        // log mail to hdd
        if ($this->_log2hdd) {
	        $from = ($this->_from !== null ? array('email'=>$this->_from['email'], 'name'=>$this->_from['name']) : array('email'=>$this->_config['from']['email'], 'name'=>$this->_config['from']['name']));

	        $now = new DateTime();

            $structure = __DIR__ . '/../../../../../data/mails/' . $now->format('Y');
            if (!is_dir($structure)) {
                mkdir($structure, 0777, true);
                chmod($structure, 0777);
            }
            $structure = __DIR__ . '/../../../../../data/mails/' . $now->format('Y') . '/' . $now->format('m');
            if (!is_dir($structure)) {
                mkdir($structure, 0777, true);
                chmod($structure, 0777);
            }
            $structure = __DIR__ . '/../../../../../data/mails/' . $now->format('Y') . '/' . $now->format('m') . '/' . $now->format('d');
            if (!is_dir($structure)) {
                mkdir($structure, 0777, true);
                chmod($structure, 0777);
            }
            $filename = $structure . '/' . $now->format('H_i_s') . '__' . str_replace(' ', '', $this->_subject) . '_' . rand(1000, 9999) . '.html';
            ob_start();
            echo '<div>from: ' . $from['email'] . ' ' . $from['name'] . "<br />";
            echo 'to: ' . $this->_to . "<br />";
            echo 'title: ' . $this->_subject . "<hr>";
            //echo '-----------------------------------------'."\r\n";
            //echo 'Body: '."\r\n";
            echo ($this->_message);
            $somecontent = ob_get_contents();
            ob_end_clean();

            $handle = @fopen($filename, 'a');
            @fwrite($handle, $somecontent);
            @fclose($handle);
        }
        ////

        return $response;
    }









    public function forgotPassword($name, $hash)
    {
        $this->_subject = $this->translator->translate('Reseatare Parola Kinderpedia');
        $this->_message =
            $this->header(array(array('href' => $this->_site_names['http'] . '/reset-password/' . $hash . '', 'name' => 'Reseteaza Parola')), 'user') . '
Hello -<BR>' . $name . '</h1>
<div style="font-family: \'Source Sans Pro\', Arial;float: left; width: 100%;">&nbsp;</div>
<p style="font-family: \'Source Sans Pro\', Arial;float: left; margin: 30px 0 0; background: none repeat scroll 0px 0px rgb(237, 237, 244);color: #4A4A4A;padding: 2%; width: 96%; border-bottom: 1px solid rgb(221, 221, 221);">
	Poti sa iti schimbi parola <a href="' . $this->_site_names['http'] . '/reset-password/' . $hash . '">aici</a>.
</p></div>' .

                $this->footer();

	    return $this->sendAction();
    }

}