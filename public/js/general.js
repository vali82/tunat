
$.general = function() {

    this.cars = false;

    var _handleBootstrapSwitch = function () {
        if (!jQuery().bootstrapSwitch) {
            return;
        }
        $('.make-switch').bootstrapSwitch();
    };

    var _uploadImages = function (uploadUrl)
    {
        // Initialize the jQuery File Upload widget:
        $('#fileupload').fileupload({
            disableImageResize:/Android(?!.*Chrome)|Opera/.test(window.navigator.userAgent),
            autoUpload:true,
            imageMaxWidth: 2000,
            imageMaxHeight: 2000,
            maxFileSize:2*1024*1024,
            maxFileCount:5,
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
        //$('#fileupload').fileupload('option', {
        //    url:$('#fileupload').fileupload('option', 'url'),
        //    // Enable image resizing, except for Android and Opera,
        //    // which actually support image resizing, but fail to
        //    // send Blob objects via XHR requests:
        //    //disableImageResize:/Android(?!.*Chrome)|Opera/.test(window.navigator.userAgent),
        //    //maxFileSize:10000000,
        //
        //});

        // Load & display existing files:
        $('#fileupload').addClass('fileupload-processing');
        $(document).ready(function() {
            $.ajax({
                // Uncomment the following to send cross-domain cookies:
                //xhrFields: {withCredentials: true},
                url: uploadUrl,
                dataType: 'json',
                context: $('#fileupload')[0]
            }).always(function () {
                $(this).removeClass('fileupload-processing');
            }).done(function (result) {
                $(this).fileupload('option', 'done')
                    .call(this, $.Event('done'), {result: result});
            });
        });


    };

    this.onLoad = function () {

        var thisObj = this;

        $.each($('a[data-toggle="tooltip"]'), function(i,obj) {
            $(obj).tooltip();
        });
        $.each($('a[data-toggle="popover"]'), function(i,obj) {
            $(obj).popover();
        });

        alert = function (p){
            bootbox.alert(p);
        };

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


        _handleBootstrapSwitch();


        // category click animation
        $("#categoryContainer .category a").on('click', function () {
            $(".banner-img").animate({
                height: "180px"
            });
            $(".banner .banner-text").fadeOut();
        });
        $("a.marca-link").on('click', function () {
            $(".banner-img").slideUp('slow');
            $('#categoryContainer').fadeOut('slow');
            //$(".banner .banner-text").fadeOut();
        });
        ///

    };

    this.loginRegister = {
        init: function()
        {
            var thisObj = this;
            var _openLogin

            $('#registerContainer').hide();
            $('#forgotContainer').hide();

            // rewrite login form action
            $("body").on("submit", "#loginForm", function(e){
                e.preventDefault();
                thisObj._doLoginDefault($(e.target).attr("action"), false);
            });
            ////

            // rewrite register form action
            $("body").on("submit", "#registerForm", function(e){
                e.preventDefault();
                thisObj._doRegisterDefault($(e.target));
            });
            ////

            $(document).on("click", "#registerTab", function(e){
                e.preventDefault();
                $('#registerContainer').slideDown();
                $('#loginContainer').slideUp();
                $('#errorLoginRegister').hide();
            });
            $(document).on("click", "#loginTab", function(e){
                e.preventDefault();
                $('#registerContainer').slideUp();
                $('#forgotContainer').slideUp();
                $('#loginContainer').slideDown();
                $('#errorLoginRegister').hide();
            });
            $(document).on("click", "#forgotTab", function(e){
                e.preventDefault();
                $('#forgotContainer').slideDown();
                $('#loginContainer').slideUp();
                $('#errorLoginRegister').hide();
            });


            // efect on click pe social media login
            $('a[id^=loginOption]').on('click', function(){
                var socialMediaType = $(this).attr('id').replace('loginOption', '');
                $('#errorLoginRegister').slideDown();
                $('#errorLoginRegister').html('Autentificare prin '+socialMediaType+'...');
                $('#errorLoginRegister').css('font-size', '16px');
                $('#loginContainer').slideUp();
                $('#registerContainer').slideUp();
                $('#registerSocialContainer').fadeOut();
            });
            ////

            // login button opens login/register form
            $('#loginNavMenuButton').on('click', function(){
                thisObj._openMainContainer();
            });

            // create ad for unlogged users
            $('.loginActionCreateAd').on('click', function(e) {
                e.preventDefault();
                thisObj._openMainContainer();
            });
        },

        _openMainContainer: function()
        {
            $('#LoginRegisterContainer').slideToggle('fast', function(){
                if ($('.navbar-toggle').is(':visible') && $('#LoginRegisterContainer').is(':visible')) {
                    $(".navbar-collapse").collapse('hide');
                }
            });
        },

        _doRegisterDefault: function (form)
        {
            var thisObj = this;
            $.ajax( {
                url: form.attr("action"),
                type: "POST",
                data: {
                    data: {
                        email: $('#globalRegisterFieldEmail').val(),
                        password: $('#globalRegisterFieldPass').val(),
                        passwordVerify: $('#globalRegisterFieldRePass').val()
                    }
                },
                dataType: "json",
                statusCode: {
                    403: function() {
                        alert( "Acces interzis!" );
                    },
                    404: function() {
                        alert( "Pagina nu a fost gasita!" );
                    }
                },
                beforeSend : function() {
                    $('#errorLoginRegister').hide();
                    $('#loginRegisterLoading').show();
                    $('#registerButton').hide();
                }
            }).done(function( data ) {

                $('#errorLoginRegister').slideDown();
                $('#errorLoginRegister').html(data.message);

                if (data.error == 0) {
                    $('#registerForm').slideUp();
                    //self.location.replace(data.result.redirectUrl);
                    thisObj._doLoginDefault(
                        form.attr("data-login-action"),
                        true
                    )
                } else {
                    $('#loginRegisterLoading').hide();
                    $('#registerButton').show();
                }
            });
        },

        _doLoginDefault: function (formAction, afterRegister)
        {
            $.ajax( {
                url: formAction,
                type: "POST",
                data: {
                    data: {
                        username: afterRegister ?
                            $('#globalRegisterFieldEmail').val() :
                            $('#globalLoginFieldIdentity').val()
                        ,
                        password: afterRegister ?
                            $('#globalRegisterFieldPass').val() :
                            $('#globalLoginFieldPass').val(),
                        rememberme: afterRegister ? 0 : (
                            $('#globalLoginFieldRemember').is(':checked') ? 1 : 0
                        )

                    }
                },
                dataType: "json",
                statusCode: {
                    403: function() {
                        alert( "Acces interzis!" );
                    },
                    404: function() {
                        alert( "Pagina nu a fost gasita!" );
                    }
                },
                beforeSend : function() {
                    if (!afterRegister) {
                        $('#errorLoginRegister').hide();
                        $('#loginRegisterLoading').show();
                        $('#loginButton').hide();
                    }
                }
            }).done(function( data ) {

                $('#errorLoginRegister').slideDown();
                $('#errorLoginRegister').html(data.message);

                if (data.error == 0) {
                    if (!afterRegister) {
                        $('#loginContainer').slideUp();
                        $('#loginSocialContainer').fadeOut();
                    }
                    $('#loginRegisterLoading').hide();
                    setTimeout(function () {
                        self.location.replace(data.result.redirectUrl);
                    }, 1000);


                } else {
                    if (!afterRegister) {
                        $('#loginRegisterLoading').hide();
                        $('#loginButton').show();
                    }
                }
            });
        }

    };

    this.ad = {

        cars: false,

        changeClass: function (selected) {
            var thisObj = this;
            var carId = $('#select2CarMake').val();
            var userList= '<option value="">Marca</option>';
            $("#select2CarModels").attr('disabled',false);
            $("#select2CarModels2").attr('disabled',true);
            $("#select2CarModels2").val('');
            if (thisObj.cars.model[carId] != undefined) {
                $.each(thisObj.cars.model[carId], function (i, v) {
                    userList += ('<option value="' + i + '">' + v.categ + '</option>');
                });
            }
            $('#select2CarModels').html(userList);
            $('#select2CarModels').val(selected == 0 ? '' : selected);
        },

        changeModel: function (selected) {
            var thisObj = this;
            var carId = $('#select2CarMake').val();
            var carCategId = $('#select2CarModels').val();
            $("#select2CarModels2").attr('disabled',false);
            /*var userList= '<option value="">Model</option>';
            $("#select2CarModels2").attr('disabled',false);
            if (thisObj.cars.model[carId] != undefined && thisObj.cars.model[carId] != undefined) {
                $.each(thisObj.cars.model[carId], function (i, v) {
                    if (v.categ == carCategId) {
                        userList += ('<option value="' + i + '">' + v.model + '</option>');
                    }

                });
            }*/
            $('#select2CarModels2').val(selected);
            $("#year_start").attr('disabled',false);
            $("#year_end").attr('disabled',false);
            //$('#select2CarModels2').val(selected == 0 ? '' : selected);
        },

        create: function(uploadUrl) {
            this.cars = generalObj.cars;
            var thisObj = this;


            if ($('#select2CarMake').length > 0 && this.cars !== false) {
                $('#select2CarMake').bind('change', function (i,v) {
                    thisObj.changeClass(0);
                });
                $('#select2CarModels').bind('change', function(i,v) {
                    thisObj.changeModel('');
                });

            }
            _uploadImages(uploadUrl);

        },

        callUpload: function(uploadUrl) {
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
        },

        search: {
            _contactUrl: null,
            init: function(contactUrl) {
                var thisObj = this;
                this._contactUrl = contactUrl;
                $('#changeCarMakeButton').bind('click', function(){
                    thisObj._changeCarMake();
                });
                $('#adGetContactButton').bind('click', function(){
                    thisObj._getContact();
                });
                $('#searchAds').bind('submit', function() {
                    var searchQuery = $('#searchInput').val().replace(/ /g,'+').replace(/"/g,'').split('/').join('');
                    //if ($('#searchYear').val() > 0 ) {
                    searchQuery += ':' + $('#searchYear').val();
                    //}
                    //if ($('#searchCounty').val() > 0 ) {
                    searchQuery += ':' + $('#searchStare').val();
                    searchQuery += ':' + $('#searchCounty').val();
                    //}
                    var actionForm = $('#searchAds').attr('action').
                        replace(
                            '__search__',
                            searchQuery
                        )
                    ;
                    $('#searchAds').attr('action', actionForm);
                    //return false;
                });
            },
            _changeCarMake: function () {
                $('#allCarsContainer').show();
            },
            _getContact: function()
            {
                $.ajax({
                    // Uncomment the following to send cross-domain cookies:
                    //xhrFields: {withCredentials: true},
                    url: this._contactUrl,
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
                        $('#adGetContactButton').button('loading');
                    }
                })
                    .done(function (data) {
                    if (!data.error) {
                        $('#contactParkPhone').html(data.result.tel1);
                        $('#contactParkEmail').html(data.result.email);
                        $('#contactParkAddress').html(data.result.location);
                        $('#adGetContactButton').slideUp();
                        $('#contactParkContainer').slideDown();
                    } else {
                        alert(data.message);
                    }
                });
            }
        }


    };

    this.offers = {
        cars: false,

        changeClass: function (selected) {
            var thisObj = this;
            var carId = $('#select2CarMake').val();
            var userList= '<option value="">Marca</option>';
            $("#select2CarModels").attr('disabled',false);
            $("#select2CarModels2").attr('disabled',true);
            $("#select2CarModels2").val('');
            if (thisObj.cars.model[carId] != undefined) {
                $.each(thisObj.cars.model[carId], function (i, v) {
                    userList += ('<option value="' + i + '">' + v.categ + '</option>');
                });
            }
            $('#select2CarModels').html(userList);
            $('#select2CarModels').val(selected == 0 ? '' : selected);
        },

        changeModel: function (selected) {
            var thisObj = this;
            var carId = $('#select2CarMake').val();
            var carCategId = $('#select2CarModels').val();
            $("#select2CarModels2").attr('disabled',false);
            /*var userList= '<option value="">Model</option>';
             $("#select2CarModels2").attr('disabled',false);
             if (thisObj.cars.model[carId] != undefined && thisObj.cars.model[carId] != undefined) {
             $.each(thisObj.cars.model[carId], function (i, v) {
             if (v.categ == carCategId) {
             userList += ('<option value="' + i + '">' + v.model + '</option>');
             }

             });
             }*/
            $('#select2CarModels2').val(selected);
            $("#year_start").attr('disabled',false);
            $("#year_end").attr('disabled',false);
            //$('#select2CarModels2').val(selected == 0 ? '' : selected);
        },

        create: function(uploadUrl) {
            this.cars = generalObj.cars;
            var thisObj = this;


            if ($('#select2CarMake').length > 0 && this.cars !== false) {
                $('#select2CarMake').bind('change', function (i,v) {
                    thisObj.changeClass(0);
                });
                $('#select2CarModels').bind('change', function(i,v) {
                    thisObj.changeModel('');
                });

            }
            _uploadImages(uploadUrl);

            $(document).on('click', "a[data-clone]", function(){
                if ($(this).data('clone') == 'piesa') {
                    console.log('asdsad');

                    var row = $(this).parent();
                    $.each($('div.piesa'), function(i,v){
                        var x  =$(v).clone();
                        $(x).removeClass('piesa');
                        $(x).insertBefore(row);
                    });
                }
            });

        }
    };

    this.myAccount = {
        update: function() {

            var _enableParcFields = function() {
                $('#name').parent().slideDown();
                $('#description').parent().slideDown();
                $('#url').parent().slideDown();
                //$('#name2').parent().parent().hide();
                //$('#name2').next().html('obligatoriu: nu va aparea nicaieri pe site');
            };
            var _enableParticularFields = function() {
                $('#name').parent().slideUp();
                $('#description').parent().slideUp();
                $('#url').parent().slideUp();
                //$('#name2').parent().parent().show();
                //$('#name2').next().html('obligatoriu: va aparea in detaliile anuntului');
            };
            $('#accountType').parent().parent().css('width', '200px');
            if ($('#accountType').is(':checked')) {
                _enableParcFields();
            } else {
                _enableParticularFields();
            }

            $('#accountType').on('switch-change', function (e, data) {
                var $el = $(this);
                if(!data.value) {
                    _enableParticularFields();
                } else {
                    _enableParcFields();
                }
            });
        }
    }

};

var generalObj = new $.general;