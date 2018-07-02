angular.module('Colaborador-Controller', [])

        .controller("ColaboradorController", ['$scope', '$state', 'ServicoData', function ($scope, $state, ServicoData) {

                $scope = ServicoData.initController($scope, $state);
                $scope.colaborador = ServicoData.getColaborador($scope, $state);

                this.salvar = function () {
                    //TODO: validação e backend

                    $scope.data.negocios[$scope.state.igrejaIndex].colaboradores[$scope.state.colaboradorIndex] = $scope.colaborador;

                    ServicoData.saveData($scope.data);

                    $state.go("modadm.colaboradores");
                };

                this.apagar = function() {
                    goToModal("#aConfirmModal");
                };

                this.confirmar = function() {

                    //TODO: backend
                    $scope.data.negocios[$scope.state.igrejaIndex].colaboradores.splice($scope.state.colaboradorIndex, 1);

                    ServicoData.saveData($scope.data);

                    $state.go("modadm.colaboradores");

                };

            }]);