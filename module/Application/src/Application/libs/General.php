<?php

namespace Application\libs;

use Zend\Di\ServiceLocator;
use Zend\Mvc\MvcEvent;
use Zend\Navigation\Page\Mvc;
use Zend\Session\Container;
use IntlDateFormatter;
use DateTime;
use \Locale;

class General
{
    public static function echop($x)
    {
        echo '<div class="container bs-docs-container"><div class="row"><div class="alert alert-danger alert-dismissable">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
        echo '<h2>ECHOP</h2><pre>';
        var_dump($x);
        echo "</pre>";
        echo '</div></div></div>';
    }

    /**
     * Get Profile Image for 'kg_admin','educator','child','parent'
     * @param string 'kg_admin','educator','child','parent' $entity_type
     * @param int $entity_id
     * @param int $size
     * @param string $style
     * @param array $options
     * @param string $return
     *
     * @return string
     * */
    public static function getSimpleAvatar($entity_type, $entity_id, $size, $includeHTTP = false)
    {
        $entity_id = ($entity_id !== '' && $entity_id !== null ? $entity_id : 0);

        $sizex = explode('x', $size);
        if ($sizex[0] == '0') {
            $size = '9999x' . $sizex[1];
            $sizestyle = 'height:' . $sizex[1] . 'px';

        } elseif ($sizex[1] == '0') {
            $size = $sizex[0] . 'x9999';
            $sizestyle = 'width:' . $sizex[0] . 'px';
        } else {
            $sizestyle = 'width:' . $sizex[0] . 'px; height:' . $sizex[1] . 'px';
        }

        $src = (MAIN_DOMAIN) . 'display-image/' . $entity_type . '/' . $entity_id . '/' . $size;

        return $src;
    }

    public static function getConfigs($controller, $param = null)
    {
        $config = $controller->getServiceLocator();
        $conf = $config->get('config');
        if ($param !== null) {
            if (strpos($param, '|') === false) {
                return (isset($conf[$param]) ? $conf[$param] : null);
            } else {
                $x = explode('|', $param);
                if (isset($conf[$x[0]]) && isset($conf[$x[0]][$x[1]])) {
                    return $conf[$x[0]][$x[1]];
                } else {
                    return null;
                }
            }
        } else {
            return $conf;
        }
    }


    public static function AmountCool($amount, $currency)
    {
        $x = array('EUR' => '€', 'USD' => '$', 'GBP' => '£');

        if (isset($x[$currency])) {
            if ($amount < 0) {
                $prefix = '-' . $x[$currency];
            } else {
                $prefix = $x[$currency];
            }
            return $prefix . number_format(round(abs($amount), 2), 2);
        } else {
            return $amount . ' ' . $currency;
        }

    }


    public static function confirmBoxJS($view, $url, $text = null)
    {
        $text = $view->translate('Esti sigur?') . ($text !== null ? '<br />' . $view->translate($text) : '');
        $text = str_replace(array('"', "'", "\r\n", "\n"), array('', '', '', ''), $text);
        return 'confirm(\'' . $text . '\',\'url:' . $url . '\');';
    }

    public static function addToSession($param, $value)
    {
        //var_dump('add to SESSION: '.$param);
        $_session = new Container($param);
        $_session->$param = $value;
    }

    public static function getFromSession($param)
    {
        //var_dump('from session: '.$param.': ');
        $_session = new Container($param);
        if (isset($_session->$param)) {
            //var_dump('found OK');
            return $_session->$param;
        } else {
            //var_dump('NULL');
            return null;
        }
    }

    /**
     * @param string $param
     * @param $controller null|\Application\Controller\MMAbstractController
     */
    public static function unsetSession($param, $controller = null)
    {
        $_session = new Container($param);
        $_session->getManager()->getStorage()->clear($param);
    }

