Date.now = Date.now || function() { return +new Date(); };

var myTimer = null;

var mensagens = {
    mensagemAjaxStart : "Requisição em andamento. Por favor aguarde.",
    mensagemErroAjax : "Houve um erro na requisição, verifique sua conexão e tente mais tarde.",
    mensagemErroFormNaoPreenchido : "Por favor preencha todos os campos do formulário.",
    mensagemErroCampoObrigatorioNaoPreenchido : "Por favor preencha todos os campos obrigatórios do formulário.",
    mensagemErroSenhaNaoIgual : "As senhas não conferem.",
    mensagemSucessoPerfil : "Dados alterados com sucesso.",
    mensagemSucessoAcademia : "Desmatriculado da academia com sucesso.",
    mensagemSucessoFicha : "Ficha removida com sucesso.",
    mensagemSucessoCarga : "Carga alterada com sucesso.",
    mensagemSucessoAvaliacao : "Avaliacao removida com sucesso.",
    mensagemFaltaConectividade : "Você precisa estar conectado à internet para realizar esta operação.",
    mensagemDadosAtualizados : "Os dados do seu dispositivo foram atualizados com sucesso.",
    mensagemEmailMatriculaSucesso : "E-mail de matrícula enviado com sucesso para ",
    mensagemSucessoPadrao : "Dados atualizados com sucesso.",
    fichaAoMenosUmExercicio : "A ficha deve possuir ao menos um exercício.",
    emailRecuperaSenha : "Enviado o e-mail de recuperação de conta. Confira sua caixa de e-mail.",
    cadastroConfirmado : "Cadastro de usuário confirmado com sucesso.",
    senhaAlterada : "Sua senha foi alterada com sucesso.",
    alunoConvidado : "Aluno convidado com sucesso.",
    conviteRejeitado : "O convite foi rejeitado com sucesso.",
    conviteAceito : "O convite foi aceito com sucesso.",
};

var simulador = null;

var AdMob = undefined;
var salvou = false;

var removerFoto = false;
var image_data = null;

var academia = null;
var foto36x36 = null;

var indexFicha = null;
var indexTreino = null;
var indexExercicio = null;

// variável criada para indicar do armazenamento do estado do filtro da tela de
// listagem de exercícios
var voltarEx = null;

var datePattern =/^([0-9]{2})\/([0-9]{2})\/([0-9]{4})$/;

var letras = ["A","B", "C", "D", "E","F","G", "H", "I", "J","K","L", "M", "N", "O","P","Q", "R", "S", "T","U","V", "W", "X", "Y", "Z"];

ajaxToggleDelay = null
function startAjaxToggle(t){
    if(!seContemAlgo(t))
        t = 200;
    if(ajaxToggleDelay === null && document.getElementById("resultLoading") === null)
    {
        ajaxToggleDelay = setTimeout(showAjaxToggle, t);
    }
}

function showAjaxToggle(){
    if(document.getElementById("resultLoading") === null)
    {
        var divResultLoading = document.createElement("DIV");
        divResultLoading.id = "resultLoading";

        divResultLoading.innerHTML =
                '<div class="content">\n\
                    <i class="fa fa-refresh faspin" style="font-size:40px;color:white;"></i>\n\
                </div>\n\
                <div id="divBg" class="bg"></div>';

        document.getElementById("content").appendChild(divResultLoading);
    }
}

function removeAjaxToggle(){
    clearTimeout(ajaxToggleDelay);
    ajaxToggleDelay = null;
    if(document.getElementById("resultLoading") !== null)
        try{
            document.getElementById("content").removeChild(document.getElementById("resultLoading"));
        }
        catch(e){}
}



// panel toggle
$(document).on('click', '.panel-toggle', function(e){

    var $this = $(e.target), $class = 'collapse' , $target;
    //if (!$this.is('a')) $this = $this.closest('a');
    $target = $this.closest('.panel');
    $target.find('.accordeon-body').toggleClass($class);
    $this.toggleClass('active');
});

