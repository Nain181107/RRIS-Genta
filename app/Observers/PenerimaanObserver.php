<?php

namespace App\Observers;

use App\Events\DataChanged;

class PenerimaanObserver
{
    public function created($model)
    {
        broadcast(new DataChanged('penerimaan'))->toOthers();
    }

    public function updated($model)
    {
        broadcast(new DataChanged('penerimaan'))->toOthers();
    }

    public function deleted($model)
    {
        broadcast(new DataChanged('penerimaan'))->toOthers();
    }
}
