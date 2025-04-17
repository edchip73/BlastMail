<?php

namespace App\Http\Controllers;

use App\Models\EmailList;
use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

class EmailListController extends Controller
{
    public function index()
    {
        $search = request()->search;
        $emailLists = EmailList::query()
        ->withCount('subscribers')
        ->when($search, fn(Builder $query) => $query
            ->where('title', 'like', "%$search%")
            ->orWhere('id', '=', $search)
            )
            ->paginate(5);

        return view('email-list.index', [
            'emailLists' => $emailLists,
            'search' => request()->search
        ]);
    }

    public function create()
    {
        return view('email-list.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => ['required', 'max:255'],
            'file' => ['required', 'file', 'mimetypes:text/plain,text/csv,application/vnd.ms-excel'],
        ]);

        $emails = $this->getEmailsFromCsvFile($request->file('file'));

        DB::transaction(function () use ($request, $emails) {
            $emailList = EmailList::query()->create(['title' => $request->title]);
            $emailList->subscribers()->createMany($emails);
        });

        return to_route('email-list.index');
    }

    /**
     * Lê e extrai e-mails e nomes de um arquivo CSV.
     */
    private function getEmailsFromCsvFile(UploadedFile $file): array
    {
        $emails = [];
        $handle = fopen($file->getRealPath(), 'r');

        if ($handle === false) {
            throw new \Exception('Não foi possível abrir o arquivo CSV.');
        }

        // Lê cabeçalho
        $header = fgetcsv($handle, 1000, ',');
        $header = array_map('strtolower', $header); // Padroniza

        $emailIndex = array_search('email', $header);
        $nameIndex = array_search('nome', $header); // Adicionado

        if ($emailIndex === false || $nameIndex === false) {
            throw new \Exception('O arquivo CSV precisa conter as colunas "email" e "nome".');
        }

        // Lê linhas do CSV
        while (($row = fgetcsv($handle, 1000, ',')) !== false) {
            if (!isset($row[$emailIndex], $row[$nameIndex])) {
                continue; // Pula linhas incompletas
            }

            $email = trim($row[$emailIndex]);
            $name = trim($row[$nameIndex]);

            if (filter_var($email, FILTER_VALIDATE_EMAIL) && $name !== '') {
                $emails[] = [
                    'email' => $email,
                    'name' => $name
                ];
            }
        }

        fclose($handle);

        return $emails;
    }

    public function show(EmailList $emailList)
    {
        //
    }

    public function edit(EmailList $emailList)
    {
        //
    }

    public function update(Request $request, EmailList $emailList)
    {
        //
    }

    public function destroy(EmailList $emailList)
    {
        //
    }
}