function inArray(elem,array)
{
    var len = array.length;
    for(var i = 0 ; i < len;i++) {
        if(array[i] == elem){return i;}
    }
    return -1;
}

function inArrayReg(elem,array)
{
    var len = array.length;
    var re = new RegExp("^"+elem);
    for(var i = 0 ; i < len;i++) {
        if(re.test(array[i])){return true;}
    }
    return false;
}
/*
Esta função retorna true apenas em caso de haver conteúdo útil em param, ou seja, string !== ""
*/
function seContemAlgo(param)
{
    if(typeof param === "string")
        return param !== undefined && param !== null && param != "null" && param !== "undefined" && param.trim() !== "";

    return param !== undefined && param !== null && param != "null" && param !== "undefined";
}

function booleanToInt(b){
    return b ? 1 : 0;
}

function getDataAmericana(param){
    if(!seContemAlgo(param)){
        var data = new Date();
        var dd = data.getDate();
        var mm = data.getMonth()+1;

        if(dd < 10)
            dd = "0"+dd;
        if(mm < 10)
            mm = "0"+mm;

        var yy = data.getFullYear();

        return yy+"-"+mm+"-"+dd;
    }
    else{
        var d = param.split("/")[0];
        var m = param.split("/")[1];
        var a = param.split("/")[2];
        return a+"/"+m+"/"+d;
    }
};

function getHora(){
    var data = new Date();
    var hh = data.getHours();

    if(hh < 10)
        hh = "0"+hh;
    var mi = data.getMinutes();
    if(mi < 10)
        mi = "0"+mi;
    var ss = data.getSeconds();
    if(ss < 10)
        ss = "0"+ss;

    return hh+":"+mi+":"+ss;
};

function getDataDiaBarraMes(){
    var data = new Date();
    var dd = data.getDate();
    var mm = data.getMonth()+1;
    var data_sm = dd+"/"+mm;

    return data_sm;
};

var windowY = 0;
function toTop(){
    $("html, body").animate({ scrollTop: 0 }, 200);
}

function backWindowY(){
    $("html, body").animate({ scrollTop: windowY }, 200);
}

function toBottom(){
    var Ybottom = $(document).height() - $(window).height() - 20;
    $("html, body").animate({ scrollTop: Ybottom }, 200);
}

function clone(a){
    return JSON.parse(JSON.stringify(a));
}

var onPauseF = function(){
    console.log('onPauseF');
};
var onResumeF = function(){
    console.log('onResumeF');
};

function jsonToMap(jsonStr) {
    return new Map(JSON.parse(jsonStr));
};

function getRandomIntInclusive(min, max) {
  min = Math.ceil(min);
  max = Math.floor(max);
  return Math.floor(Math.random() * (max - min + 1)) + min; //The maximum is inclusive and the minimum is inclusive
}

function getRandomInArray(arr){
    return arr[getRandomIntInclusive(0, arr.length-1)];
}

