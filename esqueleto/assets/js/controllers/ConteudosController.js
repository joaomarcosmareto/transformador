angular.module('Conteudos-Controller', [])

        .controller("ConteudosController", ['$scope', '$state', 'ServicoData', 'ServicoRequest', 'toaster', function ($scope, $state, ServicoData, ServicoRequest, toaster) {

                $scope = ServicoData.initController($scope, $state);
        
                $scope.grid = {
                    paginaAtual: 1,
                    inicio: 0,
                    fim: ServicoData.numeroRegistrosGrid,
                    total: 0,
                    paginasArray: [],
                };                
                
                $scope.listar = function () {
                    console.log("[listar]");

                    var param = {
                        negocio:$scope.data.negocioId,
                        paginaAtual: $scope.grid.paginaAtual,
                        numRegistros: ServicoData.numeroRegistrosGrid,
                    };

                    var onSuccess = function(data, $scope, ServicoData, ServicoRequest){
                        console.log(data.itens);
                        $scope.grid.itens = data.itens;
                    };

                    ServicoRequest.requestDefault(param, onSuccess, null, $scope, null, ServicoData, urls.conteudo.listar);
                };

                if(seContemAlgo($scope.data.negocioId)){
                    $scope.listar();    
                }else{                
                    $state.go("modadm.listagem");
                }

                this.add = function () {
                    $scope.data.conteudoId = null;
                    ServicoData.saveData($scope.data);

                    $state.go("modadm.conteudo");
                };

                this.selecionar = function (id) {
                    $scope.data.conteudoId = id;
                    ServicoData.saveData($scope.data);
                    ServicoData.remove('printConteudo');
                    console.log($scope.data);
                    $state.go("modadm.conteudo");
                };

            }]);