    /**
     * @param null|string $datetime
     * @param string $format
     * @param null|bool $time
     *
     * @return \Datetime|string
     */
    public static function DateTime($datetime = null, $format = 'iso', $time = null)
    {
        // php intl extension is ON otherwise use commented rows
        Locale::getDefault();
        $motnhs = array("Ianuarie", "Februarie", "Martie", "Aprilie", "Mai", "Iunie", "Iulie", "August",
            "Septembrie", "Octombrie", "Noiembrie", "Decembrie");
        $d = ($datetime instanceof \DateTime && $datetime !== null) ? $datetime : new DateTime($datetime);

        switch ($format) {
            // 12:34 sau 23 Martie 12:34
            case "MessagesLike":
                $now = new DateTime();

                if ($now->format('Ymd') == $d->format('Ymd')) {
                    return $d->format('H:i');
                } else {
                    //return $d->format('j') .' '.$motnhs[(int)$d->format('m') - 1];
                    $fmt = datefmt_create(
                        Locale::getDefault(),
                        IntlDateFormatter::LONG,
                        ($time === null ? IntlDateFormatter::NONE : IntlDateFormatter::SHORT)
                    );
                    $x = datefmt_format($fmt, $d);
                    return trim(str_replace($d->format('Y'), '', $x));
                }
                break;
            // Ianuarie 2015
            case "MonthNameYear":
                return $motnhs[(int)$d->format('m') - 1] . ' ' . $d->format('Y');
                break;
            // Ianuarie
            case "MonthName":
                return $motnhs[(int)$d->format('m') - 1];
                break;
            // Iun
            case "MonthShort":
                return substr($motnhs[(int)$d->format('m') - 1], 0, 3);
                break;
            // 23 Iun
            case "MonthShortDay":
                return $d->format('d') . ' ' . substr($motnhs[(int)$d->format('m') - 1], 0, 3);
                break;
            // Iun'15
            case "MonthShortYear":
                return substr($motnhs[(int)$d->format('m') - 1], 0, 3) . "'" . substr($d->format('Y'), 2, 2);
                break;
            // 12.03.2015
            case "MEDIUM":
                //return $d->format('d.m.Y') .', '. ($time === null ? '' : $d->format('H:i'));
                $fmt = datefmt_create(
                    Locale::getDefault(),
                    IntlDateFormatter::MEDIUM,
                    ($time === null ? IntlDateFormatter::NONE : IntlDateFormatter::SHORT)
                );
                return datefmt_format($fmt, $d);
                break;
            // 12 Martie 2015
            case "LONG":
//                return $d->format('d.m.Y') .', '. ($time === null ? '' : $d->format('H:i'));
                $fmt = datefmt_create(
                    Locale::getDefault(),
                    IntlDateFormatter::LONG,
                    ($time === null ? IntlDateFormatter::NONE : IntlDateFormatter::SHORT)
                );
                return datefmt_format($fmt, $d);
                break;
            // 12.02.15
            case "SHORT":
//                return $d->format('d.m.y'). ', ' . ($time === null ? '' : $d->format('H:i'));
                $ftm = new IntlDateFormatter(
                    Locale::getDefault(),
                    IntlDateFormatter::SHORT,
                    ($time === null ? IntlDateFormatter::NONE : IntlDateFormatter::SHORT)
                );
                return $ftm->format($d);
                break;
            // 2015-02-23 12:02:20
            case "iso":
                return $d->format('Y-m-d H:i:s');
                break;
            // datetime object
            case "object":
                return $d;
                break;
            // int timestamp
            case "timestamp":
                return $d->getTimestamp();
                break;
            // Luni
            case "dayOfWeekFull":
                $days = [1=>"Luni", 2=> "Marti", 3=> "Mircuri", 4=> "Joi", 5=> "Vineri", 6=> "Sambata", 7=> "Duminica"];
                return $days[$d->format('N')];
                break;
            // 0-6 : acum incepe cu 1.... deci fac o scadere
            case "dayOfWeek":
                return $d->format('N') - 1;
                break;
            // 14:20
            case "time":
                return $d->format('H:i');
                break;
            // formatul din parametru ... a nu se prea folosi
            default:
                return $d->format($format);
                break;
        }
    }

    /**
     * @param string $words
     * @param string $searchWath
     *
     * @return array
     */
    public static function generateQueryWords($words, $searchWath = 'frontSearch')
    {
        $x = explode('+', strtolower(htmlentities($words)));
        $stoped_words = [];
        if ($searchWath == 'frontSearch') {
            $stoped_words = ['piesa', 'de', 'pe', 'ce', 'sa', 'la', 'din', 'in', 'piese'];
        }

        $return = array();
        foreach ($x as $word) {
            if ($word != '' && !in_array($word, $stoped_words) && strlen($word) > 1) {
                $return[] = $word;
            }
        }
        return $return;

    }


    public static function pageTitles($controller, $title, $subtitle = null)
    {
        if ($title !== null) {
            $controller->layout()->cnt_title = $controller->translator->translate($title);
        }
        if ($subtitle !== null) {
            $controller->layout()->cnt_subtitle = $controller->translator->translate($subtitle);
        }
    }
}