function getMensagem(i, ciclo, frases){

    var diaOvulacao = ciclo.duracao - 14; //de 10 a 16 dias, ou de 12 a 16 dias
    //TODO: dia da ovulação em parametro dentro do ciclo

    //fase pre-oculação (folicular)
    if(i < diaOvulacao){
        //se dentro do periodo menstrual
        if(i <= ciclo.menstruacao){
            //se dentro do periodo menstrual (tirando dois ultimos dias)
            if(i <= ciclo.menstruacao - 2){
                //primeiros 2 dias
                if(i <= 2){
                    return getRandomInArray(frases.menstruada.inicio);
                }else{
                    return getRandomInArray(frases.menstruada.misto);
                }
            //dois ultimos dias da menstruação
            }else{
                return getRandomInArray(frases.menstruada.fim);
            }
        }else {
            //positivo tem q subir ate o 5 no dia da ovulação
            var fimPreOvulacao = diaOvulacao -1;

            var partePreOvulacao = Math.round(fimPreOvulacao/3);

            if(i <= partePreOvulacao){
                return getRandomInArray(frases.preovulacao.inicio);
            }else if(i <= partePreOvulacao*2){
                return getRandomInArray(frases.preovulacao.misto);
            }else{
                return getRandomInArray(frases.preovulacao.fim);
            }
        }
    }else if(i === diaOvulacao){
        return getRandomInArray(frases.ovulacao);
    }
    //fase pos-oculação (lutea)
    else {
        var partePosOvulacao = Math.round((ciclo.duracao - diaOvulacao)/4);

        if(i <= diaOvulacao + partePosOvulacao){
            return getRandomInArray(frases.posovulacao.inicio);
        }else if(i <= diaOvulacao + partePosOvulacao*2){
            return getRandomInArray(frases.posovulacao.misto);
        }else if(i <= diaOvulacao + partePosOvulacao*3){
            return getRandomInArray(frases.posovulacao.misto);
        }else{
            return getRandomInArray(frases.posovulacao.fim);
        }
    }
}

//i iniciando em 1 é ideial?
function getPeriodo(i, ciclo){

    var diaOvulacao = ciclo.duracao - 14;

    //fase pre-oculação (folicular)
    if(i < diaOvulacao){
        //se dentro do periodo menstrual
        if(i <= ciclo.menstruacao){
            if(ciclo.menstruacao === 1 || (ciclo.menstruacao > 1 && i <= ciclo.menstruacao/2)){
                return ENUM_CICLO.menstruada.inicio;
            }else{
                return ENUM_CICLO.menstruada.fim;
            }
        }else {
            var fimPreOvulacao = diaOvulacao-1;
            var totalPreovulacao = fimPreOvulacao - ciclo.menstruacao;

            if(i <= ciclo.menstruacao+Math.round(totalPreovulacao/2)){
                return ENUM_CICLO.preovulacao.inicio;
            }else{
                return ENUM_CICLO.preovulacao.fim;
            }
        }
    }else if(i === diaOvulacao){
        return ENUM_CICLO.ovulacao;
    }
    //fase pos-oculação (lutea)
    else {
        var totalPosOvulacao = ciclo.duracao - diaOvulacao;

        if(i <= diaOvulacao + Math.round(totalPosOvulacao/2)){
            return ENUM_CICLO.posovulacao.inicio;
        }else{
            return ENUM_CICLO.posovulacao.fim;
        }
    }
}

