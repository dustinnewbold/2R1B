@extends('master')

@section('content')
	<script>
		var name = {!! json_encode($name) !!};
	</script>

	<style>
		.card {
			padding: 20px;
			border-radius: 10px;
			font-size: 24px;
			color: #fff;
			cursor: pointer;
		}

		.president, .blue, .red.spy {
			background: #009;
		}

		.bomber, .red, .blue.spy {
			background: #900;
		}

		.gambler, .grey {
			background: #999;
		}

		.blank {
			background: #000;
		}

		.round-over {
			font-size: 24px;
			color: #f00;
		}

		.add-roles li {
			float: left;
			margin: 10px;
		}

		.add-roles {
			clear: both;
		}

		.btn {
			margin-bottom: 20px;
		}

		.btn-grey {
			background: #666;
			color: #fff;
		}

		.clear {
			clear: both;
		}
	</style>
	<div class="container" ng-controller="GameController">
		<h1>
			Game #<span ng-bind="game.id"></span>
		</h1>
		<h2>
			Welcome, <span ng-bind="me.name"></span>
		</h2>

		<div class="row">
			<div class="col-md-6">
				<h3>
					Players
				</h3>
				<ul id="players">
					<li ng-repeat="player in game.players">
						@{{ player.name }}
					</li>
				</ul>
			</div>

			<div class="col-md-6">
				<h3>
					Roles
				</h3>
				<ul id="roles">
					<li ng-repeat="role in game.roles track by $index" ng-bind="role"></li>
				</ul>
			</div>
		</div>

		<div class="pre-start" ng-if="! game.started && me.name == game.creator">
			<h4>
				Add Role
			</h4>
			<ul class="list-unstyled add-roles">
				<li>
					<a href="#" class="btn btn-danger" ng-click="addRole('Red Team', $event)">
						Red Team
					</a>
				</li>
				<li>
					<a href="#" class="btn btn-danger" ng-click="addRole('Red Spy', $event)">
						Red Spy
					</a>
				</li>
				<li>
					<a href="#" class="btn btn-danger" ng-click="addRole('Red Shy Guy', $event)">
						Shy Guy (Red)
					</a>
				</li>
				<li>
					<a href="#" class="btn btn-primary" ng-click="addRole('Blue Team', $event)">
						Blue Team
					</a>
				</li>
				<li>
					<a href="#" class="btn btn-primary" ng-click="addRole('Blue Shy Guy', $event)">
						Shy Guy (Blue)
					</a>
				</li>
				<li>
					<a href="#" class="btn btn-primary" ng-click="addRole('Blue Spy', $event)">
						Blue Spy
					</a>
				</li>
				<li>
					<a href="#" class="btn btn-grey" ng-click="addRole('Grey Team', $event)">
						Grey Team
					</a>
				</li>
				<li>
					<a href="#" class="btn btn-grey" ng-click="addRole('Gambler', $event)">
						Gambler
					</a>
				</li>
			</ul>
			<div class="clear"></div>


			<div>
				<button class="btn btn-default" ng-click="startGame()" ng-if="game.roles.length === game.players.length">
					Start Game
				</button>
				<span class="error" ng-if="game.roles.length !== game.players.length">
					<strong>
						Number of roles must match number of players
					</strong>
				</span>
			</div>
		</div>

		<div class="pre-start" ng-if="! game.started && me.name !== game.creator">
			<strong>
				Game not started yet
			</strong>
		</div>

		<div ng-if="game.started">
			<h2>
				You
			</h2>

			<div ng-click="toggleRole($event)">
				<div class="card" ng-class="me.role | lowercase" ng-if="roleShown == 2">
					<span ng-bind="me.role"></span>
				</div>
				<div class="card" ng-class="me.role | lowercase" ng-if="roleShown == 1">
					&nbsp;
				</div>
				<div class="card blank" ng-if="! roleShown">
					Card Hidden
				</div>
			</div>

			<h2>
				Round
			</h2>
			<div ng-if="game.round == 0 && game.roundStart == 0">
				Round not yet started
			</div>
			<div ng-if="game.round == 0 && game.roundStart > 0">
				<span class="round-over">
					ROUND IS OVER
				</span>
			</div>
			<div ng-if="game.round > 0">
				~@{{ game.round }} seconds
			</div>
			<div ng-if="me.name === game.creator && game.round == 0">
				<div class="row">
					<div class="col-md-2">
						<div class="form-group">
							<label for="minute">
								Minutes:
							</label>
							<input type="number" class="form-control" id="minute" required="required" pattern="\d*" value="3" ng-model="round.minutes">
						</div>
						<div class="form-group">
							<button class="btn btn-default" ng-click="startRound()">
								Start Round
							</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.4.4/angular.min.js"></script>
	<script src="/js/app.js"></script>
	<script src="/js/GameController.js"></script>
@stop