var requestRefreshFunction = false;

var versaoApp = "web";
var versaoAppAnterior = localStorage.getItem("versaoApp");

var appAluno = false;
var appMode = "appWeb";

var urlBase = "https://§sistema§.echo.com:4430";
// var urlBase = "http://§sistema§.echo.com:8088";

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

    negocio: {
        listar: '/adm/SISTEMA/listar',
        selecionar: '/adm/SISTEMA/selecionar',
        salvar: '/adm/SISTEMA/salvar',
        remover: '/adm/SISTEMA/remover'
    },
    menu: {
        salvar: '/adm/SISTEMA/menu/salvar',
        remover: '/adm/SISTEMA/menu/remover'
    },
    categoria: {
        salvar: '/adm/SISTEMA/categoria/salvar',
        remover: '/adm/SISTEMA/categoria/remover'
    },
    conteudo: {
        listar: '/adm/SISTEMA/conteudo/listar',
        selecionar: '/adm/SISTEMA/conteudo/selecionar',
        salvar: '/adm/SISTEMA/conteudo/salvar',
        remover: '/adm/SISTEMA/conteudo/remover'
    }

};