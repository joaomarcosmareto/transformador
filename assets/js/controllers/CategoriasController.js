angular.module('Categorias-Controller', [])

        .controller("CategoriasController", ['$scope', '$state', 'ServicoData', 'ServicoRequest', function ($scope, $state, ServicoData, ServicoRequest) {

                $scope = ServicoData.initController($scope, $state);
                
                $scope.negocio = getNegocio($scope);
                console.log($scope.negocio);

                this.add = function () {
                    $scope.data.categoria = null;
                    ServicoData.saveData($scope.data);

                    $state.go("modadm.categoria");
                };

                this.selecionar = function (i) {
                    $scope.data.categoria = i;
                    ServicoData.saveData($scope.data);

                    $state.go("modadm.categoria");
                };

            }]);