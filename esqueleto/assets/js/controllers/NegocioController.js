angular.module('Negocio-Controller', [])

        .controller("NegocioController", ['$scope', '$state', 'Upload', 'toaster', 'ServicoData', 'ServicoRequest',
        function ($scope, $state, $upload, toaster, ServicoData, ServicoRequest) {

                $scope = ServicoData.initController($scope, $state);

                $scope.hideFaLogo = false;
                $scope.hideFaBackground = false;

                if (!seContemAlgo($scope.data.negocio)) {
                    $scope.igreja = {
                        links: [],
                        menus: [],
                        conteudos: [],
                        colaboradores: []
                    };
                } else {
                    $scope.igreja = $scope.data.negocio;
                }

                this.salvar = function () {
                    // console.log($scope.igreja);
                    // console.log($scope.data);
                    var erros = [];
                    erros = erros.concat(validaInput("Nome", $("#nome"), $scope.igreja.nome));
                    erros = erros.concat(validaInput("Denominação", $("#denominacao"), $scope.igreja.denominacao));

                    if(erros.length > 0)
                    {
                        erros = transformaErrosEmLI(erros);
                        toaster.pop('error', "Erro!", erros, null, 'trustedHtml');
                        return;
                    }

//                    if (!seContemAlgo($scope.state.igrejaIndex))
//                    {
//                        $scope.data.negocios.push($scope.igreja);
//                    } else {
//                        $scope.data.negocios[$scope.state.igrejaIndex] = $scope.igreja;
//                    }

                    var param = {
                        id: $scope.id !== undefined ? $scope.id : null,
                        nome: $scope.igreja.nome.trim(),
                        abreviacao: $scope.igreja.abreviacao,
                        denominacao: $scope.igreja.denominacao.trim(),
                        cep: $scope.igreja.cep,
                        rua: $scope.igreja.rua,
                        numero: $scope.igreja.numero,
                        bairro: $scope.igreja.bairro,
                        cidade: $scope.igreja.cidade,
                        estado: $scope.igreja.estado,
                        telefone: $scope.igreja.telefone,
                        celular: $scope.igreja.celular,
                        email: $scope.igreja.email,
                        logo: $scope.logo,
                        removerLogo: $scope.removerLogo !== undefined ? $scope.removerLogo : false ,
                        capa: $scope.capa,
                        removerCapa: $scope.removerCapa !== undefined ? $scope.removerCapa : false ,
                    };
                    var onSuccess = function (data) {
                        toaster.pop('success', "Sucesso!", data.msg, null, 'trustedHtml');

                        ServicoData.saveData($scope.data);

                        if (seContemAlgo($scope.data.negocio)) {
                            $state.go("modadm.detalhes");
                        }else{
                            $state.go("modadm.listagem");
                        }
                    };
                    ServicoRequest.requestDefault(param, onSuccess, null, $scope, $upload, ServicoData, urls.negocio.salvar);
                };

                this.addLink = function () {
                    //TODO: validar
                    $scope.igreja.links.push($scope.novoLink);
                    $scope.novoLink = "";

                    //TODO: validar ao salvar a igreja se tem algo no novoLink, se tiver predir pro cara adicionar, ou entao adicionar automatico... ou entao mudar texto para adicionar outro
                };

                this.removerLink = function (i) {
                    $scope.igreja.links.splice(i, 1);
                };

                $scope.atualizarLogo = function(){
                    $scope.hideFaLogo = true;
                    $scope.removerLogo = false;
                };
                this.removerLogo = function(){
                    $scope.hideFaLogo = false;
                    $scope.removerLogo = true;
                    $scope.logo = null;
                };
                $scope.atualizarCapa = function(){
                    $scope.hideFaCapa = true;
                    $scope.removerCapa = false;
                };
                this.removerCapa = function(){
                    $scope.hideFaCapa = false;
                    $scope.removerCapa = true;
                    $scope.capa = null;
                };

            }]);