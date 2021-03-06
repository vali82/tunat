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
	public $_no_reply;
    public $bcc = null;
    protected $_subject;
    protected $_message;
    protected $_content;
    protected $translator;
    protected $_config;
    protected $_general_config;
    protected $_log2hdd = false; // keep mails into logs

	protected $method = null; // mandrill OR null if zf2 mail


    public function __construct($config)
    {
        $this->_general_config = $config;
       // die('dsfsfd');
        $conf = $config->get('config');
        $this->translator = $config->get('translator');

        $this->_config = $conf['email'];
        $this->_site_names = $conf['site_names'];
        $this->_log2hdd = APPLICATION_ENV == 'development' ? true : false;
    }

    protected function template($action)
    {
        return '
<div style="background: font-family: \'Source Sans Pro\', Arial;color: #577897;font-size: 15px;font-weight: 100;line-height: 19px;text-align: left;min-height: 500px;">
    <style type="text/css">
        @import url(http://fonts.googleapis.com/css?family=Source+Sans+Pro:100,400,300,700,700italic,900);
    </style>
    <div style="margin: 0px auto; width: 640px; padding-top: 20px; overflow: hidden;">
        <div style="padding: 0 15px;">
            <img src="' . $this->_site_names['http'] . '/img/logo.jpg" alt="" style="float:left;">
            <a style="background: #094B88;color:#fff;display: block;border: 0 none;border-bottom: 4px solid #0A447B;box-shadow:inset 0 2px 4px rgba(255,255,255,0.2), 0 3px 5px rgba(0,0,0,0.2);height: 32px;padding: 0 24px;float: right;font-family: \'Source Sans Pro\', Arial;font-size: 13px;font-weight: bold;height: 32px;line-height: 34px;padding: 0 24px;text-decoration: none;" href="' . $action[0]['href'] . '">' . $action[0]['name'] . '</a>
            <h1 style="color: #5A7A99;float: left;font-family: \'Source Sans Pro\', Arial;font-size: 25px;font-weight: 900;letter-spacing: -1px;line-height: 42px;margin: 0;">
                Buna,<BR>' . $action[1] . '
            </h1>
            <div style="font-family: \'Source Sans Pro\', Arial;float: left; margin: 30px 0 0; background: none repeat scroll 0px 0px rgb(237, 237, 244);color: #4A4A4A;padding: 2%; width: 96%; border-bottom: 1px solid rgb(221, 221, 221);">
                '.$this->_content.'
            </div>
        </div>
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
    </div>
</div>
';
    }


	protected function mandrillAction()
	{
		$from = ($this->_from !== null ? array('email'=>$this->_from['email'], 'name'=>$this->_from['name']) : array('email'=>$this->_config['from']['email'], 'name'=>$this->_config['from']['name']));
		try
		{
            require 'vendor/mandrill/mandrill/src/Mandrill.php';
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
				'bcc_address' => $this->bcc,
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
				),*/
				/*'images' => array(
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

		    if (APPLICATION_ENV != 'development' || 1==2) {
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








    public function contact($name, $subject, $message)
    {
        $this->_subject = $subject;
        $this->_message = 'Mesaj de la '.$name.' &lt;'.$this->_from['email'].'&gt;, '.
            General::DateTime(null, 'LONG', true).'<hr>'.
            $message
        ;

        return $this->sendAction();
    }

    public function requestOffer($from, $car, $parts, $folder, $user_id)
    {
        $this->_subject = 'Cerere Oferta Noua';
        $partshtml = '';
        foreach($parts[0] as $k => $partname) {
            $partshtml[] = ($k+1).'. <strong>'.$partname . '</strong>, cod: '.$parts[1][$k].'<br />'.$parts[2][$k];
        }

        $path = PUBLIC_IMG_PATH . $user_id. '/' . implode('/', $folder);
        foreach (glob($path . "/*") as $filefound) {
            $x = explode("/", $filefound);
            $imageFile = $x[count($x)-1];
            if (strpos($imageFile, '_') === false ) {
                $photos[] = '<a href="'.General::getSimpleAvatar($user_id . 'x' . implode('x', $folder), $imageFile, '9999x9999').'">
                    <img style="padding:10px; float:left" src="'.General::getSimpleAvatar($user_id . 'x' . implode('x', $folder), $imageFile, '100x100').'">
                    </a>';
            }
        }

        $this->_message = 'Cerere oferta de la '.$from['name']. ', ' .
            General::DateTime(null, 'LONG', true) . '<br />mail:&lt;'.$this->_from['email'].'&gt;, tel: '.
            $from['phone']. ', judet: '.$from['state']. '<hr>'.
            '<strong>Masina:</strong><br/>' .
            'categorie: '.$car['category'].', marca: '. $car['make'].', model: '.$car['model'].', an: '.$car['year'].
            ', serie sasiu: '.$car['sasiu'].'<hr>'.
            '<strong>Piese:</strong><br/>' .
            implode('<br>', $partshtml).'<hr>'.
            '<strong>Poze:</strong><br/>' .
            '<div style="overflow:hidden">'. implode('', $photos) . '</photos>'
        ;

        return $this->sendAction();
    }

    public function forgotPassword($name, $hash)
    {
        $this->_content  = 'Poti sa iti schimbi parola <a href="' . $this->_site_names['http'] . '/reset-password/' . $hash . '">aici</a>.';

        $this->_subject = $this->translator->translate('Reseatare Parola Tirbox');
        $this->_message =
            $this->template(
                array(
                    array(
                        'href' => $this->_site_names['http'] . '/reset-password/' . $hash . '',
                        'name' => 'Reseteaza Parola'
                    ),
                    $name
                )
            );

	    return $this->sendAction();
    }

    public function inactivateAd($name, $adsInMAil)
    {
        $expiredAds = '';
        foreach ($adsInMAil as $ad) {
            $expiredAds .= '
            <div style="width:300px; overflow: hidden; padding-bottom: 20px">
                <div style="float: left; width: 80px">
                    <img src="' . $ad['photo']. '" style="width:70px" />
                </div>
                <div style="float: left; width: 200px">
                    '.$ad['name'].'
                </div>
            </div>';
        }

        $this->_content = 'Urmatoarele anunturi postate de tine au expirat:<br /><br />
	        <div style="width:200px; overflow: hidden; padding-bottom: 20px">
	            '.$expiredAds.'
            </div>

	        Reactivaza-ti anunturile pentru inca 30 de zile GRATUIT!<br />
	        <a href="'.$this->_site_names['http'] . '/ad/my-ads/incative'.'">Intra in contul tau Tirbox.ro</a>'
        ;


        $this->_subject = $this->translator->translate('Anuntul tau a expirat');
        $this->_message =
            $this->template(
                array(
                    array(
                        'href' => $this->_site_names['http'] . '/ad/my-ads/inactive',
                        'name' => 'Reactiveaza-ti anunturile'
                    ),
                    $name
                )
            );

        return $this->sendAction();
    }
}
