var requestRefreshFunction = false;

var versaoApp = "web";
var versaoAppAnterior = localStorage.getItem("versaoApp");

var appAluno = false;
var appMode = "appWeb";

//var urlBase = "https://gerador.echo.com:4430";
var urlBase = "http://localhost:8088/transformador";

var appNegocioId = null;

var reqPost = function(url, upload){
    var header = null;

    if(!upload){
        header = {'Content-Type': 'application/json'};
        if(localStorage.getItem("jwt") !== null)
            header.Authorization = localStorage.getItem("jwt");
    }
    else{
        header = {'Content-Type': 'multipart/form-data'};
        if(localStorage.getItem("jwt") !== null)
            header.Authorization = localStorage.getItem("jwt");
    }

    return {
            method: 'POST',
            url : urlBase + url,
            headers: header,
            data: {},
            timeout : 300000,
    };
};


var urls = {

    refreshToken: '/refreshAuth',

    gerar: '/index.php',

};