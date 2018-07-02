angular.module('Custom-Directive', [])

//    .directive("gridFilterDefault", function () {
//        return {
//            templateUrl: "assets/directives/gridFilterHeader.html",
//            restrict: "E"
//        };
//    })

    .directive("gridFooter", function () {
        return {
            //templateUrl: "assets/directives/gridFilterFooter.html",
            template: '\n\
                        <div class="center" ng-class="{\'invisivel\' : hideLoad }" style="padding: 5px;background-color: rgb(245,246,247);">\n\
                            <i class="fa fa-refresh faspin" style="font-size:20px;color:white;"></i>\n\
                        </div>\n\
                        <footer class="panel-footer">\n\
                            <div class="row text-center">\n\
                                <small class="text-muted inline m-t-small m-b-small">\n\
                                    Exibindo <span ng-bind="grid.inicio"></span> a <span ng-bind="grid.fim"></span> de <span ng-bind="grid.total"></span> registros.\n\
                                </small>\n\
                            </div>\n\
                            <div class="row text-center" ng-class="{\'invisivel\' : grid.paginas === 1}">\n\
                                <ul class="pagination pagination-small m-t-none m-b-none">\n\
                                    <li class="pointer" ng-class="grid.paginaAtual == 1 ? \'disabled no-events\' : \'enabled\'">\n\
                                        <a ng-click="voltarUmNoGrid()">\n\
                                            <i class="fa fa-chevron-left"></i>\n\
                                        </a>\n\
                                    </li>\n\
                                    <li class="pointer bold" ng-repeat="p in grid.paginasArray track by $index" ng-class="grid.indexAtual == {{$index + 1}} ? \'disabled\' : \'enabled\'">\n\
                                        <a ng-click="irParaIndiceGrid(p, $index+1)" ng-class="grid.indexAtual == {{$index + 1}} ? \'gray\' : \'enabled\'">{{p}}</a>\n\
                                    </li>\n\
                                    <li class="pointer" ng-class="grid.paginaAtual == grid.paginas ? \'disabled no-events\' : \'enabled\'">\n\
                                        <a ng-click="avancarUmNoGrid()" >\n\
                                            <i class="fa fa-chevron-right"></i>\n\
                                        </a>\n\
                                    </li>\n\
                                </ul>\n\
                            </div>\n\
                        </footer>',
            restrict: "E",
            controller: ['$scope', 'ServicoData', function ($scope, ServicoData) {
                $scope.irParaIndiceGrid = function (indice){
                    $scope.grid.paginaAtual = indice;
                    $scope.grid.inicio = ($scope.grid.paginaAtual - 1) * ServicoData.numeroRegistrosGrid;
                    $scope.grid.fim = $scope.grid.inicio + (ServicoData.numeroRegistrosGrid);
                    $scope.listar();
                    if($scope.grid.fim > $scope.grid.total)
                        $scope.grid.fim = $scope.grid.total;
                };

                $scope.voltarUmNoGrid = function (){
                    $scope.irParaIndiceGrid(--$scope.grid.paginaAtual);
                };

                $scope.avancarUmNoGrid = function (){
                    $scope.irParaIndiceGrid(++$scope.grid.paginaAtual);
                };
            }]
        };
    })

