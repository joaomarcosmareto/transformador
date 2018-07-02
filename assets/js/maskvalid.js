
var validadores = [
                    {
                        nome: "validationfieldrequired",
                        errorMessage: function(val){
                            return "Campo obrigatório: " + val + ".";
                        },
                        validator: function(val) {
                            if("undefined" === typeof val || val === null)
                                return false;
                            return val.trim() != "";
                        }
                    },
                    {
                        nome: "validationminlength",
                        errorMessage: function(val, attr){
                            return "Campo deve ter no mínimo " + attr + " caracteres: " + val + ".";
                        },
                        validator: function(val, attr) {
                            if("undefined" === typeof val || val === null)
                                return true;
                            return val.length >= parseInt(attr);
                        }
                    },
                    {
                        nome: "validationmaxlength",
                        errorMessage: function(val, attr){
                            return "Campo deve ter no máximo " + attr + " caracteres: " + val + ".";
                        },
                        validator: function(val, attr) {
                            if("undefined" === typeof val || val === null)
                                return true;
                            return val.length <= parseInt(attr);
                        }
                    },
                    {
                        nome: "validationdateformat",
                        errorMessage: function(val){
                            return "Data inválida: " + val + ".";
                        },
                        validator: function(val) {
                            // o if abaixo foi adicionado para passar caso a data seja em branco, assim
                            // esse validador deve obrigatoriamente ser usado em conjunto com o validador de field required ( o primeiro validador).
                            if(val.trim() === "")
                                return true;
                            if("undefined" === typeof val || val === null)
                                return true;

                            if(val.split("/").length === 3 && val.length === 10){

                                var y = val.split("/")[2];
                                var m = val.split("/")[1];
                                var d = val.split("/")[0];
                                var dataInformada = new Date( y + "/" + m + "/" + d);

                                if("Invalid Date" === dataInformada.toString())
                                    return false;
                                return true;
                            }
                            return false;
                        }
                    },
                    {
                        nome: "validationdatenotbeforetoday",
                        errorMessage: function(val){
                            return "A data não deve ser anterior a hoje: " + val + ".";
                        },
                        validator: function(val) {
                            if("undefined" === typeof val || val === null)
                                return true;
                            var hoje = new Date();
                            hoje.setHours(0);hoje.setMinutes(0);hoje.setSeconds(0);hoje.setMilliseconds(0);

                            var y = val.split("/")[2];
                            var m = val.split("/")[1];
                            var d = val.split("/")[0];
                            var dataInformada = new Date( y + "/" + m + "/" + d);

                            return dataInformada >= hoje;
                        }
                    },
                    {
                        nome: "validationdatebeforetoday",
                        errorMessage: function(val){
                            return "A data deve ser anterior a hoje: " + val + ".";
                        },
                        validator: function(val) {
                            if("undefined" === typeof val || val === null)
                                return true;
                            var hoje = new Date();
                            hoje.setHours(0);hoje.setMinutes(0);hoje.setSeconds(0);hoje.setMilliseconds(0);

                            var y = val.split("/")[2];
                            var m = val.split("/")[1];
                            var d = val.split("/")[0];
                            var dataInformada = new Date( y + "/" + m + "/" + d);

                            return dataInformada < hoje;
                        }
                    },
                    {
                        nome: "validationdateequalafter",
                        errorMessage: function(val, value){
                            return "A data deve ser posterior a " + value + ": " + val + ".";
                        },
                        validator: function(val, val2) {
                            var y = val.split("/")[2];
                            var m = val.split("/")[1];
                            var d = val.split("/")[0];

                            var date = new Date( y + "/" + m + "/" + d);

                            y = val2.split("/")[2];
                            m = val2.split("/")[1];
                            d = val2.split("/")[0];

                            var date2 = new Date( y + "/" + m + "/" + d);

                            return date >= date2;
                        }
                    },
                    {
                        nome: "validationdateafter",
                        errorMessage: function(val, value){
                            return "A data deve ser posterior a " + value + ": " + val + ".";
                        },
                        validator: function(val, val2) {
                            var y = val.split("/")[2];
                            var m = val.split("/")[1];
                            var d = val.split("/")[0];

                            var date = new Date( y + "/" + m + "/" + d);

                            y = val2.split("/")[2];
                            m = val2.split("/")[1];
                            d = val2.split("/")[0];

                            var date2 = new Date( y + "/" + m + "/" + d);

                            return date > date2;
                        }
                    },
                    {
                        nome: "validationconfirmpassword",
                        errorMessage: function(val, value){
                            return "As senhas devem ser iguais";
                        },
                        validator: function(val, val2) {
                            return val === val2;
                        }
                    },
                    {
                        nome: "validationemail",
                        errorMessage: function(val){
                            return "Por favor informe um email válido: " + val;
                        },
                        validator: function(val) {
                            return /^.*@.*\..*[a-z]$/i.test(val)
                        }
                    },
                    {
                        nome: "validationConfirmPassword",
                        errorMessage: "Senhas devem ser iguais.",
                        validator: function(errorMessageElement, val, attr, element, model) {
                            var password = model.password || "";
                            return password.replace(/\s+$/, "") === element.val().replace(/\s+$/, "")
                        }
                    },
                    {
                        nome: "validationEmail",
                        errorMessage: "Por favor informe um email válido.",
                        validator: function(errorMessageElement, val) {
                            return /^.*@.*\..*[a-z]$/i.test(val)
                        }
                    },
                    {
                        nome: "validationNoSpace",
                        errorMessage: "Não pode conter espaços.",
                        validateWhileEntering: !0,
                        validator: function(errorMessageElement, val) {
                            return "" !== val && /^[^\s]+$/.test(val)
                        }
                    },
                    {
                        nome: "validationOnlyAlphabets",
                        errorMessage: "Apenas letras.",
                        validateWhileEntering: !0,
                        validator: function(errorMessageElement, val) {
                            return /^[a-z]*$/i.test(val)
                        }
                    },
                    {
                        nome: "validationoneuppercaseletter",
                        errorMessage: function(val){
                            return "Deve conter ao menos uma letra maiúscula: " + val + ".";
                        },
                        validator: function(val) {
                            return /^(?=.*[A-Z]).+$/.test(val)
                        }
                    },
                    {
                        nome: "validationonelowercaseletter",
                        errorMessage: function(val){
                            return "Deve conter ao menos uma letra minúscula: " + val + ".";
                        },
                        validator: function(val) {
                            return /^(?=.*[a-z]).+$/.test(val)
                        }
                    },
                    {
                        nome: "validationonenumber",
                        errorMessage: function(val){
                            return "Deve conter ao menos um número: " + val + ".";
                        },
                        validator: function(val) {
                            return /^(?=.*[0-9]).+$/.test(val)
                        }
                    },
                    {
                        nome: "validationOneAlphabet",
                        errorMessage: "Deve conter ao menos uma letra.",
                        validator: function(errorMessageElement, val) {
                            return /^(?=.*[a-z]).+$/i.test(val)
                        }
                    },
                    {
                        nome: "validationonealphabet",
                        errorMessage: function(val){
                            return "Deve conter ao menos uma letra: " + val + ".";
                        },
                        validator: function(val) {
                            return /^(?=.*[a-z]).+$/i.test(val)
                        }
                    },
                    {
                        nome: "validationNoSpecialChars",
                        validateWhileEntering: !0,
                        errorMessage: "Apenas letras e números.",
                        validator: function(errorMessageElement, val) {
                            return /^[a-z0-9_\-\s]*$/i.test(val)
                        }
                    },
                    {
                        nome: "validationDateBeforeToday",
                        errorMessage: "A data deve ser anterior a hoje.",
                        validator: function(errorMessageElement, val) {
                            var now, dateValue;
                            return now = new Date, dateValue = new Date(val), dateValue.setDate(dateValue.getDate() + 1), now > dateValue
                        }
                    },
                    {
                        nome: "validationDateBefore",
                        errorMessage: function(attr) {
                            return "A data deve ser antes de " + getValidationAttributeValue(attr)
                        },
                        validator: function(errorMessageElement, val, beforeDate) {
                            var dateValue = new Date(val);
                            return dateValue.setDate(dateValue.getDate() + 1), dateValue < new Date(beforeDate)
                        }
                    },{
                        nome: "validationDateAfter",
                        errorMessage: function(attr) {
                            return "A data deve ser depois de " + getValidationAttributeValue(attr)
                        },
                        validator: function(errorMessageElement, val, afterDate) {
                            var dateValue = new Date(val);
                            return dateValue.setDate(dateValue.getDate() + 1), dateValue.setHours(0), dateValue > new Date(afterDate)
                        }
                    }
                ];

