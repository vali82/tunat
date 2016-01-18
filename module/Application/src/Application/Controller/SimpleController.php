<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use ZfcBaseTest\Mapper\AbstractDbMapperTest;

class SimpleController extends AbstractActionController
{

    public function displayImageAction()
    {

        $response = $this->getResponse();
        $response->setStatusCode(200);

        $id = $this->getEvent()->getRouteMatch()->getParam('id');
        $folder = $this->getEvent()->getRouteMatch()->getParam('folder', 'xxx');
//        $sectype=$this->getEvent()->getRouteMatch()->getParam('sectype','xxx');
        $size = $this->getEvent()->getRouteMatch()->getParam('size', '100x100');
        $name = $this->getEvent()->getRouteMatch()->getParam('name', '');

        /*if ($type == 'avatars') {
            $noPhoto = $sectype . '/nouser';
        } else {
            $noPhoto = 'nophoto';
        }*/
        $noPhoto = 'nophoto.jpg';

//        die('asdad');

        $typex = str_replace("x", '/', $folder);
        $sizex = explode('x', $size);

        $image_to_display = PUBLIC_IMG_PATH . $typex . '/' . $id . '_' . $size;

        if (!file_exists($image_to_display)) {
            $oldname = PUBLIC_IMG_PATH . $typex . '/' . $id;

            if (file_exists($oldname)) {
                if ($sizex[0] != 9999 && $sizex[1] != 9999) {
                    // We encourage to use Dependency Injection instead of Service Locator
                    $thumbnailer = $this->getServiceLocator()->get('WebinoImageThumb');
                    $imagePath = $oldname;
                    $thumb = $thumbnailer->create($imagePath, $options = array(), $plugins = array());

                    $thumb->adaptiveResize($sizex[0], $sizex[1]);
                    $thumb->cropFromCenter($sizex[0], $sizex[1]);

                    $thumb->save($image_to_display);
                } else {
                    $image_to_display = $oldname;
                }

            } else {
                // display no image
                $image_to_display = PUBLIC_IMG_PATH . $noPhoto . '_' . $size;
                if (!file_exists($image_to_display)) {
                    $oldname = PUBLIC_IMG_PATH . $noPhoto;

                    if ($sizex[0] != 9999 && $sizex[1] != 9999) {
                        $thumbnailer = $this->getServiceLocator()->get('WebinoImageThumb');
                        $imagePath = $oldname;
                        $thumb = $thumbnailer->create($imagePath, $options = array(), $plugins = array());

                        $thumb->adaptiveResize($sizex[0], $sizex[1]);
                        $thumb->cropFromCenter($sizex[0], $sizex[1]);

                        $thumb->save($image_to_display);
                    } else {
                        $image_to_display = $oldname;
                    }

                }
            }

        } else {
            //$response->setStatusCode(304);

        }

        $prop = getimagesize($image_to_display);


        header("Cache-Control: private, max-age=10800, pre-check=10800");
        header("Pragma: private");
        // Set to expire in 2 days
        header("Expires: " . date(DATE_RFC822, strtotime(" 2 day")));
        if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE'])) {
            // if the browser has a cached version of this image, send 304
            header('Last-Modified: ' . $_SERVER['HTTP_IF_MODIFIED_SINCE'], true, 304);
            exit;
        }


        header('Content-type:' . $prop['mime']);
        echo implode('', file($image_to_display));
        die();
        return $response;
    }
}
