jQuery(function ($) {

    // Запрос демо регистрации (без создания площадки)
    $('#reg-form-taste').on('submit', lodash.throttle(async function (e) {
        e.preventDefault();
        await generateRecaptchaToken();
        const $form = $(this);
        const $formPre = $('#reg-form-taste-pre');
        const $formFound = $('#reg-form-taste-found');
        const $formBtn = $('#reg-form-taste .taste__form-btn');

        if (isError(this)) {
            $formBtn.prop("disabled", true);
            let formData = {};
            const email = $(this).find('#reg-form-taste-email').val();
            const name = $(this).find('#reg-form-taste-name').val();
            const phone = $(this).find('#reg-form-taste-phone').val();
            if (email) {
                formData = {
                    name: name,
                    phone: phone,
                    email: email,
                };
                $.ajax({
                    url: 'ajax.php',
                    type: "POST",
                    global: false,
                    async: false,
                    data: {
                        action: 'reg-form-taste',
                        data: formData
                    },
                    beforeSend: addSpinner
                }).done(function (response) {
                    removeSpinner();
                    console.log(response);
                    if (response) {
                        getIntegrationMessageContent(clientSuccessIntegrationTitleNew, clientSuccessIntegrationContentNew);
                        $form.trigger("reset");
                        $formBtn.prop("disabled", false);
                    }
                }).fail(function (request, status, error) {
                    console.log(request);
                    console.log(status);
                    console.log(error);
                    removeSpinner();
                    $formBtn.prop("disabled", false);
                    //history.replaceState(null, null, '/');
                });
            }
        }
        return false;
    }, 1500));

    
});
