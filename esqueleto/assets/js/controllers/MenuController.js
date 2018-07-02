angular.module('Menu-Controller', [])

    .controller("MenuController", ['$scope', '$state', 'ServicoData', 'ServicoRequest', 'toaster', function ($scope, $state, ServicoData, ServicoRequest, toaster) {
        $scope = ServicoData.initController($scope, $state);

        $scope.negocio = getNegocio($scope);
        $scope.menu = getMenu($scope);
        $scope.conteudo = null;

        $scope.selectedIndex = null;

        this.salvar = function () {

            var erros = [];
            erros = erros.concat(validaInput("Titulo", $("#titulo"), $scope.menu.titulo));

            if($scope.menu.tipo < 1)
            {
                erros = erros.concat("Por favor selecione o tipo do menu.");
            }

            if(($scope.menu.tipo === 1 || $scope.menu.tipo === 2) && $scope.menu.categoria === "")
            {
                erros = erros.concat("Por favor selecione a categoria do conteúdo.");
            }

            if($scope.menu.tipo === 4 && $scope.menu.conteudo === "")
            {
                erros = erros.concat("Por favor selecione o conteúdo.");
            }

            if($scope.menu.tipo === 4 && $scope.menu.link === "")
            {
                erros = erros.concat("Por favor informe o link.");
            }

            if($scope.menu.tipo === 5 && $scope.menu.biblia === "")
            {
                erros = erros.concat("Por favor selecione o tipo de bíblia.");
            }

            if(erros.length > 0)
            {
                erros = transformaErrosEmLI(erros);
                toaster.pop('error', "Erro!", erros, null, 'trustedHtml');
                return;
            }

            var param = {
                id: seContemAlgo($scope.menu.id) ? $scope.menu.id : null,
                negocio_id: $scope.data.negocio.id,
                titulo: $scope.menu.titulo !== undefined ? $scope.menu.titulo.trim() : null,
                icone: $scope.menu.icone,
                tipo: $scope.menu.tipo,
                categoria_id: $scope.menu.categoria,
                conteudo_id: $scope.menu.conteudo !== undefined ? $scope.menu.conteudo.id : undefined,
                link: $scope.menu.link !== undefined ? $scope.menu.link.trim() : null,
                biblia: $scope.menu.biblia
            };
            var onSuccess = function (data) {
                toaster.pop('success', "Sucesso!", data.msg, null, 'trustedHtml');

                $scope.data.negocio = null;
                ServicoData.saveData($scope.data);
                $state.go("modadm.categorias");
            };
            ServicoRequest.requestDefault(param, onSuccess, null, $scope, null, ServicoData, urls.menu.salvar);
        };

        this.apagar = function() {
            goToModal("#aConfirmModal");
        };

        this.exibeModalConteudo = function() {
            goToModal("#aConteudoModal");
        };

        $scope.listarConteudos = function(){
            console.log("[listar-conteudos]");

            if($scope.filtro_titulo === undefined || $scope.filtro_titulo === null || $scope.filtro_titulo.trim() === "")
            {
                if($scope.filtro_categoria === undefined || $scope.filtro_categoria === null || $scope.filtro_categoria.trim() === "")
                {
                    $scope.conteudos = [];
                    return;
                }
            }

            var param = {
                negocio:$scope.data.negocioId,
                paginaAtual: 0,
                numRegistros: 0,
                filtro_titulo: $scope.filtro_titulo,
                filtro_categoria: $scope.filtro_categoria
            };

            var onSuccess = function(data, $scope, ServicoData, ServicoRequest){

                $scope.conteudos = data.itens;
            };

            ServicoRequest.requestDefault(param, onSuccess, null, $scope, null, ServicoData, urls.conteudo.listar);
        }

        $scope.desmarcarItemModal = function()
        {
            $scope.conteudo = null;
            console.log("opa", "desmarcar");
        }

        $scope.confirmarItemModal = function()
        {
            $scope.menu.conteudo = $scope.conteudo;
            $scope.conteudo = null;
            console.log("opa", "confirmar");
        }

        $scope.toggleItemModal = function(item, index)
        {
            $scope.conteudo = item;
            // se não tem item selecionado, seleciona o index
            if($scope.selectedIndex === null)
            {
                $scope.selectedIndex = index;

                $('#list-group-item-'+index)[0].className = 'list-group-item bla active';
            }
            // se estou clicando no mesmo item, tira a seleção dele
            else if($scope.selectedIndex !== null && $scope.selectedIndex === index)
            {
                $scope.selectedIndex = null;
                $('#list-group-item-'+index)[0].className = 'list-group-item bla';
            }
            // se estou selecionando um outro item, desseleciona o anterior e seleciona o ultimo
            else if($scope.selectedIndex !== null && $scope.selectedIndex !== index)
            {
                $('#list-group-item-'+$scope.selectedIndex)[0].className = 'list-group-item bla';

                $scope.selectedIndex = index;
                $('#list-group-item-'+index)[0].className = 'list-group-item bla active';
            }
        }

        this.confirmar = function() {

            if (seContemAlgo($scope.data.categoria))
            {
                //TODO: backend

            }

        };

    }]);