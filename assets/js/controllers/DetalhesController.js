angular.module('Detalhes-Controller', [])

    .controller("DetalhesController", ['$scope', '$state', 'ServicoData', 'ServicoRequest', function ($scope, $state, ServicoData, ServicoRequest) {

        $scope = ServicoData.initController($scope, $state);
        
        $scope.negocio = getNegocio($scope);        
        console.log($scope.negocio);

}]);