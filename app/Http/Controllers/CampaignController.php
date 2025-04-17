<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;


class CampaignController extends Controller
{
    public function index()
    {
        $search = request()->get('search', null);
        $withTrashed = request()->get('withTrashed', false);

        return view('campaign.index', [
            'campaigns' => Campaign::query()
                ->when($withTrashed, fn(Builder $query) => $query->withTrashed())
                ->when($search, fn(Builder $query) => $query
                    ->where('name', 'like', "%$search%")
                    ->orWhere('id', '=', $search))
                ->paginate(5)
                ->appends(compact('search', 'withTrashed')),
            'search' => $search,
            'withTrashed' => $withTrashed,
        ]);
    }

    public function create(?string $tab = null)
    {
        return view('campaign.create', [
            'tab' => $tab,
            'form' => match ($tab) {
                'template' => '_template',
                'schedule' => '_schedule',
                default => '_config'
            },
            'data' => array_merge([
            'name' => null,
            'subject' => null,
            'email_list_id' => null,
            'template_id' => null,
            'body' => null,
            'track_click' => null,
            'track_open' => null,
            'sent_at' => null,
        ], is_array(session('campaign::create')) ? session('campaign::create') : []),


        ]);
    }

    public function store(?string $tab = null)
{
    // Dados padrão
    $defaults = [
        'name' => null,
        'subject' => null,
        'email_list_id' => null,
        'template_id' => null,
        'body' => null,
        'track_click' => null,
        'track_open' => null,
        'sent_at' => null,
    ];

    // Session atual (ou vazia)
    $session = array_merge($defaults, is_array(session('campaign::create')) ? session('campaign::create') : []);

    // Dados da requisição
    $map = array_merge($defaults, request()->all());

    // Validação por etapa
    if (blank($tab)) {
        request()->validate([
            'name' => ['required', 'max:255'],
            'subject' => ['required', 'max:40'],
            'email_list_id' => ['nullable'],
            'template_id' => ['nullable'],
        ]);

        $toRoute = route('campaign.create', ['tab' => 'template']);
    } elseif ($tab === 'template') {
        request()->validate([
            'body' => ['required'],
            'track_click' => ['nullable'],
            'track_open' => ['nullable'],
        ]);

        $toRoute = route('campaign.create', ['tab' => 'schedule']);
    } elseif ($tab === 'schedule') {
        request()->validate([
            'sent_at' => ['required', 'date'],
        ]);

        // Salvar em banco ou outro destino aqui
        // Exemplo: Campaign::create($session);
        // (outra lógica conforme sua aplicação)

        session()->forget('campaign::create'); // limpa a sessão

        return redirect()->route('campaign.index')->with('success', 'Campanha criada com sucesso!');
    }

    // Atualiza valores na sessão
    foreach ($defaults as $key => $default) {
        $newValue = data_get($map, $key);
        if (filled($newValue)) {
            $session[$key] = $newValue;
        }
    }

    session()->put('campaign::create', $session);

    return redirect($toRoute);
}

    public function destroy(Campaign $campaign)
    {
        $campaign->delete();
        return back()->with('message', __('Campaign successfully deleted!'));
    }

    public function restore(Campaign $campaign)
    {
        $campaign->restore();
        return back()->with('message', __('Campaign successfully restored!'));
    }
}
