/**
 For the Document Start Date
 */
var select = document.getElementById("day");
for(var i = 1; i <= 31; ++i) {
    var option = document.createElement('option');
    option.text = option.value = i;
    select.add(option, 0);
}
var select2 = document.getElementById("month");
for(var j = 1; j <= 12; ++j) {
    var option2 = document.createElement('option');
    option2.text = option2.value = j;
    select2.add(option2, 0);
}
var select3 = document.getElementById("year");
for(var p = 2016; p >= 1800; --p) {
    var option3 = document.createElement('option');
    option3.text = option3.value = p;
    select3.add(option3, 0);
}
/*
For the Document End Date
 */
var select4 = document.getElementById("day2");
for(var n = 1; n <= 31; ++n) {
    var option4 = document.createElement('option');
    option4.text = option4.value = n;
    select4.add(option4, 0);
}
var select5 = document.getElementById("month2");
for(var m = 1; m <= 12; ++m) {
    var option5 = document.createElement('option');
    option5.text = option5.value = m;
    select5.add(option5, 0);
}
var select6 = document.getElementById("year2");
for(var r = 2016; r >= 1800; --r) {
    var option6 = document.createElement('option');
    option6.text = option6.value = r;
    select6.add(option6, 0);
}