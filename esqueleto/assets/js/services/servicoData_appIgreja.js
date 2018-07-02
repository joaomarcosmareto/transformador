angular.module('Servico-Data', [])

        .factory('ServicoData', ['$timeout', 'ServicoRequest', function ($timeout, ServicoRequest) {
                var servico = {};

                servico.numeroRegistrosGrid = 25;
                servico.listanegocios = [];

                servico.initController = function(scope, $state){
                    scope.data = servico.getData();
                    scope.$state = $state;
                    scope.ServicoData = this;
                    scope.ServicoRequest = ServicoRequest;                    

                    servico.set("currentStateName", $state.current.name);

                    return scope;
                };

                servico.saveData = function (data)
                {
                    localStorage.setItem("data", JSON.stringify(data));
                };

                servico.getData = function () {
                    if(seContemAlgo(servico.data))
                        return servico.data;
                    else{
                        var data = localStorage.getItem("data");
                        var dataZerada = {
                            negocioId: null,
                            negocio: null,
                            negocios: [],
                            menuIndex: null,
                            colaboradorIndex: null,
                            conteudoId: null,
                            categoria: null,
                        };

                        return (data === null) ? dataZerada : JSON.parse(data);
                    }
                };

                servico.get = function (key) {
                    return (localStorage.getItem(key) === null) ? null : localStorage.getItem(key);
                };

                servico.set = function (key, data) {
                    localStorage.setItem(key, data);
                };

                servico.remove = function(key){
                    localStorage.removeItem(key);
                };

                return servico;

            }]);