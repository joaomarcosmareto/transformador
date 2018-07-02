angular.module('Pdf-Controller', [])

    .controller("PdfController", 
    ['$scope', "$timeout", function ($scope, $timeout) {

        $scope.avaliacao = conteudo;
        $scope,suprimirMarinha = false;
        $scope,suprimirVazios = false;        
        
        this.carregaGraficos = function()
        {            
            $scope.optionsComposicao = {
                series: {
                    pie: { 
                        show: true,
                        //radius: 3/4,
                        label: {
                            show: true,
                            formatter: labelFormatter,
                            background: { 
                                opacity: 0.55,
                                color: '#000'
                            }
                        },
                    },
                },
                legend: {show: false}
            };
            
            $scope.dataComposicao = [];
            if(seContemAlgo($scope.avaliacao.composicao.gorduraAtual))
            {
                var auxMassaGorda = $scope.avaliacao.composicao.massaGorda;
                var auxMassaMagra = $scope.avaliacao.composicao.massaMagra;

                $scope.dataComposicao.push({label: "Gordo", data: auxMassaGorda, color: '#d9534f'});                
                $scope.dataComposicao.push({label: "Magro", data: auxMassaMagra, color: '#13c4a5'});

                if(seContemAlgo($scope.avaliacao.composicao.pesoOsseo))
                {
                    var auxPesoOsseo = $scope.avaliacao.composicao.pesoOsseo;
                    $scope.dataComposicao.push({label: "Ósseo", data: auxPesoOsseo, color: '#C9D1CA'});
                }   

                if(seContemAlgo($scope.avaliacao.composicao.pesoResidual))
                {
                    var auxPesoResidual = $scope.avaliacao.composicao.pesoResidual;
                    $scope.dataComposicao.push({label: "Residual", data: auxPesoResidual, color: '#5191d1'});            
                }
                
                $scope.showGrafico = true;
            }
            else
            {
                $scope.showGrafico = false;
            }
        };
        this.carregaGraficos();
        
        $scope.ajustaDados = function(){
            if(seContemAlgo($scope.avaliacao))
            {
                if($scope.avaliacao.composicao.protocolo === 1)
                    $scope.avaliacao.composicao.protocolo = "Pollock - 3 Dobras";
                if($scope.avaliacao.composicao.protocolo === 2)
                    $scope.avaliacao.composicao.protocolo = "Pollock - 7 Dobras";
                if($scope.avaliacao.composicao.protocolo === 3)
                    $scope.avaliacao.composicao.protocolo = "Faulkner - 4 Dobras";
                if($scope.avaliacao.composicao.protocolo === 4)
                    $scope.avaliacao.composicao.protocolo = "Guedes - 3 Dobras";
                if($scope.avaliacao.composicao.protocolo === 5)
                    $scope.avaliacao.composicao.protocolo = "Manual/Balança";
            }
        };
        $scope.ajustaDados();
        
        
    }])
    
    .filter("kg", function() {
        return function(n) {
            if(seContemAlgo(n) && n !== null && n !== undefined){
                console.log(n);
                return NumericToString(n) + " kg";
            }
        };
    })
    
    .filter("percentual", function() {
        return function(n) {
            if(seContemAlgo(n))
                return NumericToString(n) + " %";
        };
    })
    
    .filter("numericToString", function() {
        return function(n) {
            return NumericToString(n);
        };
    })
;