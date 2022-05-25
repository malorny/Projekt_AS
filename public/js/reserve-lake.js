function check()
{
    let data = {
        lake: $("#reserve_lake_form_lakeId").val(),
        begin_month: $("#reserve_lake_form_beginDate_date_month").val(),
        begin_day: $("#reserve_lake_form_beginDate_date_day").val(),
        begin_year: $("#reserve_lake_form_beginDate_date_year").val(),
        begin_hour: $("#reserve_lake_form_beginDate_time_hour").val(),
        begin_minute: $("#reserve_lake_form_beginDate_time_minute").val(),
        end_month: $("#reserve_lake_form_beginDate_date_month").val(),
        end_day: $("#reserve_lake_form_beginDate_date_day").val(),
        end_year: $("#reserve_lake_form_beginDate_date_year").val(),
        end_hour: $("#reserve_lake_form_beginDate_time_hour").val(),
        end_minute: $("#reserve_lake_form_beginDate_time_minute").val()
    };

    $.ajax({
        type: 'POST',
        url: '/reserve_lake_check',
        data: data,
        success: function(e) {
            if (!e) {
                $("#freeReservation").show();
                $(':input[type="submit"]').prop('disabled', true);
            } else {
                $("#freeReservation").hide();
                $(':input[type="submit"]').prop('disabled', false);
            }
        }
    });
}

window.onload = () => {
    check();

    $("select[id^='reserve_lake_form']").on('change', function(e) {
        check();
    });
}