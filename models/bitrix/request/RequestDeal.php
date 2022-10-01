<?php

namespace app\models\bitrix\request;

use app\models\bitrix\Bitrix;

class RequestDeal extends Bitrix
{
    public static function CommandFindById(int $int)
    {
        $request = new RequestDeal();

        return $request->buildCommand("crm.deal.get", ["ID" => $int]);
    }
}
