phonecatApp.controller('GameController', function ($scope, $http) {
	$scope.me = {};
	$scope.round = {
		minutes: 3
	};
	// 2 = Role/Color Shown
	// 1 = Just Color Shown
	// 0 = Completely Hidden
	$scope.roleShown = 2;

	function updateGame() {
		$http.get('?api').success(function(data) {
			if ( $scope.game && $scope.game.round > 0 && data.round == 0 ) {
				var audio = new Audio('/audio/alarm.mp3');
				audio.play();
			}
			$scope.game = data;

			$scope.game.players.forEach(function(player) {
				if ( player.name == name ) {
					$scope.me = player;
				}
			});
		});
	}
	updateGame();
	setInterval(updateGame, 5000);

	$scope.addRole = function(role, $event) {
		$event.preventDefault();

		$scope.game.roles.push(role);
		$http.put('?api', $scope.game);
	};

	$scope.startGame = function() {
		$scope.game.started = true;
		$http.put('?api', $scope.game);
	};

	$scope.toggleRole = function($event) {
		$scope.roleShown--;
		if ( $scope.roleShown < 0 ) {
			$scope.roleShown = 2;
		}
	};

	$scope.startRound = function() {
		$scope.game.round = $scope.round.minutes * 60;
		$scope.game.roundStart = 1;
		$http.put('?api', $scope.game);
	}
});