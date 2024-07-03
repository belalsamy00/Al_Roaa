function action(spinner, action, form) {
    spinner.style.setProperty("display", "inline-block", "important");
    action.style.setProperty("display", "none", "important");
    setTimeout(function () {
        spinner.style.setProperty("display", "none", "important");
        form.style.setProperty("display", "inline-block", "important");
    }, 1000);
}
function action_Add_Absence(spinner_Add_Absence, action_Add_Absence, form_Add_Absence) {
    spinner_Add_Absence.style.setProperty("display", "inline-block", "important");
    action_Add_Absence.style.setProperty("display", "none", "important");
    setTimeout(function () {
        spinner_Add_Absence.style.setProperty("display", "none", "important");
        form_Add_Absence.style.setProperty("display", "inline-block", "important");
    }, 1000);
}
function submit_form(spinner,form) {
    form.style.setProperty ("display" ,"none", "important");
    spinner.style.setProperty ("display" ,"inline-block", "important");
    }
function Add_Absence(spinner_Add_Absence,form_Add_Absence) {
    form_Add_Absence.style.setProperty ("display" ,"none", "important");
    spinner_Add_Absence.style.setProperty ("display" ,"inline-block", "important");
    setTimeout(function() {
        window.location.reload();
    }, 1000);
    }
function myDIV() {
    const element = document.getElementById("myGroup"); 

    let nodes = element.getElementsByClassName("forms-sample");
    for (let i = 0; i < nodes.length; i++) {
    nodes[i].style.display = "none";
    }
    let nodess = element.getElementsByClassName("Do");
    for (let i = 0; i < nodes.length; i++) {
    nodess[i].style.display = "inline-block";
    }

    }
function form_2(form_date,form_group,spin) {
    if (form_date.value.length != 0) {
        form_group.style.setProperty ("display" ,"none", "important");
        spin.style.setProperty ("display" ,"inline-block", "important");
    setTimeout(function() {
        window.location.reload();
    }, 1000);
    }
    }
function Cancel_UNCancel(Cancel_button,Cancel_form,Cancel_spinner) {
    Cancel_button.style.setProperty ("display" ,"none", "important");
    Cancel_form.style.setProperty ("display" ,"none", "important");
    Cancel_spinner.style.setProperty ("display" ,"inline-block", "important");
    setTimeout(function() {
        window.location.reload();
    }, 1000);
}
function Cancel(Cancel_form,Cancel,Cancel_spinner) {
    if (confirm('هل انت متأكد من الألغاء ؟ \n قم بألغاء الحصة اذا أجل الطالب الموعد او اعتذرت انت للطالب') == true) {
        Cancel.style.setProperty ("display" ,"none", "important");
        Cancel_spinner.style.setProperty ("display" ,"inline-block", "important");
        Cancel_form.submit();
    setTimeout(function() {
                window.location.reload();
                }, 1000);
    } else {
        return false;
    }
    }
function Dschedule(Dschedule_form,Dschedule,spin) {
    if (confirm('هل انت متأكد من الحذف ؟ ') == true) {
        Dschedule.style.setProperty ("display" ,"none", "important");
        spin.style.setProperty ("display" ,"inline-block", "important");
        Dschedule_form.submit();
    setTimeout(function() {
                window.location.reload();
                }, 1000);
    } else {
        return false;
    }
    }

function D_History(D_History_form,D_History,spin) {
    if (confirm('هل انت متأكد من الحذف ؟ ') == true) {
        D_History.style.setProperty ("display" ,"none", "important");
        spin.style.setProperty ("display" ,"inline-block", "important");
        D_History_form.submit();
    } else {
        return false;
    }
    }