function obtemAtributosValidacao(atributos_do_input){
    var r = [];
    for(var i in atributos_do_input){
        if(atributos_do_input[i].name && atributos_do_input[i].name.search("validation") > -1){
            r.push(atributos_do_input[i]);
        }
    }
    if(r.length > 0 )
        return r;
    return null;
}
function obtemValidadores(atributos_do_input){
    var r = [];
    for(var i in atributos_do_input){
        for(var j in validadores){
            if(validadores[j].nome.search(atributos_do_input[i].name) > -1){
                r.push(validadores[j]);
            }
        }
    }
    if(r.length > 0 )
        return r;
    return null;
}

function transformaErrosEmLI(erros){
    var rdi = "<ul>";
    count_1 = erros.length;

    for(var i = 0; i < count_1; i++){
        rdi = rdi + "<li>" + erros[i] + "</li>";
    }
    rdi = rdi + "</ul>";
    erros = rdi;
    return erros;
}

function highLight(element){
    if(element.className.search("inputFail") === -1)
        element.className = element.className + " inputFail";
}
function unHighLight(element){
    if(element.className.search("inputFail") > -1)
        element.className = element.className.replace(" inputFail", "");
}

function validaInput(nomeCampo, element, value, nomeCampoComparator, valueComparator)
{
    var erros = [];
    var atributos_validacao = obtemAtributosValidacao(element[0].attributes);
    for(var i in atributos_validacao){
        for(var j in validadores){
            if(validadores[j].nome.search(atributos_validacao[i].name) > -1){
                if(atributos_validacao[i].value == ""){

                    if(nomeCampoComparator){
                        if(!validadores[j].validator(value, valueComparator)){
                            erros.push(validadores[j].errorMessage(nomeCampo, valueComparator));
                            highLight(element[0]);
                        }
                    }
                    else{
                        if(!validadores[j].validator(value)){
                            erros.push(validadores[j].errorMessage(nomeCampo));
                            highLight(element[0]);
                        }
                    }
                }
                else if(atributos_validacao[i].value !== "")
                {
                    if(!validadores[j].validator(value, atributos_validacao[i].value)){
                        erros.push(validadores[j].errorMessage(nomeCampo, atributos_validacao[i].value));
                        highLight(element[0]);
                    }
                }
            }
        }
    }
    if(erros.length === 0)
    {
        unHighLight(element[0]);
    }
    return erros;
}
function stringReplaceAll(string, search, replacement){
    return string.split(search).join(replacement);
}
function retornaNumeral(string){
    // a string tem que ser formatada no
    // modelo de moeda brasileiro.
    // EX: 29.811,56
    // EX: 29811,56
    var r = null;
    if(!seContemAlgo(string))
        return 0;
    r = stringReplaceAll(string, ".", "");
    r = r.replace(",", ".");
    r = parseFloat(r);
    return r;
}
function desformataReal(string, mask){
    var retorno = null;
    if(!mask){
        string = stringReplaceAll(string, ".", "");
        string = string.replace(",", ".");
        return parseFloat(string);
    }
    else
    {
        string = stringReplaceAll(string, ".", "");
        string = string.replace(",", "");
        return string;
    }
}

