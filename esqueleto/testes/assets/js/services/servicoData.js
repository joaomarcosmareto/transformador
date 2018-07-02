angular.module('Servico-Data', [])
    
    .factory('ServicoData', ['$timeout', function ($timeout) {
        var servico = {};

        servico.countNav = 0;

        servico.numeroRegistrosGrid = 20;
        servico.numeroMaxPaginasExibidas = 10;
        
        servico.serieIndex = [];
        servico.acessos = (localStorage.getItem("acessos") === null) ? [] : JSON.parse(localStorage.getItem("acessos"));
        
        servico.navBar = {};
        servico.navBar.title = titulo;
        servico.navBar.nome = (localStorage.getItem("data") === null) ? null : JSON.parse(localStorage.getItem("data")).nome;
        servico.navBar.foto36x36 = (localStorage.getItem("data") === null) ? null : JSON.parse(localStorage.getItem("data")).foto36x36;
        
        servico.fichaSelecionada = (localStorage.getItem("fichaSelecionada") == null) ? null : JSON.parse(localStorage.getItem("fichaSelecionada"));
        servico.exercicioSelecionado = (localStorage.getItem("exercicioSelecionado") == null) ? null : JSON.parse(localStorage.getItem("exercicioSelecionado"));
        servico.academiaSelecionada = (localStorage.getItem("academiaSelecionada") == null) ? null : JSON.parse(localStorage.getItem("academiaSelecionada"));
        servico.avaliacaoSelecionada = (localStorage.getItem("avaliacaoSelecionada") == null) ? null : JSON.parse(localStorage.getItem("avaliacaoSelecionada"));
        
        servico.syncComunicados = (localStorage.getItem("syncComunicados") == null) ? null : new Date(parseInt(localStorage.getItem("syncComunicados")));
        servico.syncAd = (localStorage.getItem("syncAd") == null) ? null : localStorage.getItem("syncAd");
        servico.sync = (localStorage.getItem("sync") == null) ? null : localStorage.getItem("sync");
        
        servico.tCollapse = -1;
        
        servico.showAndCreateAd = function(){
            console.log(servico.countNav);
            servico.countNav++;
            
            $timeout(function () {
                console.log("servico.countNav", servico.countNav);
                console.log("lsContNav", localStorage.getItem("countNav"));
                if(localStorage.getItem("countNav") !== null && servico.countNav == localStorage.getItem("countNav"))
                {
                    servico.countNav = 0;
                    if(AdMob){
                        var admobid = {};
                        if( /(android)/i.test(navigator.userAgent) ) { 
                            admobid = { // for Android
                                banner: 'ca-app-pub-9401353020089950/2960785718',
                                //interstitial: ''
                            };
                        }
                        AdMob.createBanner({
                            adId : admobid.banner,
                            adSize: 'SMART_BANNER',
                            position : AdMob.AD_POSITION.BOTTOM_CENTER,
                            autoShow : false,
                            isTesting: true
                        });
                        AdMob.showBanner(AdMob.AD_POSITION.BOTTOM_CENTER);
                    }
                }
                // AdMob.showBanner(AdMob.AD_POSITION.BOTTOM_CENTER);
            }, 50);
        };
        
        servico.reset = function(){
            servico.acessos = [];
            servico.syncComunicados = null;
            servico.syncAd = null;
            servico.sync = null;
        };
        
        servico.refreshTitle = function () {
            servico.navBar.title = titulo;
        };

        servico.refreshFoto = function () {
            servico.navBar.foto36x36 = foto36x36;
        };

        servico.getTitle = function () {
            return servico.navBar.title;
        };

        servico.getData = function () {
            return JSON.parse(localStorage.getItem("data"));
        };
        
        servico.get = function (key) {
            return (localStorage.getItem(key) === null) ? null : localStorage.getItem(key);
        };
        
        servico.set = function (key, data) {
            localStorage.setItem(key, data);  
        };
        
        servico.setJSON = function (key, data) {
            localStorage.setItem(key, JSON.stringify(data));  
        };

        servico.getJSON = function (data) {
            return localStorage.getItem(data) != null ? JSON.parse(localStorage.getItem(data)) : {};
        };

        servico.saveData = function (data) 
        {            
            if(data.fichas !== undefined)
            {
                for(var i = 0; i < data.fichas.length; i++)
                {
                    data.fichas[i].letrasTreinos = "";
                    for(var t = 0; t < data.fichas[i].exercicios.length; t++)
                    {
                        data.fichas[i].letrasTreinos = data.fichas[i].letrasTreinos + letras[t] + " ";
                    }
                }
            }
            
            localStorage.setItem("data", JSON.stringify(data));
            localStorage.setItem("countNav", data.countNav);
        };

        return servico;

    }]);