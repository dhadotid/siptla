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


function formatRupiah(angka, prefix){
	var number_string = angka.toString().replace(/[^,\d]/g, '').toString(),
	split   		= number_string.split(','),
	sisa     		= split[0].length % 3,
	rupiah     		= split[0].substr(0, sisa),
	ribuan     		= split[0].substr(sisa).match(/\d{3}/gi);
 
	// tambahkan titik jika yang di input sudah menjadi angka ribuan
	if(ribuan){
		separator = sisa ? '.' : '';
		rupiah += separator + ribuan.join('.');
	}
 
	rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
	return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
}