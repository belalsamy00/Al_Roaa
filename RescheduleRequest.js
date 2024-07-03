function Status(classID) {
  var Student = document.getElementById("Student").value;
  var Date = document.getElementById("Date").value;
  var MainDate = document.getElementById("MainDate").value;
  var Time = document.getElementById("Time").value;
  var classID = classID;
  if (
    Student.length != 0 &&
    Date.length != 0 &&
    MainDate.length != 0 &&
    Time.length != 0
  ) {
    $.ajax({
      type: "get",
      url: "RescheduleRequestApi.php",
      data: {
        Student: Student,
        Date: Date,
        MainDate: MainDate,
        Time: Time,
        classID: classID,
      },
      cache: false,
      success: function (Data) {
        console.log(Data);
        var Data = JSON.parse(Data);
        let content = Data;
        let Permission = content.Permission;
        let Massage = content.Massage;
        let Rescheduled = content.Rescheduled;
        let RescheduledItem = ``;
        if (Rescheduled != 0 ) {      
          for (let i = 0; i < Rescheduled.length; i++) {
            RescheduledItem += Rescheduled[i] + " </br> ";
          }
        }
        var  Alert = `
        ${Massage}
        ${RescheduledItem}
        `
        document.getElementById("Alert").innerHTML = Alert
        var elem = document.getElementById("Submit")
        if (Permission == 'Allowed') {
          elem.style.opacity = "";
          elem.style.cursor = "";
          elem.style.pointerEvents = "";
          elem.disabled = false;
        }else{
          elem.style.setProperty("opacity", "0.4", "important");
          elem.style.cursor = "not-allowed";
          elem.style.pointerEvents = "unset";
          elem.disabled = true;
        }
      },
    });
  }
}
