<?php
namespace App\View\Helper;

use Cake\View\Helper;

class PermisoHelper extends Helper
{
    public function tiene($nRol)
    {
        $rols = $this->getView()->getRequest()->getSession()->read('Auth.User.rols');
        if (empty($rols)) {
            return false;
        }

        $roles = (array)$nRol;

        foreach ($rols as $rol) {
            foreach ($roles as $r) {
                if (is_numeric($r)) {
                    if ((int)$rol['id'] === (int)$r) {
                        return true;
                    }
                } else {
                    if (strcasecmp($rol['nombre'], $r) === 0) {
                        return true;
                    }
                }
            }
        }

        return false;
    }
}
