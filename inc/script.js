var launcher = setInterval(function () {
    if(typeof(jQuery) != "undefined") {
        clearInterval(launcher);
        runCalendar();
    }
}, 100);

function runCalendar() {
    $ = jQuery;
    var arr;
    var reservations = [];
    $.each($('#super-booking-calendar').data("json").split(";+"), function(a, b) {
        if(b != "") {
            arr = b.split(",;");
            reservations.push({title:arr[0],start:arr[1]});
        }
    });
    $('#super-booking-calendar').fullCalendar({
        firstDay: 1,
        events: reservations,
        /*dayNamesShort: days,
        monthNames: months,
        events: reservations,*/
        dayClick: function(date, jsEvent, view) {
            /*
            if(date._d.getDay() != 0 && date._d.getDay() != 6 && isFree(date)) {
                if(lastDaySelected != null) {
                    lastDaySelected.css('background-color', '#DCDCDC');
                }
                $(this).css('background-color', '#BDE8AE');
                $('html,body').animate({scrollTop: (-200+$("#horaires").offset().top) },'slow');
                $("input[name=reservation_date]").val(date.format());
                selectedDate = date.format();
                lastDaySelected = $(this);
            }*/
        },
        dayRender: function( date, cell ) {
            /*
            if(cell.hasClass("fc-sat") || cell.hasClass("fc-sun")) {
                cell.css('background-color', '#b9b9b9');
            }
            else {
                cell.css('background-color', '#DCDCDC');
            }*/
        }
    });
}