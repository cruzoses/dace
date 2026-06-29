<?php
namespace App\Model\Table;

use Cake\ORM\Table;

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
                    $conditions[] = ["$filterField LIKE" => "%{$value}%"];
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
        return array_merge(...$conditions);
    }
}
