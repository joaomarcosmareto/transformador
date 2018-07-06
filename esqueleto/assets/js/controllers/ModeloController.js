§REPLACE
§For: entidades§
§File: §nome§-Controller.js
angular.module('§nome§-Controller', [])

    .controller("§nome§Controller", ['$scope', '$state', 'ServicoData', 'ServicoRequest', 'toaster', function ($scope, $state, ServicoData, ServicoRequest, toaster) {
        $scope = ServicoData.initController($scope, $state);
        
        §For: campos§
        $scope.§nome§ = "2";
        §§

        TESTE(§lFor: .entidades§);

        §For: campos | relacao§
        this.load§relacao.entidade§ = function() {

            var successFunction = function (data, $scope, ServicoData, ServicoRequest){

                $scope.§relacao.entidade§s = data.itens;
                
            };
            ServicoRequest.requestDefault(param, successFunction, null, $scope, null, ServicoData, reqPost(urls.listar§relacao.entidade§));
        };
        §§

§§