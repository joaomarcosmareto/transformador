(function() {
    var workoutapp = angular.module('workoutapp-states', ['ui.router']);

    workoutapp.config(function($stateProvider, $urlRouterProvider) {

        // HOME STATES AND NESTED VIEWS ========================================
        $stateProvider
        
        .state('modadm', {
            abstract:true,
            url: '/modadm',
            templateUrl: 'modadm/modadm.html'
        })

		.state('modadm.listagem', {
			url: '/listagem',
			templateUrl: 'modadm/listagem.html',
            parentState:'modadm.listagem'
		})

		.state('modadm.edit', {
			url: '/edit',
			templateUrl: 'modadm/edit.html',
            parentState:'modadm.detalhes'
		})

		.state('modadm.detalhes', {
			url: '/detalhes',
			templateUrl: 'modadm/detalhes.html',
            parentState:'modadm.listagem'
		})

		.state('modadm.menus', {
			url: '/menus',
			templateUrl: 'modadm/menus.html',
            parentState:'modadm.detalhes'
		})

		.state('modadm.menu', {
			url: '/menu',
			templateUrl: 'modadm/menu.html',
            parentState:'modadm.menus'
		})
        
		.state('modadm.categorias', {
			url: '/categorias',
			templateUrl: 'modadm/categorias.html',
            parentState:'modadm.detalhes'
		})

		.state('modadm.categoria', {
			url: '/categoria',
			templateUrl: 'modadm/categoria.html',
            parentState:'modadm.categorias'
		})

		.state('modadm.colaboradores', {
			url: '/colaboradores',
			templateUrl: 'modadm/colaboradores.html',
            parentState:'modadm.detalhes'
		})

		.state('modadm.colaborador', {
			url: '/colaborador',
			templateUrl: 'modadm/colaborador.html',
            data:{parent:'modadm.colaboradores'}
		})

		.state('modadm.conteudos', {
			url: '/conteudos',
			templateUrl: 'modadm/conteudos.html',
            parentState:'modadm.detalhes'
		})

		.state('modadm.conteudo', {
			url: '/conteudo',
			templateUrl: 'modadm/conteudo.html',
            parentState:'modadm.conteudos'
		})
        
        .state('conteudo', {
            url: '/conteudo',
            templateUrl: 'modadm/conteudo.html',
        })
        
    });
})();