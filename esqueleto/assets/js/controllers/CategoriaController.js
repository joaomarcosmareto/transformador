angular.module('Categoria-Controller', [])

        .controller("CategoriaController", ['$scope', '$state', 'Upload', 'ServicoData', 'ServicoRequest', 'toaster', function ($scope, $state, $upload, ServicoData, ServicoRequest, toaster) {

                $scope = ServicoData.initController($scope, $state);

                $scope.categoria = getCategoria($scope);

                this.salvar = function () {
                    
                    var erros = [];
                    erros = erros.concat(validaInput("Nome", $("#nome"), $scope.categoria.nome));

                    if(erros.length > 0)
                    {
                        erros = transformaErrosEmLI(erros);
                        toaster.pop('error', "Erro!", erros, null, 'trustedHtml');
                        return;
                    }

                    var param = {
                        id: seContemAlgo($scope.categoria.id) ? $scope.categoria.id : null,
                        negocio_id: $scope.data.negocio.id,
                        nome: $scope.categoria.nome !== undefined ? $scope.categoria.nome.trim() : null,
                        ativo: $scope.categoria.ativo,
                    };
                    var onSuccess = function (data) {
                        toaster.pop('success', "Sucesso!", data.msg, null, 'trustedHtml');
                        
                        $scope.data.negocio = null;
                        ServicoData.saveData($scope.data);

                        $state.go("modadm.categorias");
                    };
                    ServicoRequest.requestDefault(param, onSuccess, null, $scope, null, ServicoData, urls.categoria.salvar);

                };

                this.apagar = function() {
                    goToModal("#aConfirmModal");
                };

                this.confirmar = function() {

                    if (seContemAlgo($scope.data.categoria))
                    {
                        //TODO: backend
                        
                    }

                };

            }]);