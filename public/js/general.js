
$.general = function() {

    this.cars = false;

    this.onLoad = function () {

        var thisObj = this;

        $.each($('a[data-toggle="tooltip"]'), function(i,obj) {
            $(obj).tooltip();
        });
        $.each($('a[data-toggle="popover"]'), function(i,obj) {
            $(obj).popover();
        });

        /*alert = function (p){
            bootbox.alert(p);
        };*/

        confirm = function (message, funct_or_url)
        {
            bootbox.confirm(message, function(result) {
                if (result) {

                    if (funct_or_url.indexOf('url:') > -1) {
                        var url = funct_or_url.replace('url:','');
                        self.location.href = url;
                    } else if (funct_or_url.indexOf('js:') > -1) {
                        eval(funct_or_url);
                    }
                }
            });
        };

        dialog = function(title, message, button, bclose){

            var x = button.split('|');
            var eval_funct = null;
            var variable = null;
            if (button != '') {

                if (button.indexOf('|')>-1) {
                    eval_funct = x[1];
                    if (x.length == 3) {
                        variable = x[2].split(',');
                    }
                }
                var button_primary = {
                    "label" : x[0],
                    "className" : "btn-success",
                    "callback": function() {
                        if (eval_funct !== null) {
                            if (variable !== null) {
                                if (x[2].indexOf(',') == -1) {
                                    eval(eval_funct+'('+variable+')');
                                } else if (x[2].indexOf(',') > -1) {
                                    eval(eval_funct+'('+variable[0]+','+variable[1]+');');
                                }
                            } else {
                                eval(eval_funct+'()');
                            }
                        }
                    }
                };
            }

            var button_close;
            if (bclose) {
                button_close = {
                    "label" : "Inchide",
                    "className" : "btn-default",
                    "callback": function() {
                        bootbox.hideAll();
                    }
                }
            }

            var buttons_all = [];
            if (button != '') {
                buttons_all.push(button_primary);
            }
            buttons_all.push(button_close);

            bootbox.dialog({
                message: message,
                title: title,
                buttons: buttons_all
            });

        };

        prompt = function (prompt_text, text_on_cancel, fillin,js_on_ok, fail_on_empty_prompt)
        {
            setTimeout(
                function () {
                    $.each($('input[class="bootbox-input bootbox-input-text form-control"]'), function(i,obj) {
                        $(obj).val(fillin);
                    });
                }, 200
            );

            bootbox.prompt(prompt_text, function(result) {
                if (result === null) {
                    if (text_on_cancel != '') bootbox.alert(text_on_cancel);
                } else {
                    if (fail_on_empty_prompt && result == '') {
                        alert('Eroare! Campul este gol, mai incearca o data!');
                    } else {
                        if (js_on_ok.indexOf('(') == -1) {
                            eval(js_on_ok+'(result);');
                        } else {
                            var x = js_on_ok.split('(');
                            var y = x[1].split(')');
                            var second_param = y[0];
                            eval(x[0]+'(result,second_param)');
                        }
                    }
                }
            });
        };




        //});
    };



    this.ad = {

        cars: false,

        changeClass: function (selected) {
            var thisObj = this;
            var carId = $('#select2CarMake').val();
            var userList= '<option value="">Clasa</option>';
            $("#select2CarModels").attr('disabled',false);
            $("#select2CarModels2").attr('disabled',true);
            $("#select2CarModels2").val('');
            //$('#select2CarModels').val(0);
            //$("#select2CarModels").select2('destroy');
            //$("#select2CarModels").select2();
            if (thisObj.cars.model[carId] != undefined) {
                $.each(thisObj.cars.categ[carId], function (i, v) {
                    userList += ('<option value="' + v + '">' + v + '</option>');
                });
            }
            $('#select2CarModels').html(userList);
            $('#select2CarModels').val(selected == 0 ? '' : selected);
            //$('#select2CarModels2').html('');
        },

        changeModel: function (selected) {
            var thisObj = this;
            var carId = $('#select2CarMake').val();
            var carCategId = $('#select2CarModels').val();
            var userList= '<option value="">Model</option>';
            $("#select2CarModels2").attr('disabled',false);
            if (thisObj.cars.model[carId] != undefined && thisObj.cars.model[carId] != undefined) {
                $.each(thisObj.cars.model[carId], function (i, v) {
                    if (v.categ == carCategId) {
                        userList += ('<option value="' + i + '">' + v.model + '</option>');
                    }

                });
            }
            $('#select2CarModels2').html(userList);
            $('#select2CarModels2').val(selected == 0 ? '' : selected);
        },

        create: function(uploadUrl) {
            this.cars = generalObj.cars;
            var thisObj = this;
            // avem selector de model

            if ($('#select2CarMake').length > 0 && this.cars !== false) {

                $('#select2CarMake').bind('change', function (i,v) {
                    thisObj.changeClass(0);
                });

                $('#select2CarModels').bind('change', function(i,v) {
                    thisObj.changeModel(0);
                });

            }

            $("#select2CarMake").select2({
                placeholder: "Alege Marca",
            });
            $("#select2CarPartsMain").select2({
                placeholder: "Alege Categorie",
            });

            // Initialize the jQuery File Upload widget:
            $('#fileupload').fileupload({
                disableImageResize:/Android(?!.*Chrome)|Opera/.test(window.navigator.userAgent),
                autoUpload:false,
                imageMaxWidth: 2000,
                imageMaxHeight: 2000,
                maxFileSize:2*1024*1024,
                acceptFileTypes:/(\.|\/)(jpe?g|png|gif)$/i,
                messages:{
                    maxNumberOfFiles:'Numarul maxim de fisiere depasit',
                    acceptFileTypes:'Tipul fisierului nu este admis',
                    maxFileSize:'Fisierul depaseste marimea admisa',
                    minFileSize:'Fisierul este sub marimea admisa'
                },
                formData:{
                    //dinselect:$("#selectDoi").val(),
                    //album_id:albumId
                },
                // Uncomment the following to send cross-domain cookies:
                //xhrFields: {withCredentials: true},
                url:uploadUrl
            });

            // Demo settings:
            $('#fileupload').fileupload('option', {
                url:$('#fileupload').fileupload('option', 'url'),
                // Enable image resizing, except for Android and Opera,
                // which actually support image resizing, but fail to
                // send Blob objects via XHR requests:
                //disableImageResize:/Android(?!.*Chrome)|Opera/.test(window.navigator.userAgent),
                //maxFileSize:10000000,

            });

            // Load & display existing files:
            $('#fileupload').addClass('fileupload-processing');
            $.ajax({
                // Uncomment the following to send cross-domain cookies:
                //xhrFields: {withCredentials: true},
                url:uploadUrl,
                dataType:'json',
                context:$('#fileupload')[0]
            }).always(function () {
                $(this).removeClass('fileupload-processing');
            }).done(function (result) {
                $(this).fileupload('option', 'done')
                    .call(this, $.Event('done'), {result:result});
            });



            // Upload server status check for browsers with CORS support:



        },

        callUpload: function(uploadUrl)
        {
            $.ajax({
                url: uploadUrl,
                type:'GET',
                dataType:'json',
                statusCode: {
                    403: function() {
                        alert( "Acces interzis!" );
                    },
                    404: function() {
                        alert( "Pagina nu a fost gasita!" ); //@TODO - ajax status
                    }
                },
                beforeSend : function() {

                }
            })
                .done(function( data ) {

                    if (data.error == 0) {
                        // Success so call function to process the form

                    }
                    else {
                        // Handle errors here
                        alert(data.message);
                    }

                });
        }


    };


};

var generalObj = new $.general;