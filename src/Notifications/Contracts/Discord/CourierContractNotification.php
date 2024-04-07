<?php

namespace BCAUS\Seat\Utilities\Notifications\Contracts\Discord;

use Seat\Eveapi\Models\Contracts\ContractDetail;
use Seat\Eveapi\Models\Universe\UniverseStation;
use Seat\Eveapi\Models\Universe\UniverseStructure;
use Seat\Notifications\Notifications\AbstractDiscordNotification;
use Seat\Notifications\Services\Discord\Messages\DiscordEmbed;
use Seat\Notifications\Services\Discord\Messages\DiscordEmbedField;
use Seat\Notifications\Services\Discord\Messages\DiscordMessage;
use Seat\Notifications\Traits\NotificationTools;

/**
 * Class CourierContractNotification.
 *
 * @package BCAUS\Seat\Utilities\Notifications\Characters
 */
class CourierContractNotification extends AbstractDiscordNotification
{
  use NotificationTools;

  private ContractDetail $contract;
  private int $embed_color;

  public function __construct(ContractDetail $contract)
  {
    $this->contract = $contract;
    switch($this->contract->status) {
      case 'outstanding':
        $this->embed_color = DiscordMessage::INFO;
        break;
      case 'in_progress':
        $this->embed_color = DiscordMessage::WARNING;
        break;
      case 'finished':
      case 'finished_issuer':
      case 'finished_contractor':
        $this->embed_color = DiscordMessage::SUCCESS;
        break;
      default:
        $this->embed_color = DiscordMessage::ERROR;
        break;
    }
  }

  public function populateMessage(DiscordMessage $message, $notifiable): void
  {
    $message
      ->content('A new courier contract has been posted!')
      ->from('SeAT Contract Monitor')
      ->embed(function (DiscordEmbed $embed) {
        $embed
          ->title($this->contract->title ?? 'No title')
          ->color($this->embed_color)
          ->fields([
            'Issuer' => $this->contract->issuer->name,
            'Assignee' => $this->contract->assignee->name,
            'Acceptor' => $this->contract->acceptor()->exists() ? $this->contract->acceptor->name : '-',
          ]);
      })

      ->embed(function (DiscordEmbed $embed) {
        $embed
          ->title("Route")
          ->color($this->embed_color)
          ->field(function (DiscordEmbedField $field) {
            $field->name('Start')
              ->value($this->contract->start_location->name)
              ->long();
          })
          ->field(function (DiscordEmbedField $field) {
            $field->name('End')
              ->value($this->contract->end_location->name)
              ->long();
          });
      })

      ->embed(function (DiscordEmbed $embed) {
        $embed
          ->title(sprintf('Status: %s',$this->contract->status))
          ->color($this->embed_color)
          ->fields([
            'Issued' => carbon($this->contract->date_issued)->toDayDateTimeString(),
            'Accepted' => $this->contract->date_accepted ? carbon(
              $this->contract->date_accepted
            )->toDayDateTimeString() : '-',
            'Completed' => $this->contract->date_completed ? carbon(
              $this->contract->date_completed
            )->toDayDateTimeString() : '-',
          ]);
      })
      
      ->embed(function (DiscordEmbed $embed) {
        $embed
          ->title('Details')
          ->color($this->embed_color)
          ->fields([
            'Reward' => number_format($this->contract->reward, 2),
            'Collateral' => number_format($this->contract->collateral, 2),
            'Volume' => sprintf('%s m3', number_format($this->contract->volume, 2)),
            'Days to Complete' => $this->contract->days_to_complete,
          ]);
      });
  }
}
