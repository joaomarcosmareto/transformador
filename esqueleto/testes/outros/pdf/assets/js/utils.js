Date.now = Date.now || function() { return +new Date(); };

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
var admob = undefined;
var AdMob = undefined;
var salvou = false;
var file_file = null;

var file_logo = null;
var file_foto1 = null;
var file_foto2 = null;
var file_foto3 = null;
var file_foto4 = null;

var removerFoto = false;
var image_data = null;
var estado = null;
var academia = null;
var titulo = null;
var foto36x36 = null;

var indexFicha = null;
var indexTreino = null;
var indexExercicio = null;

// variável criada para indicar do armazenamento do estado do filtro da tela de 
// listagem de exercícios
var voltarEx = null;

var datePattern =/^([0-9]{2})\/([0-9]{2})\/([0-9]{4})$/;

var letras = ["A","B", "C", "D", "E","F","G", "H", "I", "J","K","L", "M", "N", "O","P","Q", "R", "S", "T","U","V", "W", "X", "Y", "Z"];

function ajaxToggle(){
    if(document.getElementById("resultLoading") === null)
    {
        var divResultLoading = document.createElement("DIV");
        divResultLoading.id = "resultLoading";

        var divContent = document.createElement("DIV");
        divContent.id = "divcontent";
        divContent.className = "content";

        var img = document.createElement("IMG");
        img.src = "assets/img/ajax-loader.gif";

        var divMsg = document.createElement("DIV");
        divMsg.id = "divmsg";
        var textnode = document.createTextNode(mensagens.mensagemAjaxStart);
        divMsg.appendChild(textnode);

        divContent.appendChild(img);
        divContent.appendChild(divMsg);

        divResultLoading.appendChild(divContent);

        var divBg = document.createElement("DIV");
        divBg.id = "divBg";
        divBg.className = "bg";

        divResultLoading.appendChild(divBg);

        document.getElementById("body").appendChild(divResultLoading);
    }
    else
    {
        removeAjaxToggle();
    }
}

function removeAjaxToggle(){
    if(document.getElementById("resultLoading") !== null)
        document.getElementById("body").removeChild(document.getElementById("resultLoading"));
}
function transformRequestParam(obj){
    var query = '', name, value, fullSubName, subName, subValue, innerObj, i;
      
    for(name in obj) {
        value = obj[name];
        
        if(value instanceof Array) {
            for(i=0; i<value.length; ++i) {
                subValue = value[i];
                fullSubName = name + '[' + i + ']';
                innerObj = {};
                innerObj[fullSubName] = subValue;
                query += param(innerObj) + '&';
            }
        }
        else if(value instanceof Object) {
            for(subName in value) {
                subValue = value[subName];
                fullSubName = name + '[' + subName + ']';
                innerObj = {};
                innerObj[fullSubName] = subValue;
                query += param(innerObj) + '&';
            }
        }
        else if(value !== undefined && value !== null)
            query += encodeURIComponent(name) + '=' + encodeURIComponent(value) + '&';
    }
    return query.length ? query.substr(0, query.length - 1) : query;
}
function scaleSize(cW, cH, maxW, maxH){
    var srcRatio = cW / cH;
    var thumbRatio = maxW / maxH;
    // console.log("source width", cW);
    // console.log("source height", cH);
    // console.log("source ratio", srcRatio);
    // console.log("thumb ratio", thumbRatio);
    if(cW <= maxW && cH <= maxH){
        return { width: cW, height: cH };
    }
    else if(thumbRatio > srcRatio){
        return { width: (maxH * srcRatio), height: maxH };
    }
    else{
        return { width: maxW, height: (maxW / srcRatio) }; 
    }
}

function cleanForms(){
    console.log("[cleanForms]");
    var forms = document.getElementsByTagName('form');
    for(var i = 0; i < forms.length; i++)
    {
        forms[i].reset();
    }
}
function saveLoginData(data){
    console.log("[saveLoginData]");
    localStorage.setItem("data", JSON.stringify(data));
}
function getSexo(){
    console.log("[getSexo]");
    var a = document.getElementsByName("gender");
    if(a[0].checked) return a[0].value;
    else if(a[1].checked) return a[1].value;
    return false;
}
function getPerfilSexo(){
    console.log("[getPerfilSexo]");
    var a = document.getElementsByName("perfilgender");
    if(a[0].checked) return a[0].value;
    else if(a[1].checked) return a[1].value;
    return false;
}

