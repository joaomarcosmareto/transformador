angular.module('Servico-Request', [])

    .factory('ServicoRequest', ['$http', 'toaster', function ($http, toaster) {
        var servico = {};
        servico.requestRefreshFunction = {};
        servico.parametrosRequest = null;
		servico.fila = [];
		servico.block = false;

        servico.onErrorSilent = function(data){
            console.log(data);
			removeAjaxToggle();
        };

        servico.onError = function(data){
            console.log(data);
            removeAjaxToggle();
            toaster.pop('error', "Erro!", mensagens.mensagemErroAjax);
        };

        servico.onProgress = function(){

        };

		servico.enfileirar = function(parametrosRequest){
			servico.fila.push(parametrosRequest);
		};

		servico.executarFila = function(){
			for(var i = 0; i < servico.fila.length; i++){
                servico.request(servico.fila[i]);
			}
            servico.fila = null;
            servico.fila = [];
		};

		servico.atualizarToken = function(novoToken){
			for(var i = 0; i < servico.fila.length; i++){
				servico.fila[i].req.headers.Authorization = novoToken;
			}
		};

        servico.montaParametrosRequest = function(parametros, onSuccess, onError, $scope, $upload, onProgress, req, silent){

            var parametrosRequest = {
                req: reqPost(req, $upload),
                angularScope: $scope,
                angularUpload: $upload,
                onProgress: onProgress,
                onSuccess: onSuccess,
                silent: silent,
                onError: (onError === null) ? silent ? servico.onErrorSilent : servico.onError : onError,
                param: parametros
            };

			return parametrosRequest;
        };

        servico.requestRefresh = function (parametrosRequest) {
            // todo 5.5.1 quando passar aqui, setar um flag pra bloquear a funcao servico.request() e bloquear inclusive esta funcao de chamar o $http
            if(!servico.block)
            {
                servico.block = true;
            }
            else
            {
                // enfileira parametrosRequest
				servico.enfileirar(parametrosRequest);
                return;
            }

            var onSuccess = function (data) {
                // console.log("voltou do servidor");
                if (data.data.sucesso === true)
                {
                    //atualiza localStorage
                    var authorization = data.headers()['authorization'];
                    if(authorization !== null && authorization !== undefined)
                        localStorage.setItem("jwt", authorization);

                    if (servico.requestRefreshFunction !== undefined)
                    {
                        // todo 5.5.4 quando passar aqui, pode liberar as outras requisicoes (parametrosRequest) que estavam enfileirados
                        // e setar o flag de bloqueio para false
                        servico.request(parametrosRequest);
						servico.atualizarToken(authorization);
                        servico.block = false;
						servico.executarFila();
                    }
                }
                else
                {
                    // todo: acertar bug dos refrehAuth
                    // servico.requestRefresh();
                }
            };
            var req = reqPost(urls.refreshToken);
            // req.data = transformRequestParam(param);
            // todo 5.5.2 quando passar aqui, validar se esta bloqueado ou nao, e
            // caso esteja bloqueado, enfileirar os parametrosRequest, pois o refresh nao precisa ser feito por demais requisicoes, apenas pela primeira.
            // $http(req).success(onSuccess).error(servico.onError);
            $http(req).then(onSuccess, servico.onError);
        };

        servico.request = function (parametrosRequest) {
            // console.log("request");
            // todo 5.5.3 quando passar aqui, validar se esta bloqueado ou nao, e
            // caso esteja bloqueado, enfileirar os parametrosRequest para serem executados após o retorno do refreshToken
			if(servico.block){
                // enfileira parametrosRequest
				servico.enfileirar(parametrosRequest);
                return;
			}
            // parametrosRequest.req.data = transformRequestParam(parametrosRequest.param);
            parametrosRequest.req.data = parametrosRequest.param;
            // servico.parametrosRequest = parametrosRequest;
            servico.requestRefreshFunction = true;

			if(seContemAlgo(parametrosRequest.angularUpload))
			{
				parametrosRequest.angularUpload.upload({
                    url: parametrosRequest.req.url,
                    method: 'POST',
                    headers: parametrosRequest.req.headers, // only for html5
                    data: parametrosRequest.param
                    //withCredentials: true,
                    //fileName: 'doc.jpg' or ['1.jpg', '2.jpg', ...] // to modify the name of the file(s)
                    //fileFormDataName: myFile, // file formData name ('Content-Disposition'), server side request form name
                    // could be a list of names for multiple files (html5). Default is 'file'
                    //formDataAppender: function(formData, key, val){}  // customize how data is added to the formData.
                    // See #40#issuecomment-28612000 for sample code
                }).progress(parametrosRequest.onProgress).then(parametrosRequest.onSuccess, parametrosRequest.onError);
			}
			else{
                $http(parametrosRequest.req).then(parametrosRequest.onSuccess, parametrosRequest.onError);
			}
        };

        /**
        O PARAMETRO BACKGROUND SERVE PARA NAO EXIBIR AJAXTOGGLE NA REQUISICAO NORMAL
        */
        servico.requestDefault = function (parametros, successFunction, notSuccessFunction, $scope, $upload, ServicoData, req, background) {

            if(!seContemAlgo(background))
                startAjaxToggle();

			var parametrosRequest = null;

            var onSuccess = function (data) {
                var authorization = data.headers()['authorization'];
                if(authorization !== null && authorization !== undefined)
                    localStorage.setItem("jwt", authorization);

                if(successFunction !== undefined && successFunction !== null)
                    successFunction(data.data, $scope, ServicoData, servico);
                removeAjaxToggle();
            };
            var notSuccess = function (data) {
                if (seContemAlgo(data) && seContemAlgo(data.data) && seContemAlgo(data.data.auth))
                    servico.requestRefresh(parametrosRequest);

                else if(notSuccessFunction !== undefined && notSuccessFunction !== null){
                    notSuccessFunction(data.data, $scope, ServicoData, servico);
                    removeAjaxToggle();
                } else {
                    if(seContemAlgo(data) && seContemAlgo(data.data) && seContemAlgo(data.data.message)){
                        toaster.pop('error', "Erro!", isJsonString(data.data.message) ? arrayStringToLI(data.data.message) : "Ops! Deu ruim!", null, 'trustedHtml');
                    }
                    else
                        toaster.pop('error', "Erro!", arrayStringToLI(['erro inesperado']), null, 'trustedHtml');
                    removeAjaxToggle();
                }
            };
			parametrosRequest = servico.montaParametrosRequest(parametros, onSuccess, notSuccess, $scope, $upload, servico.onProgress, req);
            if (simulador.navigator.isConnected()) {
				servico.request(parametrosRequest);
            }
            else {
                toaster.pop('error', "Erro!", mensagens.mensagemFaltaConectividade);
                removeAjaxToggle();
            }
        };

        servico.backgroundRequest = function (parametros, successFunction, notSuccessFunction, $scope, $upload, ServicoData, req) {

            var onSuccess = function (data) {
                if (data.data.sucesso === true) {
                    if(successFunction !== undefined && successFunction !== null)
                        successFunction(data.data, $scope, ServicoData, servico);
                }
                else {
                    if(notSuccessFunction !== undefined && notSuccessFunction !== null){
                        notSuccessFunction(data.data, $scope, ServicoData, servico);
                    }
                    else if (data.data.auth !== undefined && data.data.auth !== null){
                        servico.requestRefresh(parametrosRequest);
                    }
                }
            };
            parametrosRequest = servico.montaParametrosRequest(parametros, onSuccess, notSuccessFunction, $scope, $upload, servico.onProgress, req, true);

            if (simulador.navigator.isConnected()) {
                parametrosRequest.req.data = transformRequestParam(parametrosRequest.param);
                servico.requestRefreshFunction = true;
                $http(parametrosRequest.req).then(parametrosRequest.onSuccess, parametrosRequest.onError);
            }
        };

        return servico;

    }]);