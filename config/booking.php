<?php

return [

    /*
    | Horas mínimas de anticipación para agendar un cupo.
    | Ej. 2 = no se puede reservar para dentro de menos de 2 horas.
    */
    'min_anticipation_hours' => env('BOOKING_MIN_ANTICIPATION_HOURS', 2),

    /*
    | Cuántos días hacia adelante se ofrecen cupos (horizonte de agenda).
    */
    'horizon_days' => env('BOOKING_HORIZON_DAYS', 30),

    /*
    | Minutos que una cita queda reservada esperando el pago antes de expirar
    | y liberar el cupo. (Se usa en el Entregable 4.)
    */
    'hold_minutes' => env('BOOKING_HOLD_MINUTES', 15),

];
