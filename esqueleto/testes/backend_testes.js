var requestRefreshFunction = false; 
var urlBase = "http://localhost:8088/wm/testes/";

var reqPost = function(url, upload){
    var header = null;

    if(!upload)
        header = {'Content-Type': 'application/x-www-form-urlencoded'};
    else
        header = {'Content-Type': 'multipart/form-data'};

    return {
            method: 'POST',
            url : urlBase + url,
            headers: header,
            data: {},
            timeout : 300000,
    };
};

var urls = {       
    startTest: 'startTest.php',
};