//i: dia do ciclo iniciando em 0
//duracao: total de dias do ciclo
function getEstado(i, ciclo){

    var estado = {};

    //se regular
    //--------------------------------------------------------------------------
    var diaOvulacao = ciclo.duracao - 14; //de 10 a 16 dias, ou de 12 a 16 dias
    //TODO: dia da ovulação em parametro dentro do ciclo

    //fase pre-oculação (folicular)
    if(i < diaOvulacao){
        //se dentro do periodo menstrual
        if(i <= ciclo.menstruacao){
            //se dentro do periodo menstrual (tirando dois ultimos dias)
            if(i <= ciclo.menstruacao - 2){
                //primeiros 2 dias
                if(i <= 2){
                    estado.positivo = 1;
                    estado.negativo = 4;
                    estado.libido = 1;
                    estado.gula = 4;
                }else{
                    estado.positivo = 2;
                    estado.negativo = 3;
                    estado.libido = 1;
                    estado.gula = 3;
                }
            //dois ultimos dias da menstruação
            }else{
                estado.positivo = 3;
                estado.negativo = 3;
                estado.libido = 2;
                estado.gula = 3;
            }
        }else {
            //positivo tem q subir ate o 5 no dia da ovulação
            var fimPreOvulacao = diaOvulacao - 1;

            var partePreOvulacao = Math.round((fimPreOvulacao - ciclo.menstruacao)/3);

            if(i <= ciclo.menstruacao+partePreOvulacao){
                estado.positivo = 3;
                estado.negativo = 2;
                estado.libido = 3;
                estado.gula = 2;
            }else if(i <= ciclo.menstruacao+partePreOvulacao*2){
                estado.positivo = 4;
                estado.negativo = 2;
                estado.libido = 4;
                estado.gula = 2;
            }else{
                estado.positivo = 5;
                estado.negativo = 1;
                estado.libido = 5;
                estado.gula = 2;
            }
        }
    }else if(i === diaOvulacao){
        estado.positivo = 5;
        estado.negativo = 1;
        estado.libido = 5;
        estado.gula = 2;
    }
    //fase pos-oculação (lutea)
    else {
        var partePosOvulacao = Math.round((ciclo.duracao - diaOvulacao)/4);

        if(i <= diaOvulacao + partePosOvulacao){
            estado.positivo = 3;
            estado.negativo = 2;
            estado.libido = 4;
            estado.gula = 2;
        }else if(i <= diaOvulacao + partePosOvulacao*2){
            estado.positivo = 2;
            estado.negativo = 3;
            estado.libido = 3;
            estado.gula = 3;
        }else if(i <= diaOvulacao + partePosOvulacao*3){
            estado.positivo = 2;
            estado.negativo = 4;
            estado.libido = 2;
            estado.gula = 4;
        }else{
            estado.positivo = 1;
            estado.negativo = 5;
            estado.libido = 2;
            estado.gula = 5;
        }
    }

    /*

    Periodo fértil é 14 dias antes da próxima menstruação. A janela fertil é o dia provavel da ovulação + 3 e -3
    no ciclo irreguar o usuário tem q informar a janela*

     */

    //TODO: calcular ciclo irreguar
    // pessoa teria q informar o intervalo? ex: do 3 ao 17 dia? Teria q informar no edit que é ciclo irregular

    return estado;
};

//prejeta um novo ciclo a partir de uma data
function projetarCiclo(garota, mData){
    var diaOvulacao = garota.ciclo.duracao - 14; //de 10 a 16 dias, ou de 12 a 16 dias
    //TODO: dia da ovulação em parametro dentro do ciclo

    var key = null;
    for (var i = 1; i <= garota.ciclo.duracao; i++) {

        var value = {
            data: mData.format('YYYYMMDD'),
            estado: getEstado(i, garota.ciclo),
            i: i
        };
        if(i <= garota.ciclo.menstruacao)
            value.menstruada = true;
        else if(i-1 >= garota.ciclo.duracao - garota.ciclo.tpm)
            value.tpm = true;
        else if(i >= diaOvulacao-3 && i <= diaOvulacao+3){
            value.altaFertilidade = true;
        }
        if(i === diaOvulacao){
            value.ovulacao = true;
        }

        key = parseInt(mData.format('YYYYMMDD'));
        garota.calendario.set(key, value);

        mData.add(1, 'd');
    }
    garota.proxPrevisao = parseInt(mData.format('YYYYMMDD'));
};

//projeta novos ciclos a partir de uma data
function reprojetarCiclos(garota, mData){
    var intDataLimit = garota.proxPrevisao;
    var intDataStart = parseInt(mData.format('YYYYMMDD'));

    garota.proxPrevisao = intDataStart;

    while (intDataLimit > garota.proxPrevisao){
        projetarCiclo(garota, moment(garota.proxPrevisao, 'YYYYMMDD'));
    }
};

//projeta ciclos ate determinada data
function preverCicloAte(garota, mData) {
    //TODO: se tiver que prever muitos dias fazer algum tratamento, ou msg,.. pensar
    var intData = parseInt(mData.format('YYYYMMDD'));
    while (intData >= garota.proxPrevisao){
        projetarCiclo(garota, moment(garota.proxPrevisao, 'YYYYMMDD'));
    }
}

