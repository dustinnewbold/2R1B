@extends('master')

@section('content')
	<div class="container">
		<form action="{{ route('games.index') }}" method="GET">
			<fieldset>
				<legend>
					Join Existing Game
				</legend>
				<div class="form-group">
					<label for="firstNameExisting">
						First Name:
					</label>
					<input type="text" name="firstName" class="form-control" id="firstNameExisting" placeholder="First Name" required="required" value="{{ Session::get('firstName') }}">
				</div>
				<div class="form-group">
					<label for="gameID">
						Game ID:
					</label>
					<input type="number" name="gameID" class="form-control" id="gameID" placeholder="Game ID" maxlength="5" required="required" value="{{ Input::get('gameID') }}" pattern="\d*">
				</div>
				<div class="form-group">
					<button type="submit" class="btn btn-default">
						Join Game
					</button>
				</div>
			</fieldset>
		</form>
		<form action="{{ route('games.store') }}" method="POST">
			{!! csrf_field() !!}
			<fieldset>
				<legend>
					Create New Game
				</legend>
				<div class="form-group">
					<label for="firstName">
						First Name:
					</label>
					<input type="text" class="form-control" name="firstName" id="firstName" placeholder="First Name" required="required" value="{{ Session::get('firstName') }}">
				</div>
				<div class="form-group">
					<button type="submit" class="btn btn-default">Create New Game</button>
				</div>
			</fieldset>
		</form>
	</div>
@stop