
$(document).ready(function () {
    $('button').click(function() {
        checked = $("input[type=checkbox]:checked").length;
        max = document.getElementById("Days").value / 4
        if(checked !== max  ) {
            alert( "اختر " + document.getElementById("Days").value / 4 + " حلقات فقط"  );
            return false;
        }
    });
});
$(document).ready(function () {
    $('button').click(function() {
        checked = $('input[name=Saturday]:checked').length;
        value = document.getElementById("Saturday_time").value.length;
        if(checked > 0  ) { if (value === 0 ) { alert( "Insert Saturday time Or Dont Choosse Saturday" ); return false;}};
        if (value > 0 ) { if (checked === 0 ) { alert( "Choosse  Saturday Or Delete Saturday time" ); return false; }};
    });
});
$(document).ready(function () {
    $('button').click(function() {
        checked = $('input[name=Sunday]:checked').length;
        value = document.getElementById("Sunday_time").value.length;
        if(checked > 0  ) { if (value === 0 ) { alert( "Insert Sunday time Or Dont Choosse Sunday" ); return false;}};
        if (value > 0 ) { if (checked === 0 ) { alert( "Choosse  Sunday Or Delete Sunday time" ); return false; }};
    });
});
$(document).ready(function () {
    $('button').click(function() {
        checked = $('input[name=Monday]:checked').length;
        value = document.getElementById("Monday_time").value.length;
        if(checked > 0  ) { if (value === 0 ) { alert( "Insert Monday time Or Dont Choosse Monday" ); return false;}};
        if (value > 0 ) { if (checked === 0 ) { alert( "Choosse  Monday Or Delete Monday time" ); return false; }};
    });
});
$(document).ready(function () {
    $('button').click(function() {
        checked = $('input[name=Tuesday]:checked').length;
        value = document.getElementById("Tuesday_time").value.length;
        if(checked > 0  ) { if (value === 0 ) { alert( "Insert Tuesday time Or Dont Choosse Tuesday" ); return false;}};
        if (value > 0 ) { if (checked === 0 ) { alert( "Choosse  Tuesday Or Delete Tuesday time" ); return false; }};
    });
});
$(document).ready(function () {
    $('button').click(function() {
        checked = $('input[name=Wednesday]:checked').length;
        value = document.getElementById("Wednesday_time").value.length;
        if(checked > 0  ) { if (value === 0 ) { alert( "Insert Wednesday time Or Dont Choosse Wednesday" ); return false;}};
        if (value > 0 ) { if (checked === 0 ) { alert( "Choosse  Wednesday Or Delete Wednesday time" ); return false; }};
    });
});
$(document).ready(function () {
    $('button').click(function() {
        checked = $('input[name=Thursday]:checked').length;
        value = document.getElementById("Thursday_time").value.length;
        if(checked > 0  ) { if (value === 0 ) { alert( "Insert Thursday time Or Dont Choosse Thursday" ); return false;}};
        if (value > 0 ) { if (checked === 0 ) { alert( "Choosse  Thursday Or Delete Thursday time" ); return false; }};
    });
});
$(document).ready(function () {
    $('button').click(function() {
        checked = $('input[name=Friday]:checked').length;
        value = document.getElementById("Friday_time").value.length;
        if(checked > 0  ) { if (value === 0 ) { alert( "Insert Friday time Or Dont Choosse Friday" ); return false;}};
        if (value > 0 ) { if (checked === 0 ) { alert( "Choosse  Friday Or Delete Friday time" ); return false; }};
    });
});

$(".toggle-password").click(function() {

    $(this).toggleClass("bi bi-eye-slash-fill");
    var input = $($(this).attr("toggle"));
    if (input.attr("type") == "password") {
        input.attr("type", "text");
    } else {
        input.attr("type", "password");
    }
    });

$('input[type=number]').on('mousewheel', function(e) {
    $(e.target).blur();
    });
