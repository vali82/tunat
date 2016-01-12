var blueimp;
var page_loaded = false;

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

    var _ajaxCoolLoadPage = function(url, stateObj) {
        page_loaded = true;
        $.ajax({
            // Uncomment the following to send cross-domain cookies:
            //xhrFields: {withCredentials: true},
            url: url,
            dataType: 'json',
            statusCode: {
                403: function() {
                    alert( "Acces interzis!" );
                },
                404: function() {
                    alert( "Pagina nu a fost gasita!" ); //@TODO - ajax status
                }
            },
            beforeSend : function() {
                NProgress.configure({ minimum: 0.1, easing: 'ease', speed: 500 });
                NProgress.start();
                NProgress.inc(0.3);
                //$('#adGetContactButton').button('loading');
            },
            success: function(data, textStatus, jqXHR) {
                if (data.error !== undefined && !data.error && data.result !== undefined) {
                    if (stateObj == '') {
                        window.history.pushState('{urlPath:"' + url + '"}', 'Title', url);
                    }

                    $('#generalContainer').html(data.result.html);
                    $('#scriptsGeneral').html('<script type="text/javascript">'+data.result.js+'</script>');

                    $('#generalBanner').slideUp();
                    $('#categoryContainer').hide();
                    $('#mainContainer').addClass('mt100');
                    $('#changeCarMakeButton').show();

                    if ($('#pageTitleElement').length > 0) {
                        document.title = $('#pageTitleElement').html() + ' - Tirbox.ro';
                    } else {
                        document.title = 'Dezmembrari camioane, utilaje contructii si agricole, utilitare, autobuze, remorci - Tirbox.ro';
                    }
                } else {
                    //alert('asdad');
                }

                NProgress.done();

                $(document).scrollTop(0);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                window.location.replace(url); // simply dont think it will be here... but just in case
            }
        });
    };

    this.setAjaxCoolEvents = function (onlyThisSection, event) {

        var coolAjaxAvailable = false; // set true for ajax load page

        if (!history.pushState) {
            //alert('suported');
            //return true;
            coolAjaxAvailable = false;
        }
        if ($(window).width() < 768) {
            coolAjaxAvailable = false;
        }

        if (coolAjaxAvailable) {
            if (!onlyThisSection /*|| onlyThisSection == 'data-page-load'*/) {
                // cool ajax load pages on links
                //return true;
                $('a[data-page-load="ajax"]').on('click', function (e) {
                    e.preventDefault();
                    e.stopPropagation();

                    //$('#allCarsContainer').slideUp('slow');
                    $('#allCarsContainer').slideUp('normal', function() {
                        if ($(window).width() > 480) {
                            $('#announcement-listing').css('marginTop', '-126px');
                        }
                    });

                    _ajaxCoolLoadPage($(this).attr('href'), '');
                });
            }

            if (onlyThisSection == 'filterAds') {
                // filter form
                if (coolAjaxAvailable) {
                    _ajaxCoolLoadPage($('#searchAnnnouncement').attr('action'), '');
                    return false;
                } else {
                    return true;
                }
            } else if (onlyThisSection == 'historyCall') {
                _ajaxCoolLoadPage(document.location, event.state);
            }
        } else {
            if (onlyThisSection == 'filterAds') {
                return true;
            }
        }

        return true;
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

        window.onpopstate = function(event) {
            if(event && event.state) {
                generalObj.setAjaxCoolEvents('historyCall', event);
            } else {
                if (!page_loaded) {
                    page_loaded = true;
                    return false;
                } else {
                    history.go(0);
                }
            }
        };

        _handleBootstrapSwitch();


        // category click animation
        $("#categoryContainer .category a").on('click', function () {
            $(document).scrollTop(0);
            $(".banner-img").animate({
                height: "180px"
            });
            $(".banner .banner-text").fadeOut();
        });
        ///

        // show page effect on beforeload page
        $(window).bind('beforeunload', function(){
            NProgress.configure({ minimum: 0.1, easing: 'ease', speed: 500 });
            NProgress.start();
            NProgress.inc(0.3);
        });
        ////

        // hide page load effect
        setTimeout( function() { NProgress.done(); }, 1000);
        ////

        // filter button
        if ($('#searchAnnouncementsContainer').length > 0 && $(window).width() < 768) {
            $('#showSearchAnnouncements').show();
            $('#searchAnnouncementsContainer').hide();
            $('.parts-main-container').hide();
            $('#announcement-listing').show();

            $('#showSearchAnnouncements').on('click', function (e) {
                e.preventDefault();
                e.stopPropagation();

                if ($('#searchAnnouncementsContainer').is(':visible')) {
                    $('#searchAnnouncementsContainer').slideUp();
                    $('.parts-main-container').hide();
                    $('#announcement-listing').show();
                } else {
                    $('#searchAnnouncementsContainer').slideDown();
                    $('.parts-main-container').show();
                    $('#announcement-listing').hide();
                }
            });
        }
        ////

        this.setAjaxCoolEvents(false, false);
    };

    this.loginRegister = {
        init: function()
        {
            var thisObj = this;
            var _openLogin

            //$('#registerContainer').hide();
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

            /*$(document).on("click", "#registerTab", function(e){
                e.preventDefault();
                $('#registerContainer').slideDown();
                $('#loginContainer').slideUp();
                $('#errorLoginRegister').hide();
            });
            */
            $(document).on("click", "#loginTab", function(e){
                e.preventDefault();
                //$('#registerContainer').slideUp();
                $('#forgotContainer').slideUp();
                $('#loginContainer').slideDown();
                $('#errorLoginForm').hide();
            });
            $(document).on("click", "#forgotTab", function(e){
                e.preventDefault();
                $('#forgotContainer').slideDown();
                $('#loginContainer').slideUp();
                $('#errorLoginForm').hide();
            });


            // efect on click pe social media login
            $('a[id^=loginOption]').on('click', function(){
                var socialMediaType = $(this).attr('id').replace('loginOption', '');
                $('.contentLoginRegister').css('visibility', 'hidden');
                $('#msgCenterAuth').html('Autentificare prin '+socialMediaType+'...');
            });
            ////
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
                    $('#errorRegisterForm').hide();
                    $('#registerLoading').show();
                    $('#registerButton').hide();
                }
            }).done(function( data ) {

                $('#errorRegisterForm').slideDown();
                $('#errorRegisterForm').html(data.message);

                if (data.error == 0) {
                    $('#registerForm').slideUp();
                    //self.location.replace(data.result.redirectUrl);
                    thisObj._doLoginDefault(
                        form.attr("data-login-action"),
                        true
                    )
                } else {
                    $('#registerLoading').hide();
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
                        $('#errorLoginForm').hide();
                        $('#loginLoading').show();
                        $('#loginButton').hide();
                    }
                }
            }).done(function( data ) {

                $('#errorLoginForm').slideDown();
                $('#errorLoginForm').html(data.message);

                if (data.error == 0) {
                    if (!afterRegister) {
                        $('#loginContainer').slideUp();
                        $('#loginSocialContainer').fadeOut();
                    }
                    $('#loginLoading').hide();
                    setTimeout(function () {
                        self.location.replace(data.result.redirectUrl);
                    }, 1000);


                } else {
                    if (!afterRegister) {
                        $('#loginLoading').hide();
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
                $('#searchAnnnouncement').bind('submit', function() {
                    var searchQuery = $('#searchInput').val().replace(/ /g,'+').replace(/"/g,'').split('/').join('');
                    //if ($('#searchYear').val() > 0 ) {
                    searchQuery += ':' + $('#searchYear').val();
                    //}
                    //if ($('#searchCounty').val() > 0 ) {
                    searchQuery += ':' + $('#searchStare').val();
                    searchQuery += ':' + $('#searchCounty').val();
                    searchQuery += ':' + $('#searchOem').val().replace(/ /g,'+').replace(/"/g,'').split('/').join('');
                    //}
                    var actionForm = $('#searchAnnnouncement').attr('action').
                        replace(
                            '__search__',
                            searchQuery
                        )
                    ;
                    $('#searchAnnnouncement').attr('action', actionForm);
                    $('#button-search-ads').button('loading');
                    return generalObj.setAjaxCoolEvents('filterAds', false);
                    //return false;
                });

                // set blueimp gallery on pictures
                $('a[data-image-lib="popup"]').on('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();

                    var pics = [];
                    $.each($('a[data-image-lib="popup"]'), function(i,v) {
                        pics.push({
                            title: $(v).attr('title'),
                            href: $(v).attr('href'),
                            type: 'image/jpeg',
                            thumbnail: $(v).attr('href')
                        });
                    });
                    var galleryImg = blueimp.Gallery(pics);

                });
                ////
            },
            _changeCarMake: function () {
                if ($('#allCarsContainer').is(':visible')) {
                    $('#allCarsContainer').slideUp('normal', function() {
                        if ($(window).width() > 480) {
                            $('#announcement-listing').css('marginTop', '-126px');
                        }
                    });

                } else {
                    if ($(window).width() > 480) {
                        $('#announcement-listing').css('marginTop', '0px');
                    }
                    $('#allCarsContainer').slideDown('normal', function() {

                    });

                }

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
                            $('#contactParkPhone').parent().attr('href', 'tel:'+data.result.tel1);
                            if (data.result.email != '') {
                                $('#contactParkEmail').attr('href', 'mailto:' + data.result.email+'?subject=Info - '+
                                $('#pageTitleElement').html().trim()
                                +' - Tirbox.ro&body=In legatura cu anuntul de pe www.tirbox.ro - '+
                                window.location.href
                                );
                            } else {
                                $('#contactParkEmail').hide();
                                $('#contactParkPhone').parent().css('width', '100%');
                            }
                            $('#contactParkAddress').html(data.result.location);
                            $('#contactParkAddress').parent().attr('href', 'http://maps.google.com/?q='+data.result.location);
                            $('#adGetContactButton').slideUp();
                            $('#contactParkContainer').slideDown();
                        } else {
                            alert(data.message);
                        }
                        $('#adGetContactButton').button('reset');
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
    };

    this.sliderInit = function () {
        if ($(window).width() > 768) {
            jQuery(document).ready(function ($) {

                var options = {
                    $FillMode: 2,                                       //[Optional] The way to fill image in slide, 0 stretch, 1 contain (keep aspect ratio and put all inside slide), 2 cover (keep aspect ratio and cover whole slide), 4 actual size, 5 contain for large image, actual size for small image, default value is 0
                    $AutoPlay: true,                                    //[Optional] Whether to auto play, to enable slideshow, this option must be set to true, default value is false
                    $AutoPlayInterval: 4000,                            //[Optional] Interval (in milliseconds) to go for next slide since the previous stopped if the slider is auto playing, default value is 3000
                    $PauseOnHover: 1,                                   //[Optional] Whether to pause when mouse over if a slider is auto playing, 0 no pause, 1 pause for desktop, 2 pause for touch device, 3 pause for desktop and touch device, 4 freeze for desktop, 8 freeze for touch device, 12 freeze for desktop and touch device, default value is 1

                    $ArrowKeyNavigation: true,   			            //[Optional] Allows keyboard (arrow key) navigation or not, default value is false
                    $SlideEasing: $JssorEasing$.$EaseOutQuint,          //[Optional] Specifies easing for right to left animation, default value is $JssorEasing$.$EaseOutQuad
                    $SlideDuration: 800,                               //[Optional] Specifies default duration (swipe) for slide in milliseconds, default value is 500
                    $MinDragOffsetToSlide: 20,                          //[Optional] Minimum drag offset to trigger slide , default value is 20
                    //$SlideWidth: 600,                                 //[Optional] Width of every slide in pixels, default value is width of 'slides' container
                    //$SlideHeight: 300,                                //[Optional] Height of every slide in pixels, default value is height of 'slides' container
                    $SlideSpacing: 0, 					                //[Optional] Space between each slide in pixels, default value is 0
                    $DisplayPieces: 1,                                  //[Optional] Number of pieces to display (the slideshow would be disabled if the value is set to greater than 1), the default value is 1
                    $ParkingPosition: 0,                                //[Optional] The offset position to park slide (this options applys only when slideshow disabled), default value is 0.
                    $UISearchMode: 1,                                   //[Optional] The way (0 parellel, 1 recursive, default value is 1) to search UI components (slides container, loading screen, navigator container, arrow navigator container, thumbnail navigator container etc).
                    $PlayOrientation: 1,                                //[Optional] Orientation to play slide (for auto play, navigation), 1 horizental, 2 vertical, 5 horizental reverse, 6 vertical reverse, default value is 1
                    $DragOrientation: 1,                                //[Optional] Orientation to drag slide, 0 no drag, 1 horizental, 2 vertical, 3 either, default value is 1 (Note that the $DragOrientation should be the same as $PlayOrientation when $DisplayPieces is greater than 1, or parking position is not 0)

                    $BulletNavigatorOptions: {                          //[Optional] Options to specify and enable navigator or not
                        $Class: $JssorBulletNavigator$,                 //[Required] Class to create navigator instance
                        $ChanceToShow: 2,                               //[Required] 0 Never, 1 Mouse Over, 2 Always
                        $AutoCenter: 1,                                 //[Optional] Auto center navigator in parent container, 0 None, 1 Horizontal, 2 Vertical, 3 Both, default value is 0
                        $Steps: 1,                                      //[Optional] Steps to go for each navigation request, default value is 1
                        $Lanes: 1,                                      //[Optional] Specify lanes to arrange items, default value is 1
                        $SpacingX: 8,                                   //[Optional] Horizontal space between each item in pixel, default value is 0
                        $SpacingY: 8,                                   //[Optional] Vertical space between each item in pixel, default value is 0
                        $Orientation: 1,                                //[Optional] The orientation of the navigator, 1 horizontal, 2 vertical, default value is 1
                        $Scale: false                                   //Scales bullets navigator or not while slider scale
                    },

                    $ArrowNavigatorOptions: {                           //[Optional] Options to specify and enable arrow navigator or not
                        $Class: $JssorArrowNavigator$,                  //[Requried] Class to create arrow navigator instance
                        $ChanceToShow: 1,                               //[Required] 0 Never, 1 Mouse Over, 2 Always
                        $AutoCenter: 2,                                 //[Optional] Auto center arrows in parent container, 0 No, 1 Horizontal, 2 Vertical, 3 Both, default value is 0
                        $Steps: 1                                       //[Optional] Steps to go for each navigation request, default value is 1
                    }
                };

                //Make the element 'generalBanner' visible before initialize jssor slider.
                $("#generalBanner").css("display", "block");
                var jssor_slider1 = new $JssorSlider$("generalBanner", options);

                //responsive code begin
                //you can remove responsive code if you don't want the slider scales while window resizes
                function ScaleSlider() {
                    var bodyWidth = document.body.clientWidth;
                    if (bodyWidth)
                        jssor_slider1.$ScaleWidth(Math.min(bodyWidth, 1920));
                    else
                        window.setTimeout(ScaleSlider, 30);
                }
                ScaleSlider();

                $(window).bind("load", ScaleSlider);
                $(window).bind("resize", ScaleSlider);
                $(window).bind("orientationchange", ScaleSlider);
                //responsive code end
            });
        }

    }

};

var generalObj = new $.general;