function atualizarDadosUsuario(param){
    console.log("[atualizarDadosUsuario]");
    //console.log("[data] : ["+ localStorage.getItem("data") +"]");
    //console.log("[param] : ["+ JSON.stringify(param) +"]");
    
    var data = JSON.parse(localStorage.getItem("data"));

    data.nome = param.nome;
    data.sobrenome = param.sobrenome;
    data.sexo = param.sexo;
    data.dataNascimento = param.dataNascimento;
    data.foto90x79 = param.foto90x79;
    data.foto36x36 = param.foto36x36;

    localStorage.setItem("data",  JSON.stringify(data));
    //console.log("[data] : ["+ localStorage.getItem("data") +"]");
}

function atualizarDadosAcademias(param){
    console.log("[atualizarDadosUsuario]");
    //console.log("[data] : ["+ localStorage.getItem("data") +"]");
    //console.log("[param] : ["+ JSON.stringify(param) +"]");
    
    var data = JSON.parse(localStorage.getItem("data"));

    data.nome = param.nome;
    data.sobrenome = param.sobrenome;
    data.sexo = param.sexo;
    data.dataNascimento = param.dataNascimento;
    data.foto90x79 = param.foto90x79;
    data.foto36x36 = param.foto36x36;

    localStorage.setItem("data",  JSON.stringify(data));
    console.log("[data] : ["+ localStorage.getItem("data") +"]");
}

