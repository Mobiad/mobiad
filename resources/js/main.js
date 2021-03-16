$(function() {
    var start = moment().subtract(29, "days");
    var end = moment();

    $('input[name="from"]').val(start);
    $('input[name="to"]').val(end);

    function cb(start, end) {
        console.log(
            "A new date selection was made: " +
                start.format("YYYY-MM-DD") +
                " to " +
                end.format("YYYY-MM-DD")
        );
        $('input[name="from"]').val(start.format("YYYY-MM-DD"));
        $('input[name="to"]').val(end.format("YYYY-MM-DD"));
        $("#reportrange span").html(
            start.format("MMMM D, YYYY") + " - " + end.format("MMMM D, YYYY")
        );
    }

    $("#reportrange").daterangepicker(
        {
            startDate: start,
            endDate: end,
            ranges: {
                Today: [moment(), moment()],
                Yesterday: [
                    moment().subtract(1, "days"),
                    moment().subtract(1, "days")
                ],
                "Last 7 Days": [moment().subtract(6, "days"), moment()],
                "Last 30 Days": [moment().subtract(29, "days"), moment()],
                "This Month": [
                    moment().startOf("month"),
                    moment().endOf("month")
                ],
                "Last Month": [
                    moment()
                        .subtract(1, "month")
                        .startOf("month"),
                    moment()
                        .subtract(1, "month")
                        .endOf("month")
                ]
            }
        },
        cb
    );

    cb(start, end);

    $("#signup")
        .find("input#phoneNumber")
        .on("keyup", function(event) {
            var isValid = $("#phoneNumber").intlTelInput("isValidNumber");
            if (!isValid) {
                event.preventDefault();
            }
        });
});
