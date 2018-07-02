angular.module('Menus-Controller', [])

    .controller("MenusController", ['$scope', '$state', 'ServicoData', 'ServicoRequest', function ($scope, $state, ServicoData, ServicoRequest) {

        $scope = ServicoData.initController($scope, $state);

        $scope.negocio = getNegocio($scope);
        console.log($scope.negocio);

        $scope.filtro_titulo = null;
        $scope.filtro_categoria = null;

        this.add = function () {
            $scope.data.menu = null;
            ServicoData.saveData($scope.data);

            $state.go("modadm.menu");
        };

        this.selecionar = function (i) {
            $scope.data.menu = i;
            ServicoData.saveData($scope.data);

            $state.go("modadm.menu");
        };
    }]);