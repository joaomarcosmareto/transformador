(function() {
    var workoutapp = angular.module('transformador-states', ['ui.router']);

    workoutapp.config(function($stateProvider, $urlRouterProvider) {

        // HOME STATES AND NESTED VIEWS ========================================
        $stateProvider

        .state('modadm', {
            abstract:true,
            url: '/modadm',
            templateUrl: 'modadm/modadm.html'
        })

		.state('modadm.new', {
			url: '/new',
			templateUrl: 'modadm/new.html',
            parentState:'modadm.new'
		})
    });
})();