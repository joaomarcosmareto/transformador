angular.module('Listagem-Controller', [])
.controller("ListagemController", ['$scope', '$state', 'ServicoData', 'ServicoRequest',
function ($scope, $state, ServicoData, ServicoRequest) {

    $scope = ServicoData.initController($scope, $state, ServicoData, ServicoRequest);
    $scope.grid = {
        paginaAtual: 1,
        inicio: 0,
        fim: ServicoData.numeroRegistrosGrid,
        total: 0,
        paginasArray: [],
    };

    console.log($scope.data);

    this.add = function () {
        $scope.data.negocioId = null;
        ServicoData.saveData($scope.data);

        $state.go("modadm.edit");
    };

    this.selecionar = function (i) {
        $scope.data.negocioId = i._id;
        ServicoData.saveData($scope.data);

        selecionaNegocio($scope, function($scope){$scope.$state.go("modadm.detalhes")});
    };

    $scope.listar = function () {
        console.log("[listar]");

        var param = {
            paginaAtual: $scope.grid.paginaAtual,
            numRegistros: ServicoData.numeroRegistrosGrid,
        };

        var onSuccess = function(data, $scope, ServicoData, ServicoRequest){
            console.log(data.itens);
            $scope.grid.itens = data.itens;
        };

        var onFail = function(){
            $scope.grid.itens = $scope.data.negocios;
        };

        if (simulador.navigator.isConnected()) {
            ServicoRequest.requestDefault(param, onSuccess, onFail, $scope, null, ServicoData, urls.negocio.listar, silent = true);
        }
        else{
            onFail();
        }
    };

}]);