angular.module('Colaboradores-Controller', [])

        .controller("ColaboradoresController", ['$scope', '$state', 'ServicoData', function ($scope, $state, ServicoData) {

                $scope = ServicoData.initController($scope, $state);
                $scope.igreja = ServicoData.getIgreja($scope, $state);

                this.selecionar = function (i, index) {
                    $scope.state.colaboradorIndex = index;
                    ServicoData.saveState($scope.state);

                    $state.go("modadm.colaborador");
                };

                this.openAddModal = function () {
                    goToModal("#aAddModal");
                };

                this.add = function () {

                    //TODO: validações
                    var aux = {
                        id: "",
                        nome: "LALA",
                        email: $scope.email,
                        ativo: "0"
                    };
                    $scope.data.negocios[$scope.state.igrejaIndex].colaboradores.push(aux);

                    ServicoData.saveData($scope.data);
                };

                this.getStrPapel = function(i){
                    if(i === '1'){
                        return 'Redator';
                    } else if(i === '2'){
                        return 'Publicador';
                    } else if(i === '3'){
                        return 'Gestor';
                    }
                };

            }]);