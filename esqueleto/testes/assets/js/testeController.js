angular.module('Teste-Controller', [])

    .controller("TesteController", 
    ['$scope', '$http', 'ServicoData', 'ServicoRequest', '$timeout', function ($scope, $http, ServicoData, ServicoRequest, $timeout) {
        localStorage.clear();
        ServicoRequest.showAjaxToggle = false;
                
        $scope.arrServicoDetalheShow = [];
        
        $scope.arrTesteShow = [];
        
        $scope.servicos = [];
        
        $scope.hideLoad = false;
        $scope.gifload = urlBase+"assets/img/ajax-loader.gif";
        
        this.startTestes = function(){

            var param = {};

            var successFunction = function (data, $scope, ServicoData, ServicoRequest){
                console.log(data);
                $scope.hideLoad = true;
                $scope.servicos = data.servicos;
                
                $scope.totalSuccess = data.totalSuccess;
                $scope.totalFail = data.totalFail;
            };
            
            var notSuccessFunction = function (data, $scope, ServicoData, ServicoRequest){
                console.log(data);
                $scope.hideLoad = true;
            };

            ServicoRequest.requestDefault(param, successFunction, notSuccessFunction, $scope, null, ServicoData, reqPost(urls.startTest));
        };
        
        
    }]);