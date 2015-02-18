<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Application\Forms\AdForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\MvcEvent;
use Zend\View\Model\ViewModel;
use ZfcBaseTest\Mapper\AbstractDbMapperTest;

class AdController extends MyAbstractController
{
    public function indexAction()
    {
        return new ViewModel();
    }

    public function createAction()
    {
        $form = new AdForm();
//        $form->setEavEntity('educational_resources', $this->eav, $resourceObj);  // EAV
        $form->setCancelRoute('back');
        $form->create();

        $request = $this->getRequest();

//        $form->bind($resourceObj);

        if ($request->isPost()) {

        }

        return [
            'form' => $form
        ];
    }

}
