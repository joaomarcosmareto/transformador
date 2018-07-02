angular.module('Conteudo-Controller', [])

        .controller("ConteudoController", ['$scope', '$state', '$timeout', 'ServicoData', 'ServicoRequest', 'toaster', function ($scope, $state, $timeout, ServicoData, ServicoRequest, toaster) {

                $scope = ServicoData.initController($scope, $state);
        
                $scope.negocio = getNegocio($scope);
                console.log($state.current.name);

                //TODO: botar o height do editor de acordo com o tamanho da folha do modelo
                
                $scope.modelosPagina = [
                    {
                        id: "A4-LAND-3PF",
                        nome: "A4 (3 págs. / folha)",
                        pagina: "21cm 29.7cm landscape",
                        margens: "margin: 0cm",
                        cssBox: {"width": "9.83cm", "height": "20.9cm", "background-color": "white"},
                        editors: [
                            {css:{"margin": "0mm 2mm 0mm 0mm", "height": "19cm"}},
                            {css:{"margin": "0mm 2mm 0mm 2mm", "height": "19cm"}},
                            {css:{"margin": "0mm 0mm 0mm 2mm", "height": "19cm"}}
                        ],
                    },
                ];
                
                $scope.getModeloPaginaById = function(id){
                    var max = $scope.modelosPagina.length;
                    
                    for (var i = 0; i < max; i++) 
                    {
                        if(id === $scope.modelosPagina[i].id)
                            return $scope.modelosPagina[i];
                    }
                };                
                
                $scope.setPageSize = function(){
                    var m = $scope.getModeloPaginaById($scope.conteudo.modelo);
                    
                    var x = m.pagina.split(' ')[0];
                    var y = m.pagina.split(' ')[1];
                    if(m.pagina.split(' ')[2] === 'landscape')
                        $('#pagina').css({'maxWidth': y});
                    else
                        $('#pagina').css({'maxWidth': x});
                    
                    console.log(m.pagina);
                    $('head').append('<style>@page {size: '+m.pagina+'; '+m.margens+';}</style>');  
                };

                $scope.ajustaPaginasParaLoad = function(){

                    $scope.setPageSize();

                    var max = $scope.conteudo.paginas.length;
                    
                    $scope.showLoad = true;
                    
                    setTimeout(function(){
                            
                        for (var i = 0; i < max; i++) {
                            var aux = i+1;
                            $('#box'+aux).css($scope.conteudo.paginas[i].cssBox);
                            $('#editor'+aux).css($scope.conteudo.paginas[i].cssEditor);
                            
                            try{
                                var editor = CKEDITOR.inline('editor'+aux, {filebrowserBrowseUrl: "browser?id="+$scope.data.negocioId});

                                editor.setData($scope.conteudo.paginas[i].data, {callback: function () {
                                    this.checkDirty();
                                }});
                            }catch (e) {}

                        }

                        $scope.showLoad = false;
                        $scope.$apply(function(){});

                    }, 50*$scope.conteudo.paginas.length);//todo: botar mais tempo e por um load melhor
                };

                $scope.load = function(){

                    if(seContemAlgo($scope.data.negocioId) && seContemAlgo($scope.data.conteudoId)){
                        var param = {
                            id: $scope.data.conteudoId,
                            negocio: $scope.data.negocioId,
                        };
                        var onSuccess = function (response) {
                            $scope.conteudo = response;
                            $scope.conteudo.paginas = JSON.parse($scope.conteudo.paginas);
                            //console.log($scope.conteudo);
                            $scope.ajustaPaginasParaLoad();
                        };
                        $scope.ServicoRequest.requestDefault(param, onSuccess, null, $scope, null, $scope.ServicoData, urls.conteudo.selecionar);
                    }else{
                        $scope.$state.go("modadm.conteudos");
                    }
                };
                
                if(seContemAlgo(ServicoData.get('printConteudo'))){
                    $scope.conteudo = JSON.parse(ServicoData.get('printConteudo'));
                    $scope.ajustaPaginasParaLoad();
                }else{
                    if(!seContemAlgo($scope.data.conteudoId)){
                    $scope.conteudo = {
                        id: null,
                        publicado: "0",
                        modelo: "0",
                        categoria: "",
                        paginas:[]
                    };
                    }else {
                        $scope.load();
                    }
                }
    
                //if(!seContemAlgo($scope.conteudo.id)){
                    //$scope.conteudo.titulo = "asdasd";
                    //$scope.conteudo.categoria = $scope.negocio.categorias[0].id;
                //}
                
                console.log($scope);
                $scope.showLoad = false;
                var editor0 = CKEDITOR.replace('editor0', {filebrowserBrowseUrl: "browser?id="+$scope.data.negocioId});

                if($state.current.name === 'conteudo'){
                    $('#content').addClass('pre-print');
                    $('body').removeClass('body_top');
                    $('div').removeClass('padder2');
                    $('.col-xs-12').addClass('padding-none');
                                        
                    $scope.setPageSize();
                }

                this.imprimir = function () {                    
                    if($state.current.name !== 'conteudo'){
                        $scope.ajustaPaginasParaSalvar();
                        ServicoData.set('printConteudo', JSON.stringify($scope.conteudo));
                        //$state.go("conteudo");//TODO: trocar para abrir nova aba com endereço correto
                        var url = $state.href('conteudo');
                        window.open(url,'_blank');
                    }else{
                        window.print();
                        window.close();
                        //TODO: fechar a janela quando estiver em nova aba
                    }                        
                };

                this.salvar = function () {
                    
                    var erros = [];
                    erros = erros.concat(validaInput("Título", $("#titulo"), $scope.conteudo.titulo));

                    if(erros.length > 0)
                    {
                        erros = transformaErrosEmLI(erros);
                        toaster.pop('error', "Erro!", erros, null, 'trustedHtml');
                        return;
                    }

                    $scope.ajustaPaginasParaSalvar();

                    var param = {
                        id: seContemAlgo($scope.data.conteudoId) ? $scope.data.conteudoId : null,
                        negocio: $scope.data.negocioId,
                        titulo: $scope.conteudo.titulo,
                        publicado: $scope.conteudo.publicado,
                        categoria: $scope.conteudo.categoria,
                        modelo: $scope.conteudo.modelo,
                        paginas: JSON.stringify($scope.conteudo.paginas),
                        conteudo: editor0.getData(),
                    };
                    var onSuccess = function (data) {
                        toaster.pop('success', "Sucesso!", data.msg, null, 'trustedHtml');

                        ServicoData.saveData($scope.data);

                        $state.go("modadm.conteudos");
                    };
                    
                    ServicoRequest.requestDefault(param, onSuccess, null, $scope, null, ServicoData, urls.conteudo.salvar);

                };

                $scope.ajustaPaginasParaSalvar = function(){
                    
                    var editors = getEditors();                    
                    var max = $scope.conteudo.paginas.length;
                    
                    for (var i = 0; i < max; i++) {
                        var aux = i+1;
                        $scope.conteudo.paginas[i].data = editors["editor"+aux].getData();
                    }
                };

                this.changeModelo = function(){
                    //TODO:mostrar msg q vai apagar tudo ou entao tentar reaproveitar
                    //$scope.conteudo.paginas = [];
                    console.log($scope.conteudo);
                    if(!seContemAlgo($scope.conteudo.paginas) || $scope.conteudo.paginas.length === 0){
                        $scope.addFolha();
                        $scope.setPageSize();
                    }else if($scope.conteudo.modelo !== "0"){
                        $scope.ajustaPaginasParaLoad();
                    }
                };
                
                $scope.getModelo = function(){
                    if(!seContemAlgo($scope.modelo)){
                        $scope.modelo = $scope.getModeloPaginaById($scope.conteudo.modelo);
                    }
                    return $scope.modelo;
                };
                
                $scope.isBreak = function($index){
                    var m = $scope.getModelo();

                    if(seContemAlgo(m)){
                        return $index%m.editors.length === m.editors.length-1 && $index !== $scope.conteudo.paginas.length-1;
                    }
                    return false;
                };
                                
                $scope.showPageHeader = function($index){
                    var m = $scope.getModelo();

                    if(seContemAlgo(m)){
                        return $index%m.editors.length === 0;
                    }
                    return false;
                };
                
                $scope.addFolha = function(){                    

                    var modelo = $scope.getModelo();
                    if(!seContemAlgo(modelo)){
                        return;
                        //TODO: erro?
                    }
                    
                    var totalBox = modelo.editors.length;

                    var max = $scope.conteudo.paginas.length;
                        
                    if(!seContemAlgo($scope.conteudo.paginas)){
                        $scope.conteudo.paginas = [];
                        max = 0;
                    }
                    
                    for (var i = max; i < max+totalBox; i++) 
                    {
                        $scope.conteudo.paginas.push({
                            cssBox: modelo.cssBox,
                            cssEditor: modelo.editors[i%totalBox].css,
                            data: ""
                        });
                    }

                    $scope.showLoad = true;

                    setTimeout(function(){

                        for (var i = max; i < max+totalBox; i++) {

                            var aux = i+1;
                            $('#box'+aux).css($scope.conteudo.paginas[i].cssBox);
                            $('#editor'+aux).css($scope.conteudo.paginas[i].cssEditor);

                            CKEDITOR.inline('editor'+aux, {filebrowserBrowseUrl: "browser?id="+$scope.data.negocioId});                                    
                        }

                        $scope.showLoad = false;
                        $scope.$apply(function(){});

                    }, 100);//todo: botar mais tempo e por um load melhor

                };
                
                $scope.removerFolha = function(index){
                    
                    if (window.confirm("Realmente deseja continuar e remover essa folha?")) { 

                        var editors = getEditors(); 

                        var totalEditorsPorPagina = $scope.modelo.editors.length;
                        var paginaIndex = index/$scope.modelo.editors.length+1;
                        var paginaMax = $scope.conteudo.paginas.length/$scope.modelo.editors.length+1;

                        //se não for a ultima
                        if(paginaIndex < paginaMax && paginaMax > 2){
                            for (var i = paginaIndex; i < paginaMax-1; i++) {
                                var aux = (i-1)*totalEditorsPorPagina;
                                var aux2 = aux+totalEditorsPorPagina;
                                editors['editor'+aux].setData(editors['editor'+aux2].getData());
                                aux++;
                                aux2 = aux+totalEditorsPorPagina;
                                editors['editor'+aux].setData(editors['editor'+aux2].getData());
                                aux++;
                                aux2 = aux+totalEditorsPorPagina;
                                editors['editor'+aux].setData(editors['editor'+aux2].getData());
                            }

                            $scope.conteudo.paginas.splice(index, $scope.modelo.editors.length);
                        }

                        if(paginaMax === 2){
                            editors['editor1'].setData("");
                            editors['editor2'].setData("");
                            editors['editor3'].setData("");                        
                        }
                    }
                };

                this.apagar = function() {
                    goToModal("#aConfirmModal");
                };

                this.confirmar = function() {

                    if (seContemAlgo($scope.data.conteudoIndex))
                    {
                        //TODO: backend
                        $scope.data.igrejas[$scope.data.igrejaIndex].conteudos.splice($scope.data.conteudoIndex, 1);

                        ServicoData.saveData($scope.data);

                        $state.go("modadm.conteudos");
                    }

                };

            }]);