<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Cache, Redirect, Input, Session;

class GamesController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		if ( count(Input::get()) == 0 ) {
			return Cache::get('games');
		}

		$id = strtolower(Input::get('gameID'));
		$games = Cache::get('games');

		// Verify that user doesn't already exist. Go to game if found
		$name = Input::get('firstName');
		foreach ( $games[$id]['players'] as $player ) {
			if ( $player['name'] == $name ) {
				Session::put('firstName', Input::get('firstName'));
				return Redirect::route('games.show', Input::get('gameID'));
			}
		}

		if ( $games[$id]['started'] ) {
			die('Game already started');
		}

		$games[$id]['players'][] = array(
			'name' => Input::get('firstName'),
    		'leader' => false,
    		'role' => '',
    		'team' => ''
		);
		Cache::forever('games', $games);
		Session::put('firstName', Input::get('firstName'));

		return Redirect::route('games.show', Input::get('gameID'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  Request  $request
	 * @return Response
	 */
	public function store(Request $request)
	{
		$microtime = microtime(true);
		$microtime = explode('.', $microtime);
		$id = (int)$microtime[count($microtime) - 1];
		$game = array(
		    'id' => $id,
		    'started' => false,
		    'roundStart' => 0,
		    'roundEnd' => 0,
		    'round' => 0,
		    'creator' => Input::get('firstName'),
		    'players' => array(
		    	array(
		    		'name' => Input::get('firstName'),
		    		'leader' => false,
		    		'role' => '',
		    		'team' => ''
		    	)
		    ),
		    'roles' => array(
		    	'President',
		    	'Bomber',
		    )
		);

		$games = Cache::get('games');
		$games[$id] = $game;
		Cache::forever('games', $games);

		Session::put('firstName', Input::get('firstName'));

		return Redirect::route('games.show', $id);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		if ( Session::get('firstName') == null ) {
			return Redirect::to('/?gameID=' . $id);
		}

		$id = strtolower($id);
		$games = Cache::get('games');
		$game = $games[$id];

		if ( Input::get('api') !== null ) {

			// Fix round if it's a thing
			if ( time() < $game['roundEnd'] && $game['roundEnd'] !== 0 ) {
				$game['round'] = $game['roundEnd'] - time();
			} else {
				$game['round'] = 0;
			}

			return $game;
		}

		$name = Session::get('firstName');
		return view()->make('games.show', compact('name'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  Request  $request
	 * @param  int  $id
	 * @return Response
	 */
	public function update(Request $request, $id)
	{
		$games = Cache::get('games');
		$game = $request->all();

		// Start the game
		if ( $request->all()['started'] && ! $games[$id]['started'] ) {
			$roles = $game['roles'];
			shuffle($roles);
			$i = -1;
			foreach ( $game['players'] as &$player ) {
				$i++;
				$player['role'] = $roles[$i];
			}
		}

		// Start the round
		if ( $game['roundStart'] == 1 ) {
			$game['roundStart'] = time();
			$game['roundEnd'] = time() + (int)$game['round'];
			$game['round'] = $game['roundEnd'] - time();
		}

		$games[$id] = $game;
		Cache::forever('games', $games);
		return $games[$id];
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}
}
