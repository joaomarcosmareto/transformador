angular.module('NavBar-Controller', [])

    .controller("NavBarController", [
        '$scope', '$state', '$transitions', 'ServicoEvento', 'ServicoData',
        function ($scope, $state, $transitions, ServicoEvento, ServicoData) {

        $scope.navBar = ServicoData.navBar;

        $scope.hideVoltar = $state.current.name === 'modadm.new';

        this.voltar = function () {
            ServicoEvento.onBackButton($state);
        };

        $scope.$on(ServicoEvento.events.onBackButton, function (event) {
            ServicoEvento.onBackButton($state);
        });

        $scope.$on(ServicoEvento.events.onPause, function () {
            console.log('onPause NavBar');
            //ServicoData.setJSON("state", ServicoData.state);
        });

        $scope.$on(ServicoEvento.events.onResume, function () {
            console.log('onResume NavBar');
            //ServicoData.state = ServicoData.getState();
        });

        this.fechar = function(){
            navigator.app.exitApp();
        };

        $scope.botaoVoltar = function(){
            $scope.hideVoltar = $state.current.name === 'modadm.new';
        };

        $scope.$on(ServicoEvento.events.BotaoVoltar, function (event, param) {
            $scope.botaoVoltar();
        });

        // $transitions.onSuccess({}, function($transitions){
        //     $scope.hideVoltar = $transitions.$to().name === 'modadm.listagem';

        //     if($transitions.$to().name === "modadm.listagem"){
        //         $scope.titulo = "Igrejas";
        //     }
        //     else if($transitions.$to().name === "mod.edit"){
        //         $scope.titulo = "Cadastro";
        //     }
        //     else if($transitions.$to().name === "mod.eu"){
        //         $scope.titulo = "Meu perfil";
        //     }
        //     else{
        //         $scope.titulo = "";
        //     }
        // });

    }]);