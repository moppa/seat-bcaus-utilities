<?php

namespace BCAUS\Seat\Utilities\Observers;

use Seat\Eveapi\Models\Contracts\ContractDetail;
use Seat\Notifications\Models\NotificationGroup;
use Seat\Notifications\Traits\NotificationDispatchTool;

/**
 * Class ContractDetailObserver.
 *
 * @package BCAUS\Seat\Utilities\Observers
 */
class ContractDetailObserver
{
  use NotificationDispatchTool;

  /**
   * @param  ContractDetail  $contract
   */
  public function created(ContractDetail $contract)
  {
    // if the contract is old but just got loaded, don't notify
    if(
      $contract->date_expired && carbon($contract->date_expired) < now()->subHours(1)
      || $contract->status === 'finished'
    ) return;

    logger()->debug(
      sprintf('[BCAUS-Notifications][%d] Contract Detail - Queuing job due to created contract.', $contract->contract_id),
      $contract->toArray());

    $this->dispatch($contract);
  }

  public function updated(ContractDetail $contract)
  {
    logger()->debug(
      sprintf('[BCAUS-Notifications][%d] Contract Detail - Queuing job due to updated contract.', $contract->contract_id),
      $contract->toArray());

    $this->dispatch($contract);
  }

  /**
   * Queue notification based on User Creation.
   *
   * @param  ContractDetail  $contract
   */
  private function dispatch(ContractDetail $contract)
  {
    //if nothing changed, don't notify
    if(! $contract->isDirty())
    {
      logger()->debug(
        sprintf('[BCAUS-Notifications][%d] Contract Detail - Nothing changed.', $contract->contract_id),
        $contract->toArray());
      return;
    }
    if($contract->type == "courier")
    {
      $groups =NotificationGroup::with('alerts', 'affiliations')
        ->whereHas('alerts', function ($query) {
          $query->where('alert', 'bcaus_courier_contract_assigned');
        })->whereHas('affiliations', function ($query) use ($contract) {
          $query->where('affiliation_id', $contract->assignee_id);
        })->get();

      logger()->debug(
        sprintf('[BCAUS-Notifications][%d] Contract Detail - Notification groups.', $contract->contract_id),
        $groups->toArray());
      
      if(!empty($groups)) {
        $contract->load('start_location', 'end_location');
      
        logger()->debug(
            sprintf('[BCAUS-Notifications][%d] Contract Detail - Contract relations loaded.', $contract->contract_id),
            $contract->toArray());

        $this->dispatchNotifications('bcaus_courier_contract_assigned', $groups, function ($notificationClass) use ($contract) {
          return new $notificationClass($contract);
        });
      }
    }
  }
}
