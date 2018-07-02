angular.module('New-Controller', [])

.controller("NewController", ['$scope', '$state', 'ServicoData', 'ServicoRequest', function ($scope, $state, ServicoData, ServicoRequest) {

        $scope = ServicoData.initController($scope, $state);

        $scope.conteudo = null;
        $scope.entidades = null;

        this.gerar = function () {
            try{
                $scope.entidades = JSON.parse($scope.conteudo);

                var param = {
                    modelo: $scope.entidades
                };
                var onSuccess = function (data) {
                    console.log("foi!");
                };
                ServicoRequest.requestDefault(param, onSuccess, null, $scope, null, ServicoData, urls.gerar);
            }
            catch(error)
            {
                console.log(error);
            }
        };

    }]);