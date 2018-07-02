angular.module('App-Controller', [])

    .controller("AppController",
    ['$scope', '$rootScope', '$http', '$location', '$urlService', '$transitions',
    'toaster', 'ServicoData', 'ServicoRequest', 'ServicoEvento',
    function ($scope, $rootScope, $http, $location, $urlService, $transitions,
    toaster, ServicoData, ServicoRequest, ServicoEvento) {

        function otherwiseHandler(matchValue, url, router)
        {
            if(window.cordova && seContemAlgo(localStorage.getItem('currentStateName'))) {
                console.log("to em: " + "/"+localStorage.getItem('currentStateName').replace('.', '/'));
                return "/"+localStorage.getItem('currentStateName').replace('.', '/');
            }
            else {
                console.log("to em: " + '/modlogin/signinpage');
                return '/modlogin/signinpage';
            }
        }

        $urlService.rules.otherwise(otherwiseHandler);

        var criteriaModLogin = {to: "modlogin.**", from: "modlogin.**"};

        $transitions.onSuccess(criteriaModLogin, function($transition){
            //console.log("[state-out]: ["+$transition.$from().name+"]")
            $(".datepicker").remove();

            //console.log("[state-in]: ["+$transition.$to().name+"]")

            if($transition.$to().name === "modlogin.signinpage")
            {
                document.getElementsByTagName("html")[0].className = "bg-white";
            }
        });

        var criteria = {to: "modadm.**"};

        $transitions.onSuccess(criteria, function($transition){
            //console.log("[state-out]: ["+$transition.$from().name+"]")
            $(".datepicker").remove();

            //console.log("[state-in]: ["+$transition.$to().name+"]")

            if($transition.$to().name === "modlogin.signinpage")
            {
                document.getElementsByTagName("html")[0].className = "bg-white";
            }
            var param = {estado: $transition.$to().name};
            ServicoEvento.agendar(ServicoEvento.events.BotaoVoltar, param)
            ServicoEvento.executarAgendamento();
        });


        // $scope.autenticado = null;
        // $scope.usuario = null;

        // $scope.tab = null;

        // $rootScope.$on("verifyAuth", function(){
        //    $scope.verifyAuth();
        // });

        // $scope.verifyAuth = function() {
        //     $scope.autenticado  = ServicoData.isAutenticado();
        //     $scope.usuario      = ServicoData.getJSON("usuario");
        //     console.log($scope.usuario.permissoes);
        //     if(!$scope.autenticado)
        //         $location.path('/login');
        //     else
        //         $location.path('/home');
        // }

        // $scope.verifyAuth();

        // $scope.academias = ServicoData.listaAcademias;

        // ServicoRequest.showAjaxToggle = true;

        // this.logout = function () {
        //     var deseja_sair = simulador.navigator.notification.confirm(
        //         "Deseja realmente sair do sistema?",
        //         $scope.limparDados,
        //         "Sair do Sistema.",
        //         "Sair, Cancelar"
        //     )
        //     if(deseja_sair){ $scope.limparDados(0);}
        // };

        // $scope.limparDados = function(buttonIndex){
        //     localStorage.clear();
        //     sessionStorage.clear();
        //     $scope.verifyAuth();
        // }

        // this.click = function(item){
        //     $scope.tab = item;
        //     var body = document.getElementsByTagName("body")[0];
        //     body.className = "navbar-fixed ng-scope";
        //     $location.path('/' + item);
        // };

    }]);