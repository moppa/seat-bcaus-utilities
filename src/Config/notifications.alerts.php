<?php

return [
  'bcaus_courier_contract_assigned' => [
    'label' => 'bcaus::alerts.courier_contract_assigned',
    'handlers' => [
      'discord' => \BCAUS\Seat\Utilities\Notifications\Contracts\Discord\CourierContractNotification::class,
    ],
  ],
];