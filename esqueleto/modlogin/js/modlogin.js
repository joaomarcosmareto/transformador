(function () {
    angular.module('modlogin',
        ['toaster', 'Servico-Evento', 'Servico-Request', 'Servico-Data', 'Custom-Directive', 'ngAnimate', 'ui.router']
    )
    .constant('modlogin.urls', {
        CADASTRO_USUARIO: '/public/registrar',
        CONFIRMAR_CADASTRO: '/public/confirmarRegistro',
        RECUPERAR_SENHA: '/public/recuperarSenha',
        TROCAR_SENHA: '/public/alterarSenha',
        LOGIN_USUARIO: '/login',
    }).

    controller('LoginController', LoginController).

    config(['$compileProvider', '$stateProvider', function ($compileProvider, $stateProvider) {

        // a variavel parentState dentro de cada state é utilizada para indicar
        // para qual estado deve-se fazer a navegação em caso de clique do botão voltar

        $stateProvider
        .state('modlogin', {
            abstract:true,
            url: '/modlogin',
            templateUrl: 'modlogin/modlogin.html?1239'
        })
        .state('modlogin.signinpage', {
            url: '/signinpage',
            templateUrl: 'modlogin/signinpage.html?1239',
        })
        .state('modlogin.signuppage', {
            url: '/signuppage',
            templateUrl: 'modlogin/signuppage.html?1239',
            parentState: 'modlogin.signinpage'
        })
        .state('modlogin.signuppage-success', {
            url: '/signuppagesuccess/{codigo}',
            templateUrl: 'modlogin/signuppage-success.html?1239',
            parentState: 'modlogin.signuppage'
        })
        .state('modlogin.esquecisenha', {
            url: '/esquecisenha',
            templateUrl: 'modlogin/esquecisenha.html?1239',
            parentState: 'modlogin.signinpage'
        })
        .state('modlogin.trocarsenha', {
            url: '/trocarsenha/{codigo}',
            templateUrl: 'modlogin/trocarsenha.html?1239',
            parentState: 'modlogin.esquecisenha'
        })
        .state('modlogin.termos', {
            url: '/termos',
            templateUrl: 'modlogin/termos.html?1239',
            parentState: 'modlogin.signuppage'
        })
    }]);

    LoginController.$inject = [
        '$scope', '$state', '$transitions', '$stateParams', 'modlogin.urls',
        '$http', 'ServicoEvento', 'ServicoData', 'ServicoRequest', 'toaster', '$timeout'];
    function LoginController($scope, $state, $transitions, $stateParams, modlogin_urls,
        $http, ServicoEvento, ServicoData, ServicoRequest, toaster, $timeout) {

        ServicoData.remove("jwt");
        ServicoData.remove("data");

        $scope.emailLogin = "joaomarcosmareto@gmail.com";
        $scope.senhaLogin = "1234567a";

        if(versaoAppAnterior !== versaoApp)
        {
            localStorage.setItem("versaoApp", versaoApp);
            versaoAppAnterior = versaoApp;//para evitar loop quando for pra home
        }

        $scope.negocios = ServicoData.listaNegocios;

        if(!seContemAlgo(window.cordova)){

            if($state.current.name === "modlogin.signinpage")
                document.getElementById("emailLogin").focus();
            else if($state.current.name === "modlogin.signuppage")
                document.getElementById("nomeSignup").focus();
            else if($state.current.name === "modlogin.esquecisenha")
                document.getElementById("emailEsqueciSenha").focus();
            else if($state.current.name === "modlogin.trocarsenha")
                document.getElementById("novaSenha").focus();
            else if($state.current.name === "modlogin.signinConsulta")
                document.getElementById("usuarioConsulta").focus();
        }

        this.createAccount = function ($event) {
            console.log("[createAccount]");
            $event.preventDefault();

            var dt_aniversario = document.getElementById("aniversario").value.trim();

            var erros = [];

            erros = erros.concat(validaInput("Nome", $("#nomeSignup"), $scope.nomeSignup));
            erros = erros.concat(validaInput("Sobrenome", $("#sobrenome"), $scope.sobrenome));
            erros = erros.concat(validaInput("Aniversário", $("#aniversario"), dt_aniversario));
            erros = erros.concat(validaInput("Email", $("#email"), $scope.emailSignup));
            erros = erros.concat(validaInput("Senha", $("#senhaSignup"), $scope.senhaSignup));

            if(!$scope.termos)
            {
                erros = erros.concat("É preciso ler e estar de acordo com os termos de serviço.");
            }
            if(!seContemAlgo($scope.sexo))
            {
                erros = erros.concat("É preciso selecionar o sexo.");
            }

            if(erros.length > 0)
            {
                erros = transformaErrosEmLI(erros);
                toaster.pop('error', "Erro!", erros, null, 'trustedHtml');
                return;
            }

            var param = {
                nome: $scope.nomeSignup.trim(),
                sobrenome: $scope.sobrenome.trim(),
                dataNascimento: dt_aniversario,
                email: $scope.emailSignup.trim(),
                senha: $scope.senhaSignup.trim(),
                sexo: $scope.sexo,
            };

            var onSuccess = function (data) {
                toaster.pop('success', "Sucesso!", data.msg, null, 'trustedHtml');
                $state.go("modlogin.signinpage");

            };
            ServicoRequest.requestDefault(param, onSuccess, null, $scope, null, ServicoData, modlogin_urls.CADASTRO_USUARIO);
        };

        this.confirmarCadastro = function ($event) {
            console.log("[Confirmar Cadastro]");

            $event.preventDefault();

            var erros = [];

            erros = erros.concat(validaInput("Email", $("#codigo"), $scope.email));

            if(!seContemAlgo($stateParams))
            {
                erros = erros.concat("Código de cadastro não encontrado. Por favor acesse a partir do link recebido no e-mail.");
            }

            if(erros.length > 0)
            {
                erros = transformaErrosEmLI(erros);
                toaster.pop('error', "Erro!", erros, null, 'trustedHtml');
                return;
            }

            var param = {
                codigo: $stateParams.codigo.trim(),
                email: $scope.email
            };

            var onSuccess = function (data) {
                localStorage.removeItem("emailCadastro");
                toaster.pop('success', "Sucesso!", mensagens.cadastroConfirmado);
                $state.go("modlogin.signinpage");
            };
            ServicoRequest.requestDefault(param, onSuccess, null, $scope, null, ServicoData, modlogin_urls.CONFIRMAR_CADASTRO);
        };

        this.login = function ($event) {
            console.log("[login]");
            $event.preventDefault();

            var erros = [];

            erros = erros.concat(validaInput("Email do usuário", $("#emailLogin"), $scope.emailLogin));
            erros = erros.concat(validaInput("Senha", $("#senhaLogin"), $scope.senhaLogin));

            if(erros.length > 0)
            {
                erros = transformaErrosEmLI(erros);
                toaster.pop('error', "Erro!", erros, null, 'trustedHtml');
                return;
            }

            var param = {
                email: $scope.emailLogin.trim(),
                senha: $scope.senhaLogin.trim()
            };

            var onSuccess = function (data) {
                console.log(data.data);

                ServicoData.saveData(data.data);

                ServicoEvento.agendar(
                    ServicoEvento.events.onAlterarPerfil,
                    {
                        foto:data.foto36x36,
                        nome:data.nome,
                        agendamento: true
                    }
                );

                ServicoData.listanegocios = data.data.negocios;
                $state.go("modadm.listagem");

            };
            ServicoRequest.requestDefault(param, onSuccess, null, $scope, null, ServicoData, modlogin_urls.LOGIN_USUARIO);
        };

        this.recuperarSenha = function ($event) {
            console.log("[Recuperar Senha]");

            $event.preventDefault();

            var erros = [];

            erros = erros.concat(validaInput("Email", $("#emailEsqueciSenha"), $scope.emailEsqueciSenha));

            if(erros.length > 0)
            {
                erros = transformaErrosEmLI(erros);
                toaster.pop('error', "Erro!", erros, null, 'trustedHtml');
                return;
            }

            var param = {
                email: $scope.emailEsqueciSenha.trim(),
            };

            var onSuccess = function (data) {
                localStorage.setItem("emailRecuperar", $scope.emailEsqueciSenha.trim());
                toaster.pop('success', "Sucesso!", mensagens.emailRecuperaSenha);
                $state.go("modlogin.signinpage");
            };
            ServicoRequest.requestDefault(param, onSuccess, null, $scope, null, ServicoData, modlogin_urls.RECUPERAR_SENHA);
        };

        this.trocarSenha = function ($event) {
            console.log("[Trocar Senha]");
            $event.preventDefault();

            var erros = [];

            erros = erros.concat(validaInput("Nova Senha", $("#novaSenha"), $scope.novaSenha));
            erros = erros.concat(validaInput("Confirmar Senha", $("#novaSenha2"), $scope.novaSenha2, "Nova Senha", $scope.novaSenha));

            if(!seContemAlgo($stateParams))
            {
                erros = erros.concat("Código de troca de senha não encontrado. Por favor acesse a partir do link recebido no e-mail.");
            }

            if(erros.length > 0)
            {
                erros = transformaErrosEmLI(erros);
                toaster.pop('error', "Erro!", erros, null, 'trustedHtml');
                return;
            }

            var param = {
                novasenha: $scope.novaSenha.trim(),
                confirmsenha: $scope.novaSenha2.trim(),
                codigo: $stateParams.codigo.trim(),
                email: localStorage.getItem("emailRecuperar"),
            };

            var onSuccess = function (data) {
                localStorage.removeItem("emailRecuperar");
                toaster.pop('success', "Sucesso!", mensagens.senhaAlterada);
                $state.go("modlogin.signinpage");
            };

            ServicoRequest.requestDefault(param, onSuccess, null, $scope, null, ServicoData, modlogin_urls.TROCAR_SENHA);
        };

        this.novoCadastro = function(){
            $state.go("modlogin.signinpage");
        };

        this.cancelar = function(){
            ServicoEvento.onBackButtonModLogin($state);
        }

        $scope.$on(ServicoEvento.events.onBackButton, function (event) {
            ServicoEvento.onBackButtonModLogin($state);
        });

        $scope.$on(ServicoEvento.events.onPause, function () {
            console.log('onPause ModLogin');
            //ServicoData.setJSON("state", ServicoData.state);
        });

        $scope.$on(ServicoEvento.events.onResume, function () {
            console.log('onResume ModLogin');
            //ServicoData.state = ServicoData.getState();
        });


        removeAjaxToggle();
    }

})();