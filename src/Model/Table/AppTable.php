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
    
    public function beforeMarshal(Event $event, ArrayObject $data, ArrayObject $options)
    {
        // Verifica si el campo de fecha existe en el request
        if ( isset( $data['fecha_nacimiento'] ) ) 
        {            
            $fechaOriginal = str_replace('/', '-',$data['fecha_nacimiento']);

            // Si la fecha no está vacía, la convertimos
            if (!empty($fechaOriginal)) 
            {
                // Convierte el formato dd-mm-yyyy a yyyy-mm-dd
                $fechaFormateada = Time::createFromFormat('d-m-Y', $fechaOriginal);
                
                // Asigna el valor corregido para que CakePHP lo guarde correctamente
                $data['fecha_nacimiento'] = $fechaFormateada->format('Y-m-d');
            }
        }
        if ( isset( $data['inicio'] ) ) 
        {            
            $fechaOriginal = str_replace('/', '-',$data['inicio']);

            // Si la fecha no está vacía, la convertimos
            if (!empty($fechaOriginal)) 
            {
                // Convierte el formato dd-mm-yyyy a yyyy-mm-dd
                $fechaFormateada = Time::createFromFormat('d-m-Y', $fechaOriginal);
                
                // Asigna el valor corregido para que CakePHP lo guarde correctamente
                $data['inicio'] = $fechaFormateada->format('Y-m-d');
            }
        }
        if ( isset( $data['cierre'] ) ) 
        {            
            $fechaOriginal = str_replace('/', '-',$data['cierre']);

            // Si la fecha no está vacía, la convertimos
            if (!empty($fechaOriginal)) 
            {
                // Convierte el formato dd-mm-yyyy a yyyy-mm-dd
                $fechaFormateada = Time::createFromFormat('d-m-Y', $fechaOriginal);
                
                // Asigna el valor corregido para que CakePHP lo guarde correctamente
                $data['cierre'] = $fechaFormateada->format('Y-m-d');
            }
        }
    }    
}
