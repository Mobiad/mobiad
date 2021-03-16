var input = document.querySelector("#phoneNumber");

window.intlTelInput(input, {
    separateDialCode: true,
    nationalMode: true,
    initialCountry: "TZ",
    hiddenInput: "full_phone",
    utilsScript: "js/utils.js"
});

