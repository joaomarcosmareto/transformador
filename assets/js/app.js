(function () {
    angular.module('initModule',
        [
            'transformador-states',
            'ngFileUpload',
            'toaster',
            'Servico-Evento',
            'Servico-Request',
            'Servico-Data',
            'App-Controller',
            'New-Controller',
			'NavBar-Controller',
            'Custom-Directive',
            'ngAnimate'
        ]
    ).
    config(['$compileProvider', function ($compileProvider) {
        $compileProvider.debugInfoEnabled(false);
    }]);
})();