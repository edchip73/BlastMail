<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Models\EmailList;
use App\Models\Subscriber; 
use Illuminate\Validation\Rule;


class SubscriberController extends Controller
{
    public function index(EmailList $emailList)
    {
        $search = request()->search;
        $showTrash = request()->get('showTrash', false);

        return view('subscriber.index', [
            'emailList'=> $emailList,        
            'subscribers' => $emailList->subscribers()
            ->when($showTrash, fn (Builder $query) => $query->withTrashed())
            ->when($search, fn (Builder $query) => $query->where('name', 'like', "%$search%"))
                ->orWhere('email', 'like', "%$search%")
                ->orWhere('id', '=', $search)
            ->paginate()
            ->appends(compact('search')),
            'search' => $search,
            'showTrash' => $showTrash,
            
        ]);
    }

    public function create(EmailList $emailList)
    {
        return view('subscriber.create', compact('emailList'));
    }

    public function store(EmailList $emailList)
    {
        $data = request()->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('subscribers')->where('email_list_id', $emailList->id)],
        ]);

        $emailList->subscribers()->create($data);
        return to_route('subscribers.index', $emailList)->with('message', __('Subscriber successfully created!'));
    }

    public function destroy(mixed $list, Subscriber $subscriber)
    {
        $subscriber->delete();

        return back()->with('message', __('Subscriber deleted from the list!'));
    }
}
