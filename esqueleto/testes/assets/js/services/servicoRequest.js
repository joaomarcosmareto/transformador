angular.module('Servico-Request', [])

    .factory('ServicoRequest', ['ServicoData', '$http', '$timeout', function (ServicoData, $http, $timeout) {
        var servico = {};
        servico.requestRefreshFunction = {};
        servico.salt = null;
        servico.parametrosRequest = null;
        servico.showAjaxToggle = true;

        servico.getPrivateToken = function () {
            var d = ServicoData.getData();
            if(d !== null && d.auth !== undefined)
                return d.auth.privateToken;
            else return null;
        };

        servico.getPublicToken = function () {
            var d = ServicoData.getData();
            if(d !== null && d.auth !== undefined)
                return d.auth.publicToken;
            else return null;
        };

        servico.getRefreshToken = function () {
            var d = ServicoData.getData();
            if(d !== null && d.auth !== undefined)
                return d.auth.refreshToken;
            else return null;
        };

        servico.getSalt = function () {
            return servico.salt;
        };

        servico.generateNewSalt = function () {
            servico.salt = "123";
        };

        servico.getHash = function () {
            servico.generateNewSalt();
            if(servico.getPrivateToken() !== null)
                return sha1(servico.getSalt() + servico.getPrivateToken());
            return null;
        };

        servico.onError = function(data, status, headers, config){
            console.log(data);
            console.log(status);
            console.log(headers);
            console.log(config);
            removeAjaxToggle();
            Console.log("Erro!", mensagens.mensagemErroAjax);
        };
        
        servico.onProgress = function(){

        };

        servico.requestRefresh = function () {
            var param = {
                hash: servico.getHash(),
                timestamp: servico.getSalt(),
                refreshToken: servico.getRefreshToken()
            };

            var onSuccess = function (data) {
                //console.log("voltou do servidor");
                if(servico.showAjaxToggle)
                    ajaxToggle();

                if (data.sucesso === true)
                {
                    //atualiza localStorage
                    var localData = JSON.parse(localStorage.getItem("data"));
                    localData.auth.publicToken = data.auth.publicToken;
                    localStorage.setItem("data", JSON.stringify(localData));

                    if (servico.requestRefreshFunction !== undefined && servico.requestRefreshFunction !== false)
                    {
                        servico.parametrosRequest.param.publicToken = data.auth.publicToken;
                        console.log("voltou do servidor");
                        servico.requestRefreshFunction(servico.parametrosRequest);
                        servico.requestRefreshFunction = false;
                    }
                }
                else
                {
                    //todo: acertar bug dos refrehAuth
                    //servico.requestRefresh();
                }
            };
            var req = reqPost(urls.refreshToken);
            req.data = transformRequestParam(param);
            if(servico.showAjaxToggle)
                ajaxToggle();
            $http(req).success(onSuccess).error(servico.onError);
        };

        servico.requestAndUpload = function (parametrosRequest) {
            servico.parametrosRequest = parametrosRequest;

            servico.requestRefreshFunction = function (parametrosRequest) {
                if(servico.showAjaxToggle)
                    ajaxToggle();
                parametrosRequest.angularScope.upload = parametrosRequest.angularUpload.upload({
                    url: parametrosRequest.req.url, // upload.php script, node.js route, or servlet url
                    method: 'POST',
                    //headers: {'Authorization': 'xxx'}, // only for html5
                    //withCredentials: true,
                    data: parametrosRequest.param,
                    file: parametrosRequest.file_file, // single file or a list of files. list is only for html5
                    file_logo: parametrosRequest.file_logo, // single file or a list of files. list is only for html5
                    file_foto1: parametrosRequest.file_foto1, // single file or a list of files. list is only for html5
                    file_foto2: parametrosRequest.file_foto2, // single file or a list of files. list is only for html5
                    file_foto3: parametrosRequest.file_foto3, // single file or a list of files. list is only for html5
                    file_foto4: parametrosRequest.file_foto4, // single file or a list of files. list is only for html5
                    //fileName: 'doc.jpg' or ['1.jpg', '2.jpg', ...] // to modify the name of the file(s)
                    //fileFormDataName: myFile, // file formData name ('Content-Disposition'), server side request form name
                    // could be a list of names for multiple files (html5). Default is 'file'
                    //formDataAppender: function(formData, key, val){}  // customize how data is added to the formData. 
                    // See #40#issuecomment-28612000 for sample code
                }).progress(parametrosRequest.onProgress).success(parametrosRequest.onSuccess).error(parametrosRequest.onError);
            };
            servico.requestRefreshFunction(parametrosRequest);
        };

        servico.request = function (parametrosRequest) {
            //console.log("request");
            parametrosRequest.req.data = transformRequestParam(parametrosRequest.param);
            servico.parametrosRequest = parametrosRequest;
            servico.requestRefreshFunction = servico.request;
            if(servico.showAjaxToggle)
                ajaxToggle();
            $http(parametrosRequest.req).success(parametrosRequest.onSuccess).error(parametrosRequest.onError);
        };
        
        servico.requestDefault = function (parametros, successFunction, notSuccessFunction, $scope, $upload, ServicoData, req) {
            
            var parametrosAuth = {
                hash: servico.getHash(),
                timestamp: servico.getPublicToken() === null ? null : servico.getSalt(),
                publicToken: servico.getPublicToken()
            };
            
            //concatena os jsons
            var props = Object.keys(parametrosAuth);            
            for (var i = 0; i < props.length; i++) {
                parametros[props[i]] = parametrosAuth[props[i]];
            }
            
            var onSuccess = function (data) {
                if(servico.showAjaxToggle)
                    ajaxToggle();

                if (data.sucesso === true)
                {
                    if(successFunction !== undefined && successFunction !== null)
                        successFunction(data, $scope, ServicoData, servico);
                }
                else
                {
                    if(notSuccessFunction !== undefined && notSuccessFunction !== null)
                        notSuccessFunction(data, $scope, ServicoData, servico);
                    
                    if (data.erro !== undefined && data.erro !== null)
                        Console.log("Erro!", data.erro);
                    else if (data.auth !== undefined && data.auth !== null){
                        servico.requestRefresh();
                    }
                }
            };
            
            var onProgress = function (evt) {
                //console.log(evt);
                //console.log('progress: ' + parseInt(100.0 * evt.loaded / evt.total) + '% file :'+ evt.config.file.name);
            };

            parametrosRequest = {
                req: req,
                angularScope: $scope,
                angularUpload: $upload,
                file_file: file_file,
                file_logo: file_logo,
                file_foto1: file_foto1,
                file_foto2: file_foto2,
                file_foto3: file_foto3,
                file_foto4: file_foto4,
                param: parametros,
                onProgress: onProgress,
                onSuccess: onSuccess,
                onError: servico.onError
            };
            
            if($upload == null)
                servico.request(parametrosRequest);
            else
                servico.requestAndUpload(parametrosRequest);
        };        
        
        servico.backgroundRequest = function (parametros, successFunction, notSuccessFunction, $scope, $upload, ServicoData, req) {
            
            var parametrosAuth = {
                hash: servico.getHash(),
                timestamp: servico.getSalt(),
                publicToken: servico.getPublicToken()
            };
            
            //concatena os jsons
            var props = Object.keys(parametrosAuth);            
            for (var i = 0; i < props.length; i++) {
                parametros[props[i]] = parametrosAuth[props[i]];
            }
            
            var onSuccess = function (data) {
                if (data.sucesso === true)
                {
                    if(successFunction !== undefined && successFunction !== null)
                        successFunction(data, $scope, ServicoData, servico);
                }
                else
                {
                    if (data.auth !== undefined && data.auth !== null){
                        servico.requestRefresh();
                        return;
                    }
                    if(notSuccessFunction !== undefined && notSuccessFunction !== null)
                        notSuccessFunction(data, $scope, ServicoData, servico);
                }
            };
            
            var onProgress = function (evt) {};

            var parametrosRequest = {
                req: req,
                angularScope: $scope,
                param: parametros,
                onProgress: onProgress,
                onSuccess: onSuccess,
                onError: notSuccessFunction
            };
            
            parametrosRequest.req.data = transformRequestParam(parametrosRequest.param);
            servico.parametrosRequest = parametrosRequest;
            servico.requestRefreshFunction = servico.request;
            if (simulador.navigator.isConnected())
                $http(parametrosRequest.req).success(parametrosRequest.onSuccess).error(parametrosRequest.onError);
        };
        
        return servico;
        
    }]);