function getEventosParaODia(datas, mData){

    var eventos = [];
    var strData = mData.format('YYYYMMDD');

    for (var i = 0; i < datas.length; i++) {
        var evento = datas[i];
        if(evento.repete === 1){
            if(evento.data.substring(4, 8) === strData.substring(4, 8)){
                eventos.push(evento);
            }
        } else {
            if(evento.data === strData){
                eventos.push(evento);
            }
        }
    }

    return eventos;
}

function getRelacao(relacao){
    if (relacao === "1") {
        return "Eu";
    } else if (relacao === "2") {
        return "Namorada";
    } else if (relacao === "3") {
        return "Noiva";
    } else if (relacao === "4") {
        return "Esposa";
    } else if (relacao === "5") {
        return "Amiga";
    } else if (relacao === "6") {
        return "Parente";
    } else if (relacao === "7") {
        return "Colega";
    } else if (relacao === "8") {
        return "Outra";
    } else if (relacao === "9") {
        return "Ficante";
    }
}
function guid() {
  function s4() {
    return Math.floor((1 + Math.random()) * 0x10000)
      .toString(16)
      .substring(1);
  }
  return s4() + s4() + '-' + s4() + '-' + s4() + '-' +
    s4() + '-' + s4() + s4() + s4();
}
function mapToJson(m){
    var aux = [];
    m.forEach(function(value, key) {
        var i = [key, value];
        aux.push(i);
    });
    return JSON.stringify(aux);
}

function goToModal(id) {
    if (window.innerWidth < 1024)
        toTop();

    setTimeout(function () {
        $(id).click();
    }, 70);
}

function arrayStringToLI(str){
    var ar = null;
    if(typeof str === 'object')
        ar = str;
    else
        ar = JSON.parse(str);

    var retorno = "";

    for (var i in ar) {
        retorno += "<li>"+ar[i]+"</li>";
    }

    return retorno;
}

function isJsonString(str) {
    try {
        JSON.parse(str);
    } catch (e) {
        return false;
    }
    return true;
}

function selecionaNegocio(scope, sucessFunction){

    if(seContemAlgo(scope.data.negocioId)){
        var param = {
            id: scope.data.negocioId,
        };
        var onSuccess = function (response) {

            scope.data.negocio = response.data;
            scope.ServicoData.saveData(scope.data);

            sucessFunction(scope);
        };
        scope.ServicoRequest.requestDefault(param, onSuccess, null, scope, null, scope.ServicoData, urls.negocio.selecionar);
    }else{
        scope.$state.go("modadm.listagem");
    }
};


function getNegocio(scope, sucessFunction){
    //TODO: se não tiver data no scope, mandar para login
    if(seContemAlgo(scope.data.negocio)){
        return scope.data.negocio;
    }else{
        if(!seContemAlgo(sucessFunction)){
            sucessFunction = function (scope) {scope.negocio = scope.data.negocio;};
        }
        selecionaNegocio(scope, sucessFunction);
    }
};

function getCategoria(scope){
    getNegocio(scope, function(){});
    if(seContemAlgo(scope.data.categoria)){
        return scope.data.categoria;
    }else{
        return {ativo: '1'};
    }
};

function getMenu(scope){
    getNegocio(scope, function(){});
    if(seContemAlgo(scope.data.menu)){
        return scope.data.menu;
    }else{
        return {ativo: '1'};
    }
};

function getConteudoId(scope){
    if(seContemAlgo(scope.data) && seContemAlgo(scope.data.conteudoId)){//TODO: getConteudoId
        return scope.data.conteudoId;
    }else{
        scope.$state.go("modadm.conteudos");
    }
}

function getEditors(){
    var myinstances = [];

    for(var i in CKEDITOR.instances) {
       myinstances[CKEDITOR.instances[i].name] = CKEDITOR.instances[i];
    }
    return myinstances;
}