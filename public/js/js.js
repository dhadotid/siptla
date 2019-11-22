var format = function (num) {
    var str = num.toString().replace("$", ""), parts = false, output = [], i = 1, formatted = null;
    if (str.indexOf(",") > 0) {
        parts = str.split(",");
        str = parts[0];
    }
    str = str.split("").reverse();
    for (var j = 0, len = str.length; j < len; j++) {
        if (str[j] != ".") {
            output.push(str[j]);
            if (i % 3 == 0 && j < (len - 1)) {
                output.push(".");
            }
            i++;
        }
    }
    formatted = output.reverse().join("");
    return (formatted + ((parts) ? "," + parts[1].substr(0, 2) : ""));
};
// function format(num) {
//     var str = num.toString().replace("$", ""), parts = false, output = [], i = 1, formatted = null;
//     if (str.indexOf(".") > 0) {
//         parts = str.split(".");
//         str = parts[0];
//     }
//     str = str.split("").reverse();
//     for (var j = 0, len = str.length; j < len; j++) {
//         if (str[j] != ",") {
//             output.push(str[j]);
//             if (i % 3 == 0 && j < (len - 1)) {
//                 output.push(",");
//             }
//             i++;
//         }
//     }
//     formatted = output.reverse().join("");
//     return ("$" + formatted + ((parts) ? "." + parts[1].substr(0, 2) : ""));
// }


function hidealert()
{
    setTimeout(function () {
        $('.alert').fadeOut();
    }, 3000);
}

function notif(tp, txt) {
    new Noty({
        layout: 'topRight',
        type: tp,
        theme: 'mint',
        text: txt,
        progressBar: true,
        timeout: 3000,
    }).show();
}
