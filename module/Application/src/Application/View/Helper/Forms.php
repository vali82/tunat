<?php
namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;

class Forms extends AbstractHelper
{

    private static $_label_size;
    private static $_input_size;
    private static $elementClass;

    public static function render($view, $form, $url, $options = array())
    {
        self::$_label_size = isset($options['label_size']) ? $options['label_size'] : 'col-md-2 col-sm-4';
        self::$_input_size = isset($options['input_size']) ? $options['input_size'] : 'col-md-10 col-sm-8';
        self::$elementClass = isset($options['element_class']) ? $options['element_class'] : 'form-control';

        $form->setAttribute('action', $url);
        $form->setAttribute('method', 'post');
        $form->setAttribute('role', 'form');
        $form->setAttribute('class', 'form-horizontal');


        $form->prepare();

        $formButtonsAlign = isset($options['formButtonsAlign']) ? $options['formButtonsAlign'] : '';

        if (!isset($options['onlyForm'])) { ?>

            <div class="portlet box red">
            <div class="portlet-title">
                <div class="caption"><?= (isset($options['titlePanel']) ? $options['titlePanel'] : '&nbsp;') ?></div>
            </div>
            <div class="portlet-body form">
        <?php
        }
        echo $view->form()->openTag($form);

        ?>
        <div class="form-body">
            <?php
            $found_submit_element = false;

            foreach ($form as $element) {

                $group = $element->getAttribute('group');
                $container = $element->getAttribute('container');

                if ($element->getAttribute('name') == 'custom_form_spacer') {
                    self::customSpacerElement($element);

                } else {
                    if ($element->getAttribute('class')) {
                        self::$elementClass .= ' '.$element->getAttribute('class');
                    }
                    if ($element->getAttribute('type') != 'submit') {
                        $element->setAttribute('class', self::$elementClass);

                    } else {
                        if ($element->getAttribute('id') == 'cancelbutton') {
                            $element->setAttribute('class', "btn btn-default");
                        } else {
                            $element->setAttribute('class', "btn btn-success");
                        }
                        $found_submit_element = $element;
                        continue;
                    }


                    if ($element->getAttribute('placeholder') == null) {
                        $element->setAttribute('placeholder', $element->getLabel());
                    }

                    if ($group !== null) {
                        if ($group['type'] == 'start') { ?>
                            <div class="form-group row<?php if ($element->getAttribute('containerClass')) echo " " . $element->getAttribute('containerClass') ?>"><?php
                        }
                    } else {

                        if ($container !== null) {
                            if ($container['type'] == 'start' || $container['type'] == 'enclose') {
                                ?>
                                <div class="<?php echo $container['class'] ?>">
                            <?php
                            }
                        } ?>

                        <div class="form-group <?php if ($view->formElementErrors($element)) echo "has-error" ?><?php if ($element->getAttribute('containerClass')) echo " " . $element->getAttribute('containerClass') ?>"><?php
                    } ?>

                    <?php if ($element->getAttribute('type') != 'hidden' && !$element->getAttribute('noLabel')) { ?>
                        <label class="<?= ($group != null ?
                                (
                                    (isset($group['sizeLabel']) ? $group['sizeLabel'] : self::$_label_size) .
                                    ($view->formElementErrors($element) ? ' has-error' : '')
                                ) :
                                self::$_label_size).' control-label'
                            ?>"><?= $view->translate($element->getLabel()); ?></label>
                    <?php } ?>

                    <?php //if (!isset($options['label_above_input'])) { ?>
                    <div
                        class="<?= ($group != null ? ($group['size'] . ($view->formElementErrors($element) ? ' has-error' : '')) : self::$_input_size) ?>">

                        <?php if ($element->getName() == 'no_element') { ?>
                            &nbsp;
                        <?php } else { ?>

                            <?php if ($element->getAttribute('type') == 'file') { ?>
                                <?= (isset($element->getValue()['name']) ? $element->getValue()['name'] : $element->getValue()) ?>
                            <?php }

                            if ($element->getAttribute('datePicker')) {
                                $setTime = $element->getAttribute('setTime') ? true : false;
                                echo self::showDatePicker($view, $element, $setTime);

                            } elseif ($element->getAttribute('buttonRight')) {
                                echo self::showButtonRight($view, $element);

                            } elseif ($element->getAttribute('switcher')) {
                                echo self::showSwitcher($view, $element, $options);

                            } elseif ($element->getAttribute('justText')) {
                                echo $view->translate($element->getLabel());

                            } else {
                                echo $view->formElement($element);
                            }
                            ?>

                            <?php if ($element->getAttribute('extraInfo')) { ?>
                                <span
                                    class="help-block"><?= $view->translate($element->getAttribute('extraInfo')) ?></span>
                            <?php } ?>

                            <?php if ($view->formElementErrors($element)) { ?>
                                <span class="help-block"><?php echo $view->formElementErrors($element) ?></span>
                            <?php } ?>

                        <?php } ?>

                    </div>

                    <?php
                if ($container !== null) {
                if ($container['type'] == 'end' || $container['type'] == 'enclose') { ?>
                    </div><?php
                }
                }
                    if ($group !== null) {
                        if ($group['type'] == 'end') { ?>
                            </div><?php
                        }
                    } else { ?>
                        </div><?php
                    }
                }
            }

