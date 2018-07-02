angular.module('Servico-Evento', [])

    .factory('ServicoEvento', ['$rootScope', '$document', function ($rootScope, $document) {
        var servico = {};

        servico.events = {
            onPause: 'onPause',
            onResume: 'onResume',
            onBackButton: 'onBackButton',
            onStateChangeSuccess: 'stateChangeSuccess',
            BotaoVoltar: 'BotaoVoltar'
        };

        servico.agenda = [];

        servico.onBackButtonModLogin = function(state) {
            if(state.current.name === 'modlogin.signinpage')
            {
                if (window.cordova) {
                    navigator.app.exitApp();
                }
            }
            else
            {
                state.go(state.current.parentState);
            }
        };

        servico.onBackButton = function(state) {

            if(state.current.name === 'modadm.listagem')
            {
                if (window.cordova) {
                    navigator.app.exitApp();
                }
            }
            
            state.go(state.$current.parentState);
        };

        $document.bind('resume', function () {
            servico.publish(servico.events.onResume, null);
        });

        $document.bind('pause', function () {
            servico.publish(servico.events.onPause, null);
        });

        $document.bind('backbutton', function () {
            servico.publish(servico.events.onBackButton, null);
        });

        servico.publish = function(eventName, data) {
            $rootScope.$broadcast(eventName, data)
        }
        servico.agendar = function(eventName, data) {
            servico.agenda.push({evento:eventName, param: data});
        };
        servico.executarAgendamento = function() {
            for(var i = 0; i < servico.agenda.length; i++){
                // console.log("executando agendamento");
                servico.publish(servico.agenda[i].evento, servico.agenda[i].param);
            }
            // por enquanto só permite um agendamento, alterar quando der...
            servico.agenda = [];
        };
        return servico;
    }]);