function sha1(str) {
    //  discuss at: http://phpjs.org/functions/sha1/
    // original by: Webtoolkit.info (http://www.webtoolkit.info/)
    // improved by: Michael White (http://getsprink.com)
    // improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    //    input by: Brett Zamir (http://brett-zamir.me)
    //   example 1: sha1('Kevin van Zonneveld');
    //   returns 1: '54916d2e62f65b3afa6e192e6a601cdbe5cb5897'

    var rotate_left = function (n, s) {
        var t4 = (n << s) | (n >>> (32 - s));
        return t4;
    };

    /*var lsb_hex = function (val) {
    // Not in use; needed?
    var str="";
    var i;
    var vh;
    var vl;
    for ( i=0; i<=6; i+=2 ) {
    vh = (val>>>(i*4+4))&0x0f;
    vl = (val>>>(i*4))&0x0f;
    str += vh.toString(16) + vl.toString(16);
    }
    return str;
    };*/

    var cvt_hex = function (val) {
        var str = '';
        var i;
        var v;

        for (i = 7; i >= 0; i--) {
            v = (val >>> (i * 4)) & 0x0f;
            str += v.toString(16);
        }
        return str;
    };

    var blockstart;
    var i, j;
    var W = new Array(80);
    var H0 = 0x67452301;
    var H1 = 0xEFCDAB89;
    var H2 = 0x98BADCFE;
    var H3 = 0x10325476;
    var H4 = 0xC3D2E1F0;
    var A, B, C, D, E;
    var temp;

    // utf8_encode
    str = unescape(encodeURIComponent(str));
    var str_len = str.length;

    var word_array = [];
    for (i = 0; i < str_len - 3; i += 4) {
        j = str.charCodeAt(i) << 24 | str.charCodeAt(i + 1) << 16 | str.charCodeAt(i + 2) << 8 | str.charCodeAt(i + 3);
        word_array.push(j);
    }

    switch (str_len % 4) {
        case 0:
            i = 0x080000000;
            break;
        case 1:
            i = str.charCodeAt(str_len - 1) << 24 | 0x0800000;
            break;
        case 2:
            i = str.charCodeAt(str_len - 2) << 24 | str.charCodeAt(str_len - 1) << 16 | 0x08000;
            break;
        case 3:
            i = str.charCodeAt(str_len - 3) << 24 | str.charCodeAt(str_len - 2) << 16 | str.charCodeAt(str_len - 1) <<
            8 | 0x80;
            break;
    }

    word_array.push(i);

    while ((word_array.length % 16) != 14) {
        word_array.push(0);
    }

    word_array.push(str_len >>> 29);
    word_array.push((str_len << 3) & 0x0ffffffff);

    for (blockstart = 0; blockstart < word_array.length; blockstart += 16) {
        for (i = 0; i < 16; i++) {
            W[i] = word_array[blockstart + i];
        }
        for (i = 16; i <= 79; i++) {
            W[i] = rotate_left(W[i - 3] ^ W[i - 8] ^ W[i - 14] ^ W[i - 16], 1);
        }

        A = H0;
        B = H1;
        C = H2;
        D = H3;
        E = H4;

        for (i = 0; i <= 19; i++) {
            temp = (rotate_left(A, 5) + ((B & C) | (~B & D)) + E + W[i] + 0x5A827999) & 0x0ffffffff;
            E = D;
            D = C;
            C = rotate_left(B, 30);
            B = A;
            A = temp;
        }

        for (i = 20; i <= 39; i++) {
            temp = (rotate_left(A, 5) + (B ^ C ^ D) + E + W[i] + 0x6ED9EBA1) & 0x0ffffffff;
            E = D;
            D = C;
            C = rotate_left(B, 30);
            B = A;
            A = temp;
        }

        for (i = 40; i <= 59; i++) {
            temp = (rotate_left(A, 5) + ((B & C) | (B & D) | (C & D)) + E + W[i] + 0x8F1BBCDC) & 0x0ffffffff;
            E = D;
            D = C;
            C = rotate_left(B, 30);
            B = A;
            A = temp;
        }

        for (i = 60; i <= 79; i++) {
            temp = (rotate_left(A, 5) + (B ^ C ^ D) + E + W[i] + 0xCA62C1D6) & 0x0ffffffff;
            E = D;
            D = C;
            C = rotate_left(B, 30);
            B = A;
            A = temp;
        }

        H0 = (H0 + A) & 0x0ffffffff;
        H1 = (H1 + B) & 0x0ffffffff;
        H2 = (H2 + C) & 0x0ffffffff;
        H3 = (H3 + D) & 0x0ffffffff;
        H4 = (H4 + E) & 0x0ffffffff;
    }

    temp = cvt_hex(H0) + cvt_hex(H1) + cvt_hex(H2) + cvt_hex(H3) + cvt_hex(H4);
    return temp.toLowerCase();
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
    for(var i = 0 ; i < len;i++)
    {
        if(array[i] == elem){return i;}
    }
    return -1;
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

function generateThumb(file, $timeout, imageId, width, height) {
        
    getOrientation(file, function(orientation) 
    {
        var resizeFunction = resize;
        
        // if(orientation === 6)
        //     resizeFunction = resize2AndRotate;
    
        if (typeof file !== undefined && file !== null) {
            $timeout(function () {
                var reader = new FileReader();
                reader.onloadend = function (e) {
                    $timeout(function () {
                        var img = new Image();
                        img.crossOrigin = 'anonymous';
                        img.orientation = orientation;
                        img.imageId = imageId;
                        img.destWidth = width;
                        img.destHeight = height;
                        img.onload = resizeFunction;
                        img.src = e.target.result;
                        // setTimeout(function() {
                        //     scaleImage(img, imageId, width);
                        // }, 300);
                    });
                };
                reader.readAsDataURL(file);

            });//$timeout(function())
        }//if file !== null
    });
}

function getOrientation(file, callback) {
    var reader = new FileReader();
    reader.onload = function(e) {

        var view = new DataView(e.target.result);
        if (view.getUint16(0, false) != 0xFFD8) return callback(-2);
        var length = view.byteLength, offset = 2;
        while (offset < length) {
            var marker = view.getUint16(offset, false);
            offset += 2;
            if (marker == 0xFFE1) {
                var little = view.getUint16(offset += 8, false) == 0x4949;
                offset += view.getUint32(offset + 4, little);
                var tags = view.getUint16(offset, little);
                offset += 2;
                for (var i = 0; i < tags; i++)
                    if (view.getUint16(offset + (i * 12), little) == 0x0112)
                        return callback(view.getUint16(offset + (i * 12) + 8, little));
            }
            else if ((marker & 0xFF00) != 0xFF00) break;
            else offset += view.getUint16(offset, false);
        }
        return callback(-1);
    };
    reader.readAsArrayBuffer(file.slice(0, 64 * 1024));
}

function sharpen(ctx, w, h, mix) {

    var weights = [0, -1, 0, -1, 5, -1, 0, -1, 0],
        katet = Math.round(Math.sqrt(weights.length)),
        half = (katet * 0.5) | 0,
        dstData = ctx.createImageData(w, h),
        dstBuff = dstData.data,
        srcBuff = ctx.getImageData(0, 0, w, h).data,
        y = h;

    while (y--) {

        x = w;

        while (x--) {

            var sy = y,
                sx = x,
                dstOff = (y * w + x) * 4,
                r = 0,
                g = 0,
                b = 0,
                a = 0;

            for (var cy = 0; cy < katet; cy++) {
                for (var cx = 0; cx < katet; cx++) {

                    var scy = sy + cy - half;
                    var scx = sx + cx - half;

                    if (scy >= 0 && scy < h && scx >= 0 && scx < w) {

                        var srcOff = (scy * w + scx) * 4;
                        var wt = weights[cy * katet + cx];

                        r += srcBuff[srcOff] * wt;
                        g += srcBuff[srcOff + 1] * wt;
                        b += srcBuff[srcOff + 2] * wt;
                        a += srcBuff[srcOff + 3] * wt;
                    }
                }
            }

            dstBuff[dstOff] = r * mix + srcBuff[dstOff] * (1 - mix);
            dstBuff[dstOff + 1] = g * mix + srcBuff[dstOff + 1] * (1 - mix);
            dstBuff[dstOff + 2] = b * mix + srcBuff[dstOff + 2] * (1 - mix)
            dstBuff[dstOff + 3] = srcBuff[dstOff + 3];
        }
    }

    ctx.putImageData(dstData, 0, 0);
}

/// naive and non-efficient implementation of update, but
/// do illustrate the impact of sharpen after a downsample
function resize2AndRotate() {
    var image = document.getElementById(this.imageId);
    var canvas = document.createElement("canvas");
    var ctx = canvas.getContext("2d");

    console.log("imagem tem: ", this.width, this.height);
    var json = scaleSize(this.width, this.height, this.destWidth, this.destHeight);
    console.log("de acordo com meu scale, deveria ter: ",json);

    /// set canvas size proportional to original image
    canvas.height = canvas.width * (this.height / this.width);
    
    var rc = document.createElement('canvas'),
        rctx = rc.getContext('2d');
        
    // rc.width = this.width;
    // rc.height = this.height;    
    rc.width = this.height;
    rc.height = this.width;
    
    // rctx.translate(rc.width/2,rc.height/2);
    rctx.translate(rc.width,0);
    rctx.rotate(90*Math.PI/180);
    //rctx.drawImage(this, 0, 0, -this.width/2, -this.height/2);
    rctx.drawImage(this, 0, 0, this.width, this.height);

    var oc = document.createElement('canvas'),
        octx = oc.getContext('2d');

    oc.width = rc.width * 0.5;
    oc.height = rc.height * 0.5;
    octx.drawImage(rc, 0, 0, oc.width, oc.height);
    console.log("step1: ", oc.width, oc.height);
    /// step 2
    octx.drawImage(oc, 0, 0, oc.width * 0.5, oc.height * 0.5);
    console.log("step2: ", oc.width*0.5, oc.height*0.5);
    /// draw final result to screen canvas
    // ctx.drawImage(oc, 0, 0, oc.width * 0.5, oc.height * 0.5, 0, 0, canvas.width, canvas.height);
    //apagar daqui baixo
    canvas.width = oc.width * 0.5;
    canvas.height = oc.height * 0.5;
    ctx.drawImage(oc, 0, 0, oc.width * 0.5, oc.height * 0.5, 0, 0, oc.width * 0.5, oc.height * 0.5);
    //apagar daqui pra cima
    console.log("step final: ", oc.width*0.5, oc.height*0.5);
    /// copy to off-screen to use as source for shapening
    // offctx.drawImage(canvas, 0, 0);
    
    /// apply sharpening convolution
    // update(ctx, offScreen, canvas);
    image.src = rc.toDataURL("image/jpeg", 0.9);
    console.log("imagem ficou com: ", image.width, image.height);
    file_file = dataURItoBlob(image.src);
}

function resize2() {
    // var image = document.getElementById("fotoca");
    var image = document.getElementById(this.imageId);
    var canvas = document.createElement("canvas");
    // canvas.width = this.superWidth;
    var ctx = canvas.getContext("2d");
    // var offScreen = document.createElement('canvas');
    // var offctx = offScreen.getContext('2d');

    console.log("imagem tem: ", this.width, this.height);
    var json = scaleSize(this.width, this.height, this.destWidth, this.destHeight);
    console.log("de acordo com meu scale, deveria ter: ",json);

    /// set canvas size proportional to original image
    canvas.height = canvas.width * (this.height / this.width);
    // canvas.width = json.width;
    // canvas.height = json.height;
    /// set off-screen canvas/sharpening source to same size
    // offScreen.width = canvas.width;
    // offScreen.height = canvas.height;
    // console.log("mas tem: ", canvas.width, canvas.height);
    /// step 1 in down-scaling
    var oc = document.createElement('canvas'),
        octx = oc.getContext('2d');

    oc.width = this.width * 0.5;
    oc.height = this.height * 0.5;
    octx.drawImage(this, 0, 0, oc.width, oc.height);
    console.log("step1: ", oc.width, oc.height);
    /// step 2
    octx.drawImage(oc, 0, 0, oc.width * 0.5, oc.height * 0.5);
    console.log("step2: ", oc.width*0.5, oc.height*0.5);
    /// draw final result to screen canvas
    // ctx.drawImage(oc, 0, 0, oc.width * 0.5, oc.height * 0.5, 0, 0, canvas.width, canvas.height);
    //apagar daqui baixo
    canvas.width = oc.width * 0.5;
    canvas.height = oc.height * 0.5;
    ctx.drawImage(oc, 0, 0, oc.width * 0.5, oc.height * 0.5, 0, 0, oc.width * 0.5, oc.height * 0.5);
    //apagar daqui pra cima
    console.log("step final: ", oc.width*0.5, oc.height*0.5);
    /// copy to off-screen to use as source for shapening
    // offctx.drawImage(canvas, 0, 0);
    
    /// apply sharpening convolution
    // update(ctx, offScreen, canvas);
    image.src = canvas.toDataURL("image/jpeg", 0.9);
    console.log("imagem ficou com: ", image.width, image.height);
    file_file = dataURItoBlob(image.src);
}

/// naive and non-efficient implementation of update, but
/// do illustrate the impact of sharpen after a downsample
function resize() {
    var canvas = document.createElement("canvas");
    var ctx = canvas.getContext("2d");

    // console.log("imagem tem: ", this.width, this.height);
    // console.log("canvas tem: ", canvas.width, canvas.height);
    var json = scaleSize(this.width, this.height, this.destWidth, this.destHeight);
    // console.log("de acordo com meu scale, deveria ter: ",json);

    var rc = document.createElement('canvas');
    var rctx = rc.getContext('2d');

    if(this.orientation == 6){
        rc.width = this.height;
        rc.height = this.width;

        rctx.translate(rc.width,0);
        rctx.rotate(90*Math.PI/180);
    }
    else{
        rc.width = this.width;
        rc.height = this.height;
    }

    rctx.drawImage(this, 0, 0, this.width, this.height);

    var oc = document.createElement('canvas');
    var octx = oc.getContext('2d');

    var fim = false;
    var i = 1;

    var steps = [];

    //320 de width parece ser um tamanho bom entre o número de passos e o tamanho final do arquivo    
    if(rc.width * 0.5 >= 320){
        oc.width = rc.width * 0.5;
        oc.height = rc.height * 0.5;

        steps[0] = {width: rc.width * 0.5, height: rc.height * 0.5};
        while(true){
            steps[i] = {width: rc.width / ((i+1) * 2), height: rc.height / ((i+1) * 2)};

            //320 de width parece ser um tamanho bom entre o número de passos e o tamanho final do arquivo
            if(steps[i].width < 320){
                break;
            }
            i++;
        }
    }
    else
    {
        oc.width = rc.width;
        oc.height = rc.height;
        steps[0] = {width: rc.width, height: rc.height};
    }
    console.log(steps);
    octx.drawImage(rc, 0, 0, steps[0].width, steps[0].height);
    // console.log("printei a primeira vez: ", steps[0].width, steps[0].height);

    if(steps.length > 1){
        for(i = 1; i < steps.length; i++)
        {
            if(i+1 <= steps.length){
                octx.drawImage(oc, 0, 0, steps[i-1].width, steps[i-1].height, 0, 0, steps[i].width, steps[i].height);
                // console.log("printei a "+(i+1)+"ª vez: ",  0, 0, steps[i-1].width, steps[i-1].height, 0, 0, steps[i].width, steps[i].height);
            }
            if(i+1 === steps.length){
                canvas.width = steps[i].width;
                canvas.height = steps[i].height;
                ctx.drawImage(oc, 0, 0, steps[i].width, steps[i].height, 0, 0, steps[i].width, steps[i].height);
                // console.log("printei a ultima vez: ", 0, 0, steps[i].width, steps[i].height, 0, 0, steps[i].width, steps[i].height);
            }
        }
    }
    else
    {
        canvas.width = steps[0].width;
        canvas.height = steps[0].height;
        ctx.drawImage(oc, 0, 0, steps[0].width, steps[0].height, 0, 0, steps[0].width, steps[0].height);
        // console.log("printei a ultima vez: ", 0, 0, steps[0].width, steps[0].height, 0, 0, steps[0].width, steps[0].height);
    }

    // console.log("canvas: ", canvas.width, canvas.height);

    var image = document.getElementById(this.imageId);
    image.src = canvas.toDataURL("image/jpeg", 0.9);
    file_file = dataURItoBlob(image.src);
}
function scale(){
        var destWidth = 320, destHeight = 212;
        var start = new Date().getTime();
        var scalingSteps = 0;

        var canvas = document.createElement("canvas");
        var ctx = canvas.getContext("2d");

        var curWidth = this.naturalWidth;
        var curHeight = this.naturalHeight;

        var lastWidth = this.naturalWidth;
        var lastHeight = this.naturalHeight;

        var end = false;
        var scale=0.5;
        while(end==false){
            scalingSteps +=1;
            curWidth *= scale;
            curHeight *= scale;
            if(curWidth < destWidth){
                curWidth = destWidth;
                curHeight = destHeight;
                end=true;
            }
            ctx.drawImage(canvas, 0, 0, Math.round(lastWidth), Math.round(lastHeight), 0, 0, Math.round(curWidth), Math.round(curHeight));
            lastWidth = curWidth;
            lastHeight = curHeight;
        }
        var endTime =new Date().getTime();
        console.log("execution time: "+ ( endTime - start) + "ms. scale per frame: "+scale+ " scaling step count: "+scalingSteps);
        var image = document.getElementById(this.imageId);
        image.src = canvas.toDataURL("image/jpeg", 0.9);
        file_file = dataURItoBlob(image.src);
    }
function scaleImage(img, imageId, targetWidth) {
    var imgCV = document.createElement('canvas');
    imgCV.width = img.width;
    imgCV.height = img.height;

    var imgCtx = imgCV.getContext('2d');
    imgCtx.drawImage(img, 0, 0);

    var scale = targetWidth / img.width;
    console.log("scale", scale)

    var image = document.getElementById(imageId);
    image.src = downScaleCanvas(imgCV, scale);
    file_file = dataURItoBlob(image.src);

    var downscaled = downScaleCanvas(imgCV, scale);
    console.log("scaled", targetWidth, img.height * scale);
    console.log("dimensions: ", targetWidth + " x " + img.height * scale);
    }
    
    
//algoritmo do gamealchemist    
//http://stackoverflow.com/questions/18922880/html5-canvas-resize-downscale-image-high-quality
//http://jsfiddle.net/gamealchemist/r6aVp/
function downScaleCanvas(cv, scale) {
    if (!(scale < 1) || !(scale > 0))
    {
        return cv.toDataURL("image/jpeg", 0.9);
    }
    var sqScale = scale * scale;
    var sw = cv.width;
    var sh = cv.height;
    var tw = Math.floor(sw * scale);
    var th = Math.floor(sh * scale);
    var sx = 0, sy = 0, sIndex = 0;
    var tx = 0, ty = 0, yIndex = 0, tIndex = 0;
    var tX = 0, tY = 0;
    var w = 0, nw = 0, wx = 0, nwx = 0, wy = 0, nwy = 0;

    var crossX = false;
    var crossY = false;
    var sBuffer = cv.getContext('2d').getImageData(0, 0, sw, sh).data;
    var tBuffer = new Float32Array(3 * tw * th);
    var sR = 0, sG = 0,  sB = 0;

    for (sy = 0; sy < sh; sy++) {
        ty = sy * scale;
        tY = 0 | ty;
        yIndex = 3 * tY * tw;
        crossY = (tY != (0 | ty + scale)); 
        if (crossY) {
            wy = (tY + 1 - ty);
            nwy = (ty + scale - tY - 1);
        }
        for (sx = 0; sx < sw; sx++, sIndex += 4) {
            tx = sx * scale;
            tX = 0 |  tx;
            tIndex = yIndex + tX * 3;
            crossX = (tX != (0 | tx + scale));
            if (crossX) {
                wx = (tX + 1 - tx);
                nwx = (tx + scale - tX - 1);
            }
            sR = sBuffer[sIndex    ];
            sG = sBuffer[sIndex + 1];
            sB = sBuffer[sIndex + 2];

            if (!crossX && !crossY) {
                tBuffer[tIndex    ] += sR * sqScale;
                tBuffer[tIndex + 1] += sG * sqScale;
                tBuffer[tIndex + 2] += sB * sqScale;
            } else if (crossX && !crossY) {
                w = wx * scale;
                tBuffer[tIndex    ] += sR * w;
                tBuffer[tIndex + 1] += sG * w;
                tBuffer[tIndex + 2] += sB * w;

                nw = nwx * scale
                tBuffer[tIndex + 3] += sR * nw;
                tBuffer[tIndex + 4] += sG * nw;
                tBuffer[tIndex + 5] += sB * nw;
            } else if (crossY && !crossX) {
                w = wy * scale;
                tBuffer[tIndex    ] += sR * w;
                tBuffer[tIndex + 1] += sG * w;
                tBuffer[tIndex + 2] += sB * w;

                nw = nwy * scale
                tBuffer[tIndex + 3 * tw    ] += sR * nw;
                tBuffer[tIndex + 3 * tw + 1] += sG * nw;
                tBuffer[tIndex + 3 * tw + 2] += sB * nw;
            } else {
                w = wx * wy;
                tBuffer[tIndex    ] += sR * w;
                tBuffer[tIndex + 1] += sG * w;
                tBuffer[tIndex + 2] += sB * w;

                nw = nwx * wy;
                tBuffer[tIndex + 3] += sR * nw;
                tBuffer[tIndex + 4] += sG * nw;
                tBuffer[tIndex + 5] += sB * nw;

                nw = wx * nwy;
                tBuffer[tIndex + 3 * tw    ] += sR * nw;
                tBuffer[tIndex + 3 * tw + 1] += sG * nw;
                tBuffer[tIndex + 3 * tw + 2] += sB * nw;

                nw = nwx * nwy;
                tBuffer[tIndex + 3 * tw + 3] += sR * nw;
                tBuffer[tIndex + 3 * tw + 4] += sG * nw;
                tBuffer[tIndex + 3 * tw + 5] += sB * nw;
            }
        } // end for sx 
    } // end for sy

    // create result canvas
    var resCV = document.createElement('canvas');
    resCV.width = tw;
    resCV.height = th;
    var resCtx = resCV.getContext('2d');
    var imgRes = resCtx.getImageData(0, 0, tw, th);
    var tByteBuffer = imgRes.data;
    // convert float32 array into a UInt8Clamped Array
    var pxIndex = 0; //  
    var r,g,b;
    for (sIndex = 0, tIndex = 0; pxIndex < tw * th; sIndex += 3, tIndex += 4, pxIndex++) {
      tByteBuffer[tIndex] = Math.ceil(tBuffer[sIndex]);
      tByteBuffer[tIndex + 1] = Math.ceil(tBuffer[sIndex + 1]);
      tByteBuffer[tIndex + 2] = Math.ceil(tBuffer[sIndex + 2]);
      tByteBuffer[tIndex + 3] = 255;
    }
    // writing result to canvas.
    resCtx.putImageData(imgRes, 0, 0);
    return resCV.toDataURL("image/jpeg", 0.9);
}

function dataURItoBlob(dataURI) {
    // convert base64/URLEncoded data component to raw binary data held in a string
    var byteString;
    if (dataURI.split(',')[0].indexOf('base64') >= 0)
        byteString = atob(dataURI.split(',')[1]);
    else
        byteString = unescape(dataURI.split(',')[1]);

    // separate out the mime component
    var mimeString = dataURI.split(',')[0].split(':')[1].split(';')[0];

    // write the bytes of the string to a typed array
    var ia = new Uint8Array(byteString.length);
    for (var i = 0; i < byteString.length; i++) {
        ia[i] = byteString.charCodeAt(i);
    }

    return new Blob([ia], {type:mimeString});
}

/// adjustable sharpening - update cached source
function update(context, offScreen, canvas) {
    context.drawImage(offScreen, 0, 0);
    // mix value é um valor para o sharpening.
    mix_value = 20;
    sharpen(context, canvas.width, canvas.height, parseInt(mix_value) * 0.01);
}

function fazPaginacao($scope, numeroMaxPaginasExibidas, numeroRegistrosGrid, data)
{
    $scope.grid.itens = data.itens;
    $scope.grid.total = Number(data.total);

    if($scope.grid.total === 0){
        $scope.grid.paginas = 1;
        $scope.grid.inicio = 0;
        $scope.grid.fim = 0;
        $scope.grid.paginasArray = [];
    }
    else
    {     
        $scope.grid.paginas = Math.ceil($scope.grid.total / numeroRegistrosGrid);

        maxPaginasExibidas = $scope.grid.paginas > numeroMaxPaginasExibidas ? numeroMaxPaginasExibidas : $scope.grid.paginas;

        paginasRestantes = $scope.grid.paginas - $scope.grid.paginaAtual;

        totalAntesAtual = Math.abs(1 - $scope.grid.paginaAtual);

        if(totalAntesAtual > Math.floor(maxPaginasExibidas/2))
            totalAntesAtual = Math.floor(maxPaginasExibidas/2);

        totalDepoisAtual = maxPaginasExibidas-totalAntesAtual-1;                    
        totalDepoisAtual = paginasRestantes < totalDepoisAtual ? paginasRestantes : totalDepoisAtual;   

        //ajuste caso o atual passe da metade + 1 do totalPaginasExibidas (ele tem q começar a adicionar no inicio para sempre da o numero total)
        ajuste = 0;
        if(totalDepoisAtual === paginasRestantes)
            ajuste = totalAntesAtual - totalDepoisAtual > 0 ? totalAntesAtual - totalDepoisAtual -1 : 0;                    

        $scope.grid.indexAtual = totalAntesAtual >= 1 ? totalAntesAtual+1+ajuste : 1;

        $scope.grid.paginasArray = [];
        primeiraPagina = $scope.grid.paginaAtual-totalAntesAtual;
        for (var i = primeiraPagina-ajuste; i <= $scope.grid.paginaAtual-1; i++) 
            $scope.grid.paginasArray.push(i);
        for (var i = $scope.grid.paginaAtual; i <= $scope.grid.paginaAtual+totalDepoisAtual; i++) 
            $scope.grid.paginasArray.push(i);

        $scope.grid.inicio = ($scope.grid.paginaAtual-1) * numeroRegistrosGrid + 1;
        $scope.grid.fim = ($scope.grid.paginaAtual-1) * numeroRegistrosGrid + data.itens.length;
    }
}

function booleanToInt(b){
    return b ? 1 : 0;
}

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

function maskData(element){
    var v = element.value;
    console.log(v);
    if(v.charAt(2) === "/" && v.charAt(5) === "/" && v.length === 10)
    {
        // do nothing;
    }
    else if(v.indexOf("/") === -1 && v.length === 8)
    {
        v = v[0] + v[1] + "/" + v[2] + v[3] + "/" + v[4] + v[5] + v[6] + v[7];
        element.value = v;
    }
}

function maskIn(element, tipo){
    element.value = format(element.value, tipo);
}

function maskOut(element, tipo){
    element.value = unformat(element.value, tipo);
}

function maxsize(event, element, tipo){
    // console.log(event.keyCode);
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

function isNumberKey(event){
    var code = event.keyCode;
    // abaixo sao numeros do teclado normal e numeros do teclado numerico nesta ordem
    if((code > 47 && code < 58) || (code > 95 && code < 107))
        return true;
    // abaixo é backspace, tab, shift e control nesta ordem, e setas do teclado
    else if(code === 8 || code === 9 || code === 16 || code === 17 || (code > 36 && code < 41))
        return true;
    else 
        return false;
}

function format(v, tipo){
    if(v === null) return null;

    var aux = "";
    if(tipo === 'cel'){
        if(v.length === 11){
            aux = "(" + v.substring(0, 2) + ") " + v.substring(2, 7) + "-" + v.substring(7, 11);
        }
        else if(v.length === 15){
            aux = v;
        }
        else{
            aux = "";
        }
    }
    if(tipo === 'fone'){
        if(v.length === 10){
            aux = "(" + v.substring(0, 2) + ") " + v.substring(2, 6) + "-" + v.substring(6, 10);
        }
        else if(v.length === 14){
            aux = v;
        }
        else{
            aux = "";
        }
    }
    if(tipo === 'cep'){
        if(v.length === 8){
            aux = v.substring(0, 5) + "-" + v.substring(5, 8);
        }
        else if(v.length === 9){
            aux = v;
        }
        else{
            aux = "";
        }
    }
    if(tipo === 'data'){
        if(v.length === 8){
            aux = v.substring(0, 2) + "/" + v.substring(2, 4) + "/" + v.substring(4, 8);
        }
        else if(v.length === 10){
            aux = v;
        }
        else{
            aux = "";
        }
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
    return aux;
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