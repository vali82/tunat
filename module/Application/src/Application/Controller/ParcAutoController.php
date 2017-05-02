<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Application\Forms\ContactForm;
use Application\Forms\Filters\ContactFilter;
use Application\libs\General;
use Application\Mail\MailGeneral;
use Application\Models\Ads\Ad;
use Application\Models\Ads\AdCollection;
use Application\Models\Ads\AdDM;
use Application\Models\Advertiser\AdvertiserDM;
use Application\Models\Cars\CarsCollection;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use ZfcBaseTest\Mapper\AbstractDbMapperTest;

class ParcAutoController extends MyAbstractController
{
    public function indexAction()
    {
        $ida = $this->getEvent()->getRouteMatch()->getParam('id', '');

        $advertiserDM = new AdvertiserDM($this->adapter);
        $advertiserObj = $advertiserDM->fetchOne($ida);

//        General::echop($advertiserObj);

        $adCollection = new AdCollection($this);
        $adList = null;
        $ads = null;
        $content = $adCollection->adListHTML([
            'place' => 'onSearch',
            'carModelId' => 0,
            'partMainId' => 0,
            'search' => General::generateQueryWords(''),
            'searchAdvertiser' => $ida,
            'searchYear' => '',
            'searchCounty' => '',
            'searchStare' => '',
            'searchOem' => ''
        ]);

        $adList = $content['list'];
        $ads = $content['ads'];


        return [
            'advertiser' => $advertiserObj,
            'ads' => $ads,
            'adList' => $adList,
            'carCollection' => new CarsCollection($this)
        ];
    }
}
