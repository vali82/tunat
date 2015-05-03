<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Application\Forms\Filters\MyAccountFilter;
use Application\Forms\MyAccountForm;
use Application\libs\General;
use Application\Models\Ads\AdCollection;
use Application\Models\Ads\AdDM;
use Application\Models\Autoparks\ParksDM;
use Application\Models\Cars\CarsCollection;
use Zend\Mvc\MvcEvent;
use Zend\View\Model\JsonModel;

class MyAccountController extends MyAbstractController
{
    public function onDispatch(MvcEvent $e)
    {
        $this->layout()->setTemplate('layout-parcauto');
        parent::onDispatch($e);
    }

    public function indexAction()
    {
        return [
            'myPark' => $this->myPark,
        ];
    }

    public function updateAction()
    {
        $request = $this->getRequest();
        $error = false;

        $states = General::getFromSession('states');

        $form = new MyAccountForm();
        $form->setCancelRoute('back');
        $form->changeMyAccount($states);
        $form->bind($this->myPark);

        if ($request->isPost()) {
            $filter = new MyAccountFilter();
            $filter->setDbAdapter($this->adapter);
            $filter->setTranslator($this->translator);

            $form->setInputFilter($filter->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                if (isset($_FILES['imagefile']) && $_FILES['imagefile']['name'] !== '') {
                    $uploadResponse = $this->uploadAdImages(
                        $this->myPark->getId(),
                        $this->myPark->getEmail(),
                        ['logo'],
                        ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'],
                        2 * 1024 * 1024
                    );
                    if ($uploadResponse instanceof JsonModel) {
                        $file_name = $uploadResponse->getVariable('files')[0]['nameDisk'];
                        $file_path = PUBLIC_IMG_PATH . $this->myPark->getId() . '/logo';
                        foreach (glob($file_path . "/*") as $filefound) {
                            if (strpos($filefound, $file_name) === false) {
                                @unlink($filefound);
                            }
                        }
                        $this->myPark->setLogo($file_name);

                    } else {
                        $form->setMessages(array(
                            'imagefile' => array(
                                $this->translator->translate('A aparut o eroare') . ': ' .
                                $uploadResponse->getReasonPhrase()
                            )
                        ));
                        $error = true;
                    }
                }


                if ($this->myPark->getAccountType() == 1) {
                    if ($form->getInputFilter()->getValue('name') === '') {
                        $form->setMessages(array(
                            'name' => array(
                                $this->translator->translate('A aparut o eroare') . ': ' .
                                $this->translator->translate('Numele este obligatoriu')
                            )
                        ));
                        $error = true;
                    }
                } else {
                    if ($form->getInputFilter()->getValue('name2') === '') {
                        $form->setMessages(array(
                            'name2' => array(
                                $this->translator->translate('A aparut o eroare') . ': ' .
                                $this->translator->translate('Numele este obligatoriu')
                            )
                        ));
                        $error = true;
                    }
                }

                if (!$error) {
                    $DM = new ParksDM($this->adapter);
                    if ($this->myPark->getAccountType() == 1) {
                        $this->myPark->setAccountType('parc-auto');
                    } else {
                        $this->myPark
                            ->setName($form->getInputFilter()->getValue('name2'))
                            ->setUrl('')
                            ->setDescription('')
                        ;
                        $this->myPark->setAccountType('particular');
                    }
                    $DM->updateRow($this->myPark);

                    $this->flashMessenger()->addSuccessMessage(
                        $this->translator->translate('Contul tau a fost modificat cu success')
                    );
                    General::unsetSession('myPark');
                    return $this->redirect()->toRoute('home/myAccount');
                }

            } else {
                $form->populateValues(array(
                    'imagefile' => '<img src="' . $this->myPark->generateAvatar('100x100') . '" />'
                ));
            }

        } else {
            $form->populateValues(array(
                'imagefile' => '<img src="' . $this->myPark->generateAvatar('100x100') . '" />',
                'account_type' => $this->myPark->getAccountType() === 'particular' ? 0 : 1
            ));
        }


        return array(
            'form' => $form
        );

    }

    public function myAdsAction()
    {
        $statusParam = $this->getEvent()->getRouteMatch()->getParam('status', 'active');
        switch ($statusParam) {
            case "active":
                $status = 'ok';
                $title = 'Anunturi Active';
                break;
            case "expired":
                $title = 'Anunturi Expirate';
                $status = 'expired';
                break;
            default:
                $status = 'ok';
        }
        $cars = $this->cars;
        $carCollection = new CarsCollection($this);
        $ad = new AdCollection($this);

        $token = md5(time().'asdfqwer'.rand(1000, 9999));
        General::addToSession('token', $token);
        $content = $ad->adListHTML([
            'place' => 'myAds',
            'status' => $status,
            'token' => $token
        ]);

        $adList = $content['list'];
        $ads = $content['ads'];
        return [
            'adList' => $adList,
            'ads' => $ads,
            'carCollection' => $carCollection,
            'statusParam' => $statusParam,
            'title' => $title
        ];
    }

    public function changeStatusAction()
    {
        $token = $this->getEvent()->getRouteMatch()->getParam('token', '');
        $id = $this->getEvent()->getRouteMatch()->getParam('id', '');
        $mode = $this->getEvent()->getRouteMatch()->getParam('mode', '');
        $messageError = '';
        $messageSuccess = '';
        $error = 0;
        $statusRedirect = 'active';

        if ($token == General::getFromSession('token')) {
            $adDM = new AdDM($this->adapter);
            /** @var $adObj \Application\Models\Ads\Ad*/
            $adObj = $adDM->fetchOne([
                'id' => $id,
                'park_id' => $this->myPark->getId()
            ]);
            if ($adObj !== null) {
                if ($mode == 'delete') {
                    $adDM->deleteOne($adObj);
                    $file_path = PUBLIC_IMG_PATH . $this->myPark->getId() . '/ads/' . $id;
                    foreach (glob($file_path . "/*") as $filefound) {
                        @unlink($filefound);
                    }
                    rmdir($file_path);

                    $messageSuccess = 'Anuntul a fost sters cu success!';

                } elseif ($mode == 'activate') {
                    $expDate = General::DateTime(null, 'object');
                    $expDate->add(new \DateInterval('P30D'));
                    $adObj
                        ->setExpirationDate(General::DateTime($expDate))
                        ->setDateadd(General::DateTime())
                        ->setStatus('ok')
                    ;
                    $adDM->updateRow($adObj);
                    $messageSuccess = 'Anuntul a fost activat cu success!';
                    $statusRedirect = 'expired';
                }
            } else {
                $error = 1;
                $messageError = 'Anunt invalid!';
            }


        } else {
            $messageError = 'Token invalid!';
            $error = 1;
        }

        if ($error) {
            $this->flashMessenger()->addErrorMessage($messageError);
        } else {
            $this->flashMessenger()->addSuccessMessage($messageSuccess);
        }

        $this->redirect()->toRoute('home/myAccount/myAds', ['status' => $statusRedirect]);
    }
}
