<?php
namespace Application\View\Helper;

use Application\libs\General;
use Zend\View\Helper\AbstractHelper;

class Forms extends AbstractHelper
{

    private static $label_size;
    private static $input_size;
    private static $elementClass;

    public static function render($view, $form, $url, $options = array())
    {
        self::$label_size = isset($options['label_size']) ? $options['label_size'] : 'col-md-2 col-sm-4';
        self::$input_size = isset($options['input_size']) ? $options['input_size'] : 'col-md-10 col-sm-8';
        self::$elementClass = isset($options['element_class']) ? $options['element_class'] : 'form-control';

        $form->setAttribute('action', $url);
        $form->setAttribute('method', 'post');
        $form->setAttribute('role', 'form');
        //$form->setAttribute('class', 'form-horizontal');


        $form->prepare();

        $formButtonsAlign = isset($options['formButtonsAlign']) ? $options['formButtonsAlign'] : '';

        echo $view->form()->openTag($form);

        $found_submit_element = false;

        foreach ($form as $element) {
            $elementClass = self::$elementClass;
            $group = $element->getAttribute('group');
            $container = $element->getAttribute('container');

            if ($element->getAttribute('custom_form_spacer') == true) {
                self::customSpacerElement($element);
//                General::echop($element->getAttribute('name'));

            } else {
                if ($element->getAttribute('class')) {
                    $elementClass .= ' ' . $element->getAttribute('class');
                }
                if ($element->getAttribute('type') != 'submit') {
                    $element->setAttribute('class', $elementClass);

                } else {
                    if ($element->getAttribute('id') == 'cancelbutton') {
                        $element->setAttribute('class', "button btn-blue");
                    } else {
                        $element->setAttribute('class', "button btn-green");
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



                <?php if ($group != null) { ?>
                <div
                    class="<?= ($group != null ? ($group['size'] . ($view->formElementErrors($element) ? ' has-error' : '')) : self::$input_size) ?>">
                <?php } ?>

                <?php if ($element->getAttribute('type') != 'hidden' && !$element->getAttribute('noLabel')) { ?>
                    <label class="<?= 'control-label'?>">
                        <?= $view->translate($element->getLabel()); ?>
                    </label>
                <?php } ?>

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

                        } elseif ($element->getAttribute('name') == 'photosMultiUpload') {
                            self::photosMultiUpload($view, $element);

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

                <?php if ($group != null) { ?>
                </div>
                <?php } ?>

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

        if ($found_submit_element) { ?>
            <div class="">
                <hr style="margin:10px 0">
                <div class="<?= $formButtonsAlign ?>">
                    <?php echo $view->formElement($found_submit_element); ?>
                    <?php if ($found_submit_element->getAttribute('cancelLink')) { ?>
                        <input type="button" value="<?= $view->translate('Anuleaza') ?>" class="button btn-blue"
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
        <?php }

        echo $view->form()->closeTag($form);
    }

    private static function photosMultiUpload($view, $element)
    {
        $view->headLink()
            ->prependStylesheet($view->basePath() .'/assets/jquery-file-upload/blueimp-gallery/blueimp-gallery.min.css')
            ->prependStylesheet($view->basePath() .'/assets/jquery-file-upload/css/jquery.fileupload.css')
            ->appendStylesheet($view->basePath() .'/assets/jquery-file-upload/css/jquery.fileupload-ui.css');

        $view->headScript()
            //->appendFile('/js/media.js', 'text/javascript')
            ->appendFile($view->basePath() .'/assets/jquery-file-upload/js/vendor/jquery.ui.widget.js', 'text/javascript')
            ->appendFile($view->basePath() .'/assets/jquery-file-upload/js/vendor/tmpl.min.js', 'text/javascript')
            ->appendFile($view->basePath() .'/assets/jquery-file-upload/js/vendor/load-image.min.js', 'text/javascript')
            ->appendFile($view->basePath() .'/assets/jquery-file-upload/js/vendor/canvas-to-blob.min.js', 'text/javascript')
            ->appendFile($view->basePath() .'/assets/jquery-file-upload/blueimp-gallery/jquery.blueimp-gallery.min.js', 'text/javascript')
            ->appendFile($view->basePath() .'/assets/jquery-file-upload/js/jquery.iframe-transport.js', 'text/javascript')
            ->appendFile($view->basePath() .'/assets/jquery-file-upload/js/jquery.fileupload.js', 'text/javascript')
            ->appendFile($view->basePath() .'/assets/jquery-file-upload/js/jquery.fileupload-process.js', 'text/javascript')
            ->appendFile($view->basePath() .'/assets/jquery-file-upload/js/jquery.fileupload-image.js', 'text/javascript')
//    ->appendFile('/assets/jquery-file-upload/js/jquery.fileupload-audio.js', 'text/javascript')
//    ->appendFile('/assets/jquery-file-upload/js/jquery.fileupload-video.js', 'text/javascript')
            ->appendFile($view->basePath() .'/assets/jquery-file-upload/js/jquery.fileupload-validate.js', 'text/javascript')
            ->appendFile($view->basePath() .'/assets/jquery-file-upload/js/jquery.fileupload-ui.js', 'text/javascript')
            ->appendFile($view->basePath() .'/assets/jquery-file-upload/js/cors/jquery.xdr-transport.js', 'text/javascript')
        ;

        ?>
            <!-- The fileupload-buttonbar contains buttons to add/delete files and start/cancel the upload -->
            <div class="row fileupload-buttonbar">
                <div class="col-lg-7">
                    <!-- The fileinput-button span is used to style the file input field as button -->
                    <span class="button btn-green fileinput-button">
                        <i class="glyphicon glyphicon-plus"></i>
                        <span>adauga poze...</span>
                        <input type="file" multiple="" name="files[]">
                    </span>
                    <!--<button class="btn btn-primary start" type="submit">
                        <i class="glyphicon glyphicon-upload"></i>
                        <span>Start upload</span>
                    </button>-->
                    <!-- The global file processing state -->
                    <span class="fileupload-process"></span>
                </div>
                <!-- The global progress information -->
                <div class="col-lg-5 fileupload-progress fade">
                    <!-- The global progress bar -->
                    <div class="progress progress-striped active" role="progressbar" aria-valuemin="0"
                         aria-valuemax="100">
                        <div class="progress-bar progress-bar-success" style="width:0%;">
                        </div>
                    </div>
                    <!-- The extended global progress information -->
                    <div class="progress-extended">
                        &nbsp;
                    </div>
                </div>
                <div class="col-md-12">
                    <p class="photo-uploader-note">
                        Nr maxim de poze adminse: <?=$element->getAttribute('maxNumberOfFiles')?>. Pozele trebuie sa aiba maximum <strong><?=$element->getAttribute('maxFileSize')?></strong>
                        . Sunt admise doar fisiere (<strong><?=$element->getAttribute('acceptedFileType')?></strong>).
                    </p>
                </div>
            </div>
            <!-- The table listing the files available for upload/download -->
            <table role="presentation" class="table table-striped clearfix">
                <tbody class="files">
                </tbody>
            </table>
        <!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->
        <script id="template-upload" type="text/x-tmpl">
    {% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class="template-upload fade">
        <td>
            <span class="preview"></span>
        </td>
        <td>
            <p class="name">{%=file.name%}</p>
            <strong class="error text-danger"></strong>
            {% if (file.error) { %}
            <div><span class="label label-danger">Error</span> {%=file.error%}</div>
            {% } %}
        </td>
        <td>
            <p class="size">{%=o.formatFileSize(file.size)%}</p>
            {% if (!o.files.error) { %}
            <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100"
                 aria-valuenow="0">
                <div class="progress-bar progress-bar-success" style="width:0%;"></div>
            </div>
            {% } %}
        </td>
        <td>
            {% if (!o.files.error && !i && !o.options.autoUpload) { %}
            <button class="button btn-blue start">
                    <i class="glyphicon glyphicon-upload"></i>
                    <span>Start</span>
                </button>
            {% } %}
            {% if (!i) { %}
            <button class="button btn-red cancel">
                    <i class="glyphicon glyphicon-ban-circle"></i>
                    <span>Cancel</span>
                </button>
            {% } %}
        </td>
    </tr>
    {% } %}
</script>
        <!-- The template to display files available for download -->
        <script id="template-download" type="text/x-tmpl">
    {% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class="template-download fade">
        <td>
                    <span class="preview">
                        {% if (file.thumbnailUrl) { %}
                            <a href="{%=file.url%}" type="{%=file.type%}" title="{%=file.name%}"
                               download="{%=file.name%}" data-gallery><img src="{%=file.thumbnailUrl%}"></a>
                        {% } %}
                    </span>
        </td>
        <td>
            <p class="name">
                {% if (file.url) { %}
                <a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}"
                {%=file.thumbnailUrl?'data-gallery':''%}>{%=file.name%}</a>
                {% } else { %}
                <span>{%=file.name%}</span>
                {% } %}
            </p>
            {% if (file.error) { %}
            <div><span class="label label-danger">Error</span> {%=file.error%}</div>
            {% } %}
        </td>
        <td>
            <span class="size">{%=o.formatFileSize(file.size)%}</span>
        </td>
        <td>
            {% if (file.deleteUrl) { %}
            <button class="button btn-red delete" onclick="$(this).button('loading')" data-loading-text="Loading..." data-type="{%=file.deleteType%}" data-url="{%=file.deleteUrl%}"
            {% if (file.deleteWithCredentials) { %} data-xhr-fields='{"withCredentials":true}'{% } %}>
            <i class="fa fa-trash-o"></i>
            <span>Sterge</span>
            </button>
            {% } else { %}
            <button class="button btn-red cancel">
                <i class="fa fa-ban"></i>
                <span>Anuleaza</span>
            </button>
            {% } %}
        </td>
    </tr>
    {% } %}
</script>
        <!-- The blueimp Gallery widget -->
        <div id="blueimp-gallery" class="blueimp-gallery blueimp-gallery-controls" data-filter="">
            <div class="slides">
            </div>
            <h3 class="title"></h3>
            <a class="prev">
                ‹
            </a>
            <a class="next">
                ›
            </a>
            <a class="close white">
            </a>
            <a class="play-pause">
            </a>
            <ol class="indicator">
            </ol>
        </div>
        <?php
    }

    protected static function showSwitcher($view, $element, $options)
    {
        $view->headLink()
            ->appendStylesheet('/assets/bootstrap-switch/css/bootstrap-switch.css');
        $view->headScript()
            ->appendFile('/assets/bootstrap-switch/js/bootstrap-switch.js', 'text/javascript');

        ?>

        <?php
        $element->setAttribute('data-text-label', $element->getAttribute('label'));
        $element->setAttribute('class', 'make-switch');

        return $view->formElement($element);
    }

    protected static function customSpacerElement($element)
    {
        ?>
        <div class="form-group<?php if ($element->getAttribute('containerClass')) echo " " . $element->getAttribute('containerClass') ?>">
        <?php if ($element->getAttribute('pureHtml')) { ?>
        <?= $element->getAttribute('pureHtml') ?>
        <?php } else { ?>
            <?php if ($element->getAttribute('textAbove')) { ?>
                <p class="help-block"
                   style="text-align:<?= ($element->getAttribute('aalign') ? $element->getAttribute('aalign') : 'left') ?>; color: #666666;font-size: <?= ($element->getAttribute('afsize') ? $element->getAttribute('afsize') : 15) ?>px;font-weight: 500;padding: 0;">
                    <?= $element->getAttribute('textAbove') ?>
                </p>
            <?php } ?>
            <hr style="margin:10px 0">
            <?php if ($element->getAttribute('textBellow')) { ?>
                <p class="help-block"
                   style="text-align:<?= ($element->getAttribute('balign') ? $element->getAttribute('balign') : 'left') ?>; color: #666666;font-size: <?= ($element->getAttribute('bfsize') ? $element->getAttribute('bfsize') : 15) ?>px;font-weight: 500;padding: 0 0 15px 0;">
                    <?= $element->getAttribute('textBellow') ?>
                </p>
            <?php } ?>
        <?php } ?>
        </div>
        <?php
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
