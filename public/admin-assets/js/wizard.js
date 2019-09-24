(function ($) {

    'use strict';

    var form = $("#example-form");

    form.val({
        errorPlacement: function errorPlacement(error, element) {
            element.before(error);
        },
        rules: {
            host_name : {
                required: true,
            },
        }
    });


    form.children("div").steps({
        headerTag: "h3",
        bodyTag: "section",
        transitionEffect: "slideLeft",
        onStepChanging: function (event, currentIndex, newIndex) {

            return form.val();
        },
        onFinishing: function (event, currentIndex) {
            form.val({
                ignore: [':disabled']
            })
            return form.val();
        },
        onFinished: function (event, currentIndex) {
            alert("Submitted host details!");

            var form = $(this);
            form.submit();

            jQuery('form').submit();

            console.log("Hello");
        }
    });



    var validationForm = $("#example-validation-form");

    validationForm.val({
        errorPlacement: function errorPlacement(error, element) {
            element.before(error);
        },
        rules: {
            confirm: {
                equalTo: "#password"
            },
            password : {
                required: true,
            },
        }
    });
    validationForm.children("div").steps({
        headerTag: "h3",
        bodyTag: "section",
        transitionEffect: "slideLeft",
        onStepChanging: function (event, currentIndex, newIndex) {
            validationForm.val({
                ignore: [":disabled",":hidden"]
            })
            return validationForm.val();
        },
        onFinishing: function (event, currentIndex) {
            validationForm.val({
                ignore: [':disabled']
            })
            return validationForm.val();
        },
        onFinished: function (event, currentIndex) {
            alert("Submitted!");
        }
    });
    var verticalForm = $("#example-vertical-wizard");
    verticalForm.children("div").steps({
        headerTag: "h3",
        bodyTag: "section",
        transitionEffect: "slideLeft",
        stepsOrientation: "vertical",
        onFinished: function (event, currentIndex) {
            alert("Submitted!");
        }
    });
})(jQuery);