            ?>
        </div>
        <?php
        if ($found_submit_element) { ?>
            <div class="form-actions fluid">
                <div
                    class="col-md-offset-<?= str_replace('col-sm-', '', self::$_label_size) ?> <?= self::$_input_size ?>">
                    <div class="<?= $formButtonsAlign ?>">
                        <?php echo $view->formElement($found_submit_element); ?>
                        <?php if ($found_submit_element->getAttribute('cancelLink')) { ?>
                            <input type="button" value="<?= $view->translate('Anuleaza') ?>" class="btn btn-default"
                                   onclick="<?php
                                   if ($found_submit_element->getAttribute('cancelLink') == 'back') {
                                       echo 'window.history.back()';
                                   } elseif (strpos($found_submit_element->getAttribute('cancelLink'), 'js::') === 0) {
                                       echo str_replace('js::', '', $found_submit_element->getAttribute('cancelLink'));
                                   } else {
                                       echo 'self.location.href=\'' . $view->url($found_submit_element->getAttribute('cancelLink')) . '\' ';
                                   }
                                   ?>">
                        <?php } ?>
                    </div>
                </div>
            </div>
        <?php }

        echo $view->form()->closeTag($form);

        if (!isset($options['onlyForm'])) { ?>
            </div>
            </div>
        <?php
        }
    }

    protected static function showSwitcher($view, $element, $options)
    {
        $view->headLink()
            ->appendStylesheet('/assets/plugins/bootstrap-switch/css/bootstrap-switch.css');
        $view->headScript()
            ->appendFile('/assets/plugins/bootstrap-switch/js/bootstrap-switch.js', 'text/javascript');

        ?>

        <?php
        $element->setAttribute('data-text-label', $element->getAttribute('label'));
        $element->setAttribute('class', 'make-switch');

        return $view->formElement($element);
    }

    protected static function customSpacerElement($element)
    {
        if (!$element->getAttribute('no_form_group')) { ?>
            <div class="form-group" style="padding:0 15px">
        <?php }?>
        <?php if ($element->getAttribute('pureHtml')) { ?>
        <?= $element->getAttribute('pureHtml') ?>
    <?php } else { ?>
        <?php if ($element->getAttribute('textAbove')) { ?>
            <p class="help-block"
               style="text-align:<?= ($element->getAttribute('aalign') ? $element->getAttribute('aalign') : 'left') ?>; color: #666666;font-size: <?= ($element->getAttribute('afsize') ? $element->getAttribute('afsize') : 15) ?>px;font-weight: 500;padding: 15px 0 0 0;"><?= $element->getAttribute('textAbove') ?></p>
        <?php } ?>
        <hr style="margin:10px 0">
        <?php if ($element->getAttribute('textBellow')) { ?>
            <p class="help-block"
               style="text-align:<?= ($element->getAttribute('balign') ? $element->getAttribute('balign') : 'left') ?>; color: #666666;font-size: <?= ($element->getAttribute('bfsize') ? $element->getAttribute('bfsize') : 15) ?>px;font-weight: 500;padding: 0 0 15px 0;"><?= $element->getAttribute('textBellow') ?></p>
        <?php } ?>

    <?php }
        if (!$element->getAttribute('no_form_group')) { ?></div><?php }

    }

    static function showDatePicker($view, $element, $setTime = false)
    {
        $element_id = $element->getAttribute('id');
        $element->setAttribute('readonly', 'readonly');

        if (!$setTime) {
            $view->layout()->js_call .= '
		        $("#dp3-' . $element->getAttribute('id') . '").datepicker({ language: "ro", isRTL: App.isRTL(),
					format: "dd-mm-yyyy",
					autoclose: true,
					todayBtn: true,
					pickerPosition: (App.isRTL() ? "bottom-right" : "bottom-left")
					});
				';
            $view->headLink()
                ->appendStylesheet('/css/bootstrap-datepicker/datepicker3.css');
            $view->headScript()
                ->appendFile('/js/underscore/underscore-min.js', 'text/javascript')
                ->appendFile('/js/bootstrap-calendar/calendar.js', 'text/javascript')
                ->appendFile('/js/bootstrap-datepicker/bootstrap-datepicker.js', 'text/javascript');
        } else {
            $view->headLink()
                ->appendStylesheet('/assets/plugins/bootstrap-datetimepicker/css/datetimepicker.css');
            $view->headScript()
                ->appendFile('/assets/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js', 'text/javascript')
                ->appendFile('/assets/plugins/bootstrap-datetimepicker/js/locales/bootstrap-datetimepicker.ro.js', 'text/javascript');

            $view->layout()->js_call .= '
		        $("#dp3-' . $element->getAttribute('id') . '").datetimepicker({ language: "ro", isRTL: App.isRTL(),
                    format: "dd-mm-yyyy hh:ii",
                    autoclose: true,
                    todayBtn: true,
                    startDate: "' . date('Y-m-d H:i:s') . '0",
                    pickerPosition: (App.isRTL() ? "bottom-right" : "bottom-left"),
                    minuteStep: 10});
                ';
        }


        if (!$setTime) {
            $template_html = '<div id="dp3-' . $element_id . '" class="input-group date" data-date-format="dd-mm-yyyy" data-date="' . date("d-m-Y") . '">';
            $template_html .= $view->formElement($element);
            $template_html .= '<span class="input-group-btn">';
            $template_html .= '<button type="button" class="btn default date-set"><i class="fa fa-calendar"></i></button>';
            $template_html .= '</span>';
            $template_html .= '</div>';
            return $template_html;

        } else {
            $template_html = '<div id="dp3-' . $element_id . '"  class="input-group date form_datetime">';
            $template_html .= $view->formElement($element);
            $template_html .= '<span class="input-group-btn">';
            $template_html .= '<button type="button" class="btn default date-reset"><i class="fa fa-times"></i></button>';
            $template_html .= '</span>';
            $template_html .= '<span class="input-group-btn">';
            $template_html .= '<button type="button" class="btn default date-set"><i class="fa fa-calendar"></i></button>';
            $template_html .= '</span>';
            $template_html .= '</div>';
            return $template_html;
        }
    }

    static function showDatePickerFrontend($view, $element, $setTime = false)
    {
        $element_id = $element->getAttribute('id');
        $element->setAttribute('readonly', 'readonly');

        if (!$setTime) {
            $template_html = '<div id="dp3-' . $element_id . '" class="input-group date" data-date-format="dd-mm-yyyy" data-date="' . date("d-m-Y") . '">';
            $template_html .= $view->formElement($element);
            $template_html .= '<span class="input-group-btn">';
            $template_html .= '<button type="button" class="btn default date-set"><i class="fa fa-calendar"></i></button>';
            $template_html .= '</span>';
            $template_html .= '</div>';
            return $template_html;

        } else {
            $template_html = '<div id="dp3-' . $element_id . '"  class="input-group date form_datetime">';
            $template_html .= $view->formElement($element);
            $template_html .= '<span class="input-group-btn">';
            $template_html .= '<button type="button" class="btn default date-reset"><i class="fa fa-times"></i></button>';
            $template_html .= '</span>';
            $template_html .= '<span class="input-group-btn">';
            $template_html .= '<button type="button" class="btn default date-set"><i class="fa fa-calendar"></i></button>';
            $template_html .= '</span>';
            $template_html .= '</div>';
            return $template_html;
        }
    }

    static function showButtonRight($view, $element)
    {
        //$element->setValue(date('d-m-Y'));
        $button = $element->getAttribute('button');

        $template_html = '
		<div class="input-group">
		  ' . $view->formElement($element) . '
		  <span class="input-group-btn">
		    <button id="' . (isset($button['id']) ? $button['id'] : '') . '" class="' . $button['class'] . '" onclick="' . (isset($button['onclick']) ? $button['onclick'] : '') . '" type="button" >' . $button['name'] . '</button>
		  </span>
		</div>
		';
        //$template_html .= $view->formElement($element);
        //$template_html .= '<span class="add-on"><i class="icon-calendar"></i></span></div>';
        return $template_html;

    }

}
