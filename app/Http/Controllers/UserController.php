<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Rules\Numeros_USP;
use App\User;
use Uspdev\Replicado\Pessoa;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:authorized');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::where('role', 'admin')
                        ->orWhere('role', 'authorized')->get();
        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'codpes' => ['required', new Numeros_USP($request->codpes)],
        ]);

        $user = User::where('codpes', $request->codpes)->first();
        if (is_null($user)) {
            $user = new User;
        }

        $user->codpes = $request->codpes;
        $user->email = Pessoa::email($request->codpes);
        $user->name = Pessoa::dump($request->codpes)['nompesttd'];
        $user->role = 'authorized';
        $user->save();
        $request->session()->flash('alert-info', 'Pessoa adicionada com sucesso');
        return redirect()->route('users.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource. 
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        return view('users.edit')->with('users', $user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::find($id);
        $user->role = $request->role;
        $user->update();
        $request->session()->flash('alert-info', 'Permissão alterada com sucesso');
        return redirect()->route('users.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        $user = User::find($id);
        $user->delete();
        $request->session()->flash('alert-warning', 'Usuário deletado!');
        return redirect()->route('users.index');
    }
}
