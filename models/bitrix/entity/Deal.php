<?php

namespace app\models\bitrix\entity;

use app\models\bitrix\Bitrix;
use app\models\bitrix\request\RequestDeal;
use yii\base\Model;

class Deal extends Model
{
    public int $id;
    public string $title;
    public string $stage_id;

    private static $map_fields = [
        'ID' => 'id',
        'TITLE' => 'title',
        'STAGE_ID' => 'stage_id',
    ];

    public function rules()
    {
        return [
            ['id', 'int'],
            [['title', 'stage_id'], 'string'],
        ];
    }

    public static function findById(int $int)
    {
        $command["deal"] = RequestDeal::CommandFindById($int);

        $webhook = new Bitrix();
        $webhook->batchRequest($command);

        $data = collect($webhook->getLastResponse());
        $data = collect($data["result"]["result"]);

        $error = collect($webhook->getLastResponse()["result"]['result_error']);

        if($error->isNotEmpty())
        {
            return "Произошла ошибка";
        }

        $deal = new static();
        $deal->fill($data->get("deal"));

        return $deal;
    }

    public function fill(Array $fields)
    {
        foreach ($fields as $field_id => $value)
        {
            $map_fields = collect(static::$map_fields);

            if($map_fields->has($field_id))
            {
                $var_name = $map_fields->get($field_id);

                if($this->canGetProperty($var_name))
                {
                    $this->$var_name = $value;
                }
            }
        }
    }
}