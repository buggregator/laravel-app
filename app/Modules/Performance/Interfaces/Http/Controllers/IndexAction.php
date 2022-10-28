<?php

namespace Modules\Performance\Interfaces\Http\Controllers;

use App\Commands\FindAllProjects;
use App\Commands\FindAllTransactions;
use App\Contracts\Query\QueryBus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Interfaces\Http\Controllers\Controller;
use Spatie\RouteAttributes\Attributes\Get;

class IndexAction extends Controller
{
    private function getTransactionsProjects(QueryBus $bus): array
    {
        $projects = $bus->ask(new FindAllProjects());
        $transactions = $bus->ask(new FindAllTransactions());
        $dataItems = DB::table('events')
            ->select('project_id', 'transaction_id', DB::raw('count(*) as total_events'))
            ->where('transaction_id', '<>', '')
            ->groupBy('project_id', 'transaction_id')
            ->get()->all();
        $transactionsProjects = [];
        $names = [
            'project_id' => [],
            'transaction_id' => [],
        ];
        foreach ($projects as $project) {
            $names['project_id'][$project->getId()] = $project->getName();
        }
        foreach ($transactions as $transaction) {
            $names['transaction_id'][$transaction->getId()] = $transaction->getName();
        }
        foreach ($dataItems as $dataItem) {
            $transactionsProjects[] = [
                'projectId' => $dataItem->project_id,
                'transactionId' => $dataItem->transaction_id,
                'totalEvents' => $dataItem->total_events,
                'projectName' => $names['project_id'][$dataItem->project_id],
                'transactionName' => $names['transaction_id'][$dataItem->transaction_id],
            ];
        }

        return $transactionsProjects;
    }

    #[Get(uri: '/performance', name: 'performance', middleware: 'auth')]
    public function performanceIndex(Request $request, QueryBus $bus): \Inertia\Response
    {
        return Inertia::render('Performance/Index', [
            'transactionsProjects' => $this->getTransactionsProjects($bus),
        ]);
    }
}
