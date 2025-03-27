<?php

namespace App\Http\Controllers;

use App\Models\Responsavel;
use App\Models\Sala;
use App\Models\User;
use Illuminate\Http\Request;
use Uspdev\Replicado\Pessoa;

class ResponsavelController extends Controller
{
    public function store(Request $request){
        $this->authorize('admin');

        $codpes = $request->input('codpes');
        $sala = Sala::find($request->input('sala'));

        if(count(User::where('codpes', $codpes)->get()) == 0)
        {
            $user = User::findOrCreateFromReplicado($codpes);
            if (!($user instanceof \App\Models\User)) {
                return redirect()->back()->withErrors(['codpes' => $user]);
            }
        }else{
            $user = User::firstWhere('codpes', $codpes);
        }

        $responsavel = new Responsavel();
        $responsavel->sala_id = $sala->id;
        $responsavel->user_id = $user->id;
        $responsavel->save();

        $sala->restricao->aprovacao = 1;
        $sala->restricao->save();                     // sem isso, o registro na tabela restricoes não é atualizado, ainda que haja a linha a seguir
        $sala->save();

        session()->put('alert-success', $user->name.' adicionado como responsável.');
        return redirect()->route('salas.edit',['sala' => $request->input('sala'), 'responsaveis' => $sala->responsaveis]);
    }

    public function destroy(Responsavel $responsavel){
        $this->authorize('admin');

        // Se tiver apenas um responsável altera a sala para não precisar de aprovação ao deletar este único responsável.
        if(count($responsavel->sala->responsaveis) == 1){
            $responsavel->sala->restricao->aprovacao = 0;
            $responsavel->sala->restricao->save();    // sem isso, o registro na tabela restricoes não é atualizado, ainda que haja a linha a seguir
            $responsavel->sala->save();
        }

        $responsavel->delete();

        session()->put('alert-success', 'Responsável removido.');
        return back();
    }
}
