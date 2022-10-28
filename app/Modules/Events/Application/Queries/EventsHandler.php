<?php

namespace Modules\Events\Application\Queries;

use App\Commands\AskEvents;

abstract class EventsHandler
{
    protected static function getScopeFromFindEvents(AskEvents $query): array
    {
        $scope = [];
        if ($query->type) {
            $scope['type'] = $query->type;
        } else {
            $scope['type'] = ['<>' => 'sentryTransaction'];
        }
        if ($query->projectId) {
            $scope['project_id'] = $query->projectId;
        }
        if ($query->transactionId) {
            $scope['transaction_id'] = $query->transactionId;
        }

        return $scope;
    }
}
