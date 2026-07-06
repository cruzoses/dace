<?php
namespace App\Model\Table;

use ArrayObject;
use Cake\I18n\Time;
use Cake\ORM\Table;
use Cake\Event\Event;

class AppTable extends Table
{
    protected $searchFields = [];

    public function getSearchFields(): array
    {
        return $this->searchFields;
    }

    public function formatConditions(array $passedArgs = []): array
    {
        $conditions = [];
        foreach ($this->searchFields as $field => $config) {
            if (is_int($field)) {
                $field = $config;
                $config = ['type' => 'text'];
            }
            $value = $passedArgs[$field] ?? null;
            if ($value === '' || $value === null) {
                continue;
            }
            $alias = $this->getAlias();
            $filterField = $config['filterField'] ?? "$alias.$field";
            switch ($config['type'] ?? 'text') {
                case 'text':
                    $conditions[] = ["UPPER($filterField) LIKE" => "%" . mb_strtoupper($value, 'UTF-8') . "%"];
                    break;
                case 'exact':
                case 'select':
                    $conditions[] = [$filterField => $value];
                    break;
                case 'int':
                    $conditions[] = [$filterField => (int)$value];
                    break;
                case 'date':
                    $fecha = str_replace('/', '-', $value);
                    $fecha = date('Y-m-d', strtotime($fecha));
                    $conditions[] = ["DATE($filterField)" => $fecha];
                    break;
            }
        }
        if (empty($conditions)) {
            return [];
        }
        return $conditions;
    }
    
    public function beforeMarshal(Event $event, ArrayObject $data, ArrayObject $options)
    {
        $fechaCampos = ['fecha_nacimiento', 'inicio', 'cierre', 'fecha_notas', 'fecha_titulo'];
        foreach ($fechaCampos as $campo) {
            if (isset($data[$campo]) && !empty($data[$campo])) {
                $fechaOriginal = str_replace('/', '-', $data[$campo]);
                $fechaFormateada = Time::createFromFormat('d-m-Y', $fechaOriginal);
                if ($fechaFormateada === false) {
                    $fechaFormateada = Time::createFromFormat('Y-m-d', $fechaOriginal);
                }
                if ($fechaFormateada !== false) {
                    $data[$campo] = $fechaFormateada->format('Y-m-d');
                }
            }
        }


    }    
}
