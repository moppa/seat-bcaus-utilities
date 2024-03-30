<?php

namespace BCAUS\Seat\Structures\Observers;

use BCAUS\Seat\Structures\Models\DrillStatus;
use Seat\Eveapi\Models\Character\CharacterNotification;


/**
 * Class CharacterNotificationObserver.
 *
 * @package BCAUS\Seat\Structures\Observers
 */
class CharacterNotificationObserver
{
    const EXPIRATION_DELAY = 3600;

    const MOON_TYPES = ['MoonminingExtractionStarted', 'MoonminingExtractionFinished', 'MoonminingExtractionCancelled'];

    /**
     * @param  \Seat\Eveapi\Models\Character\CharacterNotification  $notification
     */
    public function created(CharacterNotification $notification)
    {
        logger()->debug(
            sprintf('[BCAUS-StructureNotification][%d] Character Notification - Notification received', $notification->notification_id),
            $notification->toArray());

        $this->dispatch($notification);
    }

    /**
     * Queue notification based on notification kind.
     *
     * @param  \Seat\Eveapi\Models\Character\CharacterNotification  $notification
     */
    private function dispatch(CharacterNotification $notification)
    {
        if(in_array($notification->type, self::MOON_TYPES)) {
            logger()->debug(sprintf('[BCAUS-StructureNotification][%d] Was Moon mining ping', $notification->notification_id), $notification->toArray());

            $drillStatus = DrillStatus::where('d_structure_id', $notification->text['structureID'])->first();
            if($drillStatus != null && $drillStatus->notification_id > $notification->notification_id) {
                logger()->info(sprintf('[BCAUS-StructureNotification][%d] Existing notification is more recent %d', $notification->notification_id, $drillStatus->notification_id));
                return;
            }

            if($drillStatus == null) {
                logger()->debug(sprintf('[BCAUS-StructureNotification][%d] Creating DrillStatus', $notification->notification_id), $notification->toArray());
                $drillStatus = new DrillStatus(['d_structure_id' => $notification->text['structureID']]);
            }

            $drillStatus->notification_id = $notification->notification_id;
            $drillStatus->timestamp = $notification->timestamp;

            if($notification->type == 'MoonminingExtractionStarted') {
                $drillStatus->ready_time = $this->mssqlTimestampToDate($notification->text['readyTime']);
            } else {
                $drillStatus->ready_time = null;
            }

            $drillStatus->save();
            logger()->info(sprintf('[BCAUS-StructureNotification][%d] Updated Drillstatus', $notification->notification_id), $drillStatus->toArray());
        }
    }

    /**
     * @param  int  $timestamp
     * @return \Carbon\Carbon
     *
     * @author https://github.com/flakas/reconbot/blob/master/reconbot/notificationprinters/esi/printer.py#L317
     */
    private function mssqlTimestampToDate(int $timestamp)
    {
        // Convert microsoft epoch to unix epoch
        // Based on: http://www.wiki.eve-id.net/APIv2_Char_NotificationTexts_XML

        $seconds = $timestamp / 10000000 - 11644473600;

        return carbon()->createFromTimestamp($seconds, 'UTC');
    }
}
