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
                console.log("to em: " + '/modadm/new');
                return '/modadm/new';
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

    }]);