composer create-project --prefer-dist cakephp/app:"^3.10" dace --no-blocking 
ó

COMPOSER_NO_BLOCKING=1 composer create-project --prefer-dist cakephp/app:"^3.10" dace

https://github.com/FriendsOfCake/bootstrap-ui
composer require friendsofcake/bootstrap-ui

https://github.com/maiconpinto/cakephp-adminlte-theme
composer require maiconpinto/cakephp-adminlte-theme

https://captcha.com/doc/php/cakephp-captcha.html
composer require captcha-com/cakephp-captcha:"4.*"

https://www.cakedc.com/yevgeny_tomenko/2024/12/21/cakedc-search-filter-plugin
composer require cakedc/search-filter

composer require phpoffice/phpspreadsheet

https://demos.themeselection.com/sneat-bootstrap-html-admin-template/documentation/extended-ui-sweetalert2.html


Obtener ids faltantes
SELECT a.id + 1 AS id_faltante
FROM periodos a
LEFT JOIN periodos b ON b.id = a.id + 1
WHERE b.id IS NULL AND a.id < (SELECT MAX(id) FROM periodos)
ORDER BY a.id;

Indice para estudiante_programas
CREATE INDEX idx_ep_estudiante_created
ON estudiante_programas(estudiante_id, created, id);

SELECT e.id, e.cedula, e.nombres, e.apellidos,
       ep.periodo_id, ep.sede_id
FROM estudiantes e
LEFT JOIN (
    SELECT ep.*,
           ROW_NUMBER() OVER (PARTITION BY ep.estudiante_id ORDER BY ep.created ASC, ep.id ASC) AS rn
    FROM estudiante_programas ep
) ep ON ep.estudiante_id = e.id AND ep.rn = 1;


Evita duplicados de registro
ALTER TABLE estudiante_programas 
ADD UNIQUE INDEX uq_estudiante_carrera_programa (estudiante_id, carrera_id, programa_id);

Ejecutar el SQL en la BD: ALTER TABLE usuarios ADD api_token VARCHAR(64) NULL;
Configurar la IP del servidor en Constants.java
Abrir E:\JavaSoft\Dace\ en Android Studio y compilar