function formataRealNaVolta(string, mask){
    var retorno = "";
    if(string === undefined || string === null){
        return "0,00";
    }
    // verifica se string é do tipo string
    if(typeof string !== "string"){
        string = string.toFixed(2);
    }

    var inteiro = "";
    var decimal = "";

    if(!mask){
        // troca ponto decimal por vírgula e
        // separa a parte inteira do decimal
        string = string.replace(".",",");
        inteiro = string.split(",")[0];
        decimal = string.split(",")[1];

        if(!seContemAlgo(decimal))
        {
            decimal = "00";
        }
    }
    // se esta funcao tiver sendo chamada a partir de uma mascara,
    // o modo de obter a parte inteira e decimal é um pouco diferente
    if(mask){
        // precisa ter no minio 3 caracteres, senao só tem decimal
        if(string.length < 3){
            inteiro = "0";
            if(string.length == 1){
                decimal = "0"+string;
            }
            else if(string.length == 2){
                decimal = string;
            }
        }
        else
        {
            decimal = string[string.length-2] + string[string.length-1];
            inteiro = string.slice(0, -2)
        }
    }
    // console.log("inteiro", inteiro);
    // console.log("decimal", decimal);
    // stripa o sinal de negativo se houver...
    var sinal = (inteiro.substring(0,1) === "-") ? "-" : "";
    if(sinal === "-")
    {
        inteiro = inteiro.substring(1, inteiro.length);
    }

    //obtem um array da string separada a cada 3 caracteres partindo do último em direção ao primeiro
    var array = [];
    var a = getParts(array, inteiro, 3);
    // console.log("a",a);

    // se tiver mais de um grupo, coloca ele na ordem inversa, pois foi obtido do ultimo para o primeiro
    // senao, coloca o grupo único mesmo
    if(a.length > 1){
        for(var i = a.length -1; i >= 0; i --)
        {
            retorno += a[i] + ".";
        }
        retorno = retorno.substring(0, retorno.length -1);
    }
    else{
        retorno += a[0];
    }
    // console.log("retorno", retorno, "decimal", decimal);
    return sinal + retorno + "," + decimal;
}

function getParts(array, string, partitionSize)
{
    // console.log("array",array, "string", string);
    var len = string.length;
    if(len > 3){
        array = array.concat(string.slice(-partitionSize));
        if(len - partitionSize <= 3){
            array = array.concat(string.substring(0,(string.length-1) - 2));
        }
        else{
            array = getParts(array, string.substring(0,(string.length-1) - 2), partitionSize);
        }
    }
    else{
        return array = array.concat(string);
    }
    return array;
}
function StringToNumeric(n){
    if(typeof n == "string")
        return Number(n.replace(",","."));
    else if(typeof n == "number")
        return n;
}
function NumericToString(n){
    if(n){
        var a = "" + n;
        return a.replace(".",",");
    }
    return null;
}
function isNumeric(n){
    return seContemAlgo(n) && StringToNumeric(n);
}

function labelFormatter(label, series) {
    return "<div style='font-size:8pt; text-align:center; padding:2px; color:white;'>" + label + "<br/><b>" + series.percent.toFixed(2) + "%</b></div>";
//    return "<div style='font-size:8pt; text-align:center; padding:2px;color:black;'><b>" + Math.round(series.percent) + "%</b></div>";
}

function goToNextInput(event, tabindex){
    var code = event.keyCode;
    if(code == 13){
        var inputs = document.getElementsByTagName("input");
        for(var i = 0; i < inputs.length; i++) {//loop through each element
            for(var j = 0; j < inputs[i].attributes.length; j++){//loop through each element attributes
                if(inputs[i].attributes[j].name == "seq"){
                    if(inputs[i].attributes[j].value == tabindex){
                        inputs[i].focus(); //if it's the one we want, focus it and exit the loop
                        break;
                    }
                }
            }
        }
    }
}




function prepareFormat(value, tipo){
    var v = value;

    if(!seContemAlgo(v))
        return "";

    if(tipo === "number")
        v = v.replace(/\D/g,'');

    return v;
}

function maskData(value){
    var v = prepareFormat(value, "number");

    if(v.length === 8){
        v = v.substring(0, 2) + "/" + v.substring(2, 4) + "/" + v.substring(4, 8);
    }
    else if(v.length !== 10){
        v = "";
    }

    if(v.split("/").length === 3 && v.length === 10){
        var y = v.split("/")[2];
        var m = v.split("/")[1];
        var d = v.split("/")[0];
        var data = new Date( y + "/" + m + "/" + d);
        if("Invalid Date" === data.toString())
            v = "";
        if (data.getFullYear() != y || data.getMonth()+1 != m || data.getDate() != d) {
            v = "";
        }
    }
    return v;
}

function maskCep(value){
    var v = prepareFormat(value, "number");

    if(v.length === 8){
        v = v.substring(0, 5) + "-" + v.substring(5, 8);
    }
    else if(v.length !== 9){
        v = "";
    }
    return v;
}

function maskCel(value){
    var v = prepareFormat(value, "number");

    if(v.length === 11){
        v = "(" + v.substring(0, 2) + ") " + v.substring(2, 7) + "-" + v.substring(7, 11);
    }
    else if(v.length !== 15){
        v = "";
    }
    return v;
}

function maskFone(value){
    var v = prepareFormat(value, "number");

    if(v.length === 10){
        v = "(" + v.substring(0, 2) + ") " + v.substring(2, 6) + "-" + v.substring(6, 10);
    }
    else if(v.length !== 14){
        v = "";
    }
    return v;
}

function format(v, tipo){
    if(v === null) return null;

    var aux = "";
    if(tipo === 'cel'){aux = maskCel(v);}
    if(tipo === 'fone'){ aux = maskFone(v);}
    if(tipo === 'cep'){aux = maskCep(v);}
    if(tipo === 'data'){aux = maskData(v);}
    if(tipo === 'money'){
        // console.log("v", v);
        if(seContemAlgo(v))
            aux = formataRealNaVolta(v, true);
        else
            aux = "";
    }
    // console.log(aux);
    return aux;
}

function unformat(v, tipo){
    if(v === null) return null;

    var aux = "";
    if(tipo === 'cel'){
        if(v.length === 15){
            aux = v.substring(1, 3) + v.substring(5, 10) + v.substring(11, 15);
        }
    }
    if(tipo === 'fone'){
        if(v.length === 14){
            aux = v.substring(1, 3) + v.substring(5, 9) + v.substring(10, 14);
        }
    }
    if(tipo === 'cep'){// 29150-270
        if(v.length === 9){
            aux = v.substring(0, 5) + v.substring(6, 9);
        }
    }
    if(tipo === 'data'){// 00/00/0000
        if(v.length === 10){
            aux = v.substring(0, 2) + v.substring(3, 5) + v.substring(6, 10);
        }
    }
    if(tipo === 'money'){
        aux = desformataReal(v, true);
    }
    return aux;
}



function maskIn(element, tipo){
    element.value = format(element.value, tipo);
}

function maskOut(element, tipo){
    element.value = unformat(element.value, tipo);
}

function maxsize(element, tipo){
    if(tipo === 'cel'){
        if(element.value.length > 11){
            element.value = element.value.substring(0, 11);
        }
    }
    if(tipo === 'fone'){
        if(element.value.length > 10){
            element.value = element.value.substring(0, 10);
        }
    }
    if(tipo === 'cep'){
        if(element.value.length > 8){
            element.value = element.value.substring(0, 8);
        }
    }
    if(tipo === 'data'){
        if(element.value.length > 8){
            element.value = element.value.substring(0, 8);
        }
    }
}

function isNumberKey(value){
    var a = value;
    a = parseInt(a);
    if(isNaN(a)){
        return false;
    }
    return true;
}

function checktype(element, tipo){
    var char_typed = element.value[element.value.length-1];
    if(tipo === 'data'){
        if(!isNumberKey(char_typed)){
            element.value = element.value.replace(/\D/g,'');
        }
    }
    if(tipo === 'cep'){
        if(!isNumberKey(char_typed)){
            element.value = element.value.replace(/\D/g,'');
        }
    }
    if(tipo === 'cel'){
        if(!isNumberKey(char_typed)){
            element.value = element.value.replace(/\D/g,'');
        }
    }
    if(tipo === 'fone'){
        if(!isNumberKey(char_typed)){
            element.value = element.value.replace(/\D/g,'');
        }
    }
    if(tipo === 'money'){
        if(!isNumberKey(char_typed)){
            element.value = element.value.replace(/\D/g,'');
        }
    }
    maxsize(element